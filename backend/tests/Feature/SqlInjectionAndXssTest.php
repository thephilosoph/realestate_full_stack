<?php

namespace Tests\Feature;

use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * The API's protections against SQL injection and XSS:
 *
 * - SQL injection: all queries go through Eloquent with parameter binding;
 *   the search term, filters and sort direction are bound values, and the
 *   sort column + sort locale are validated against whitelists before they
 *   ever reach a query.
 * - XSS: the API returns JSON only (application/json), user content is
 *   stored and returned verbatim as inert text, and the frontend renders it
 *   via Vue interpolation (no v-html), so payloads are never executable.
 *
 * These tests verify that malicious payloads are treated as literal data.
 */
class SqlInjectionAndXssTest extends TestCase
{
    use RefreshDatabase;

    // ── SQL injection ──────────────────────────────────────

    public function test_search_term_is_bound_and_never_interpreted_as_sql(): void
    {
        Property::create($this->property(['title' => ['en' => 'Villa', 'ar' => 'فيلا']]));

        $payloads = [
            "' OR 1=1 --",
            "'; DROP TABLE properties; --",
            "' UNION SELECT id, password FROM users --",
        ];

        foreach ($payloads as $payload) {
            $this->getJson('/api/v1/properties?search='.rawurlencode($payload))
                ->assertOk()
                // No row matches the literal string, and the injection must
                // not bypass the published filter or dump other rows.
                ->assertJsonCount(0, 'data');
        }

        $this->assertDatabaseCount('properties', 1);
    }

    public function test_injected_search_cannot_leak_unpublished_content(): void
    {
        Property::create($this->property(['title' => ['en' => 'Secret Villa', 'ar' => 'فيلا'], 'is_published' => false]));
        Property::create($this->property(['title' => ['en' => 'Secret Loft', 'ar' => 'لوفت']]));

        // Even a search term that matches real rows must not expose the
        // unpublished one.
        $this->getJson('/api/v1/properties?search='.rawurlencode("' OR '1'='1"))
            ->assertOk()
            ->assertJsonCount(0, 'data');

        $this->getJson('/api/v1/properties?search=Secret')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title.en', 'Secret Loft');
    }

    public function test_sort_column_whitelist_blocks_injection(): void
    {
        Property::create($this->property());

        $payloads = [
            'id; DROP TABLE properties; --',
            'id) OR 1=1 --',
            'price FROM properties; --',
        ];

        foreach ($payloads as $payload) {
            // The FormRequest whitelist rejects any sort column that is not
            // explicitly allowed.
            $this->getJson('/api/v1/properties?sort='.rawurlencode($payload))
                ->assertStatus(422);
        }

        $this->assertGreaterThan(
            0,
            DB::table('properties')->count(),
            'properties table must survive DROP attempts',
        );
    }

    public function test_sort_locale_whitelist_blocks_injection(): void
    {
        Property::create($this->property());

        // sort_locale is interpolated into a JSON path (title->{$locale}),
        // so it is validated to `in:en,ar` — anything else is rejected.
        $this->getJson('/api/v1/properties?sort=title&sort_locale='.rawurlencode("en') OR 1=1 --"))
            ->assertStatus(422);
    }

    public function test_filter_values_are_bound_not_concatenated(): void
    {
        Property::create($this->property(['type' => 'Villa']));

        $this->getJson('/api/v1/properties?type='.rawurlencode("Villa' OR 1=1 --"))
            ->assertOk()
            ->assertJsonCount(0, 'data');

        $this->getJson('/api/v1/properties?type=Villa')
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }

    public function test_route_id_parameter_cannot_inject(): void
    {
        Property::create($this->property());

        $this->getJson('/api/v1/properties/'.rawurlencode('1 OR 1=1'))
            ->assertNotFound();

        $this->getJson('/api/v1/properties/'.rawurlencode("1; DROP TABLE properties; --"))
            ->assertNotFound();

        $this->assertGreaterThan(0, DB::table('properties')->count());
    }

    // ── XSS ────────────────────────────────────────────────

    public function test_script_payload_is_stored_and_served_as_inert_text(): void
    {
        $payload = "<script>alert('xss')</script>";
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/v1/admin/properties', $this->property([
            'title' => ['en' => $payload, 'ar' => $payload],
        ]))->assertCreated();

        $response = $this->getJson('/api/v1/properties?search='.rawurlencode('alert'));

        $response
            ->assertOk()
            // Served as application/json, never as text/html the browser
            // could execute.
            ->assertHeader('content-type', 'application/json')
            ->assertJsonPath('data.0.title.en', $payload);
    }

    public function test_event_handler_payload_is_inert_data(): void
    {
        $payload = '<img src=x onerror=alert(1)>';
        Property::create($this->property([
            'description' => ['en' => $payload, 'ar' => $payload],
        ]));

        $this->getJson('/api/v1/properties')
            ->assertOk()
            ->assertHeader('content-type', 'application/json')
            ->assertJsonPath('data.0.description.en', $payload);
    }

    public function test_html_payloads_in_search_and_filters_are_literal(): void
    {
        Property::create($this->property(['type' => 'Villa']));

        $payloads = [
            'search' => '<script>alert(1)</script>',
            'type' => '<img src=x onerror=alert(1)>',
        ];

        foreach ($payloads as $key => $payload) {
            $this->getJson('/api/v1/properties?'.$key.'='.rawurlencode($payload))
                ->assertOk()
                ->assertJsonCount(0, 'data');
        }
    }

    public function test_xss_in_admin_content_does_not_break_admin_api(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $payload = "<svg/onload=alert('xss')>";

        $id = $this->postJson('/api/v1/admin/properties', $this->property([
            'title' => ['en' => $payload, 'ar' => $payload],
        ]))->assertCreated()->json('data.id');

        $this->putJson("/api/v1/admin/properties/{$id}", [
            'title' => ['en' => $payload, 'ar' => $payload],
        ])->assertOk()
            ->assertHeader('content-type', 'application/json')
            ->assertJsonPath('data.title.en', $payload);
    }

    // ── Helpers ────────────────────────────────────────────

    private function property(array $overrides = []): array
    {
        return array_merge([
            'title' => ['en' => 'Seaside Villa', 'ar' => 'فيلا على البحر'],
            'description' => ['en' => 'A villa.', 'ar' => 'فيلا.'],
            'location' => ['en' => 'Alexandria', 'ar' => 'الإسكندرية'],
            'price' => 250000,
            'type' => 'Villa',
            'is_published' => true,
        ], $overrides);
    }
}
