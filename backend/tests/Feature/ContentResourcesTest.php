<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Faq;
use App\Models\Feature;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ContentResourcesTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        return $user;
    }

    // ── Features ───────────────────────────────────────────

    public function test_admin_can_create_feature(): void
    {
        $this->admin();

        $this->postJson('/api/v1/admin/features', [
            'name' => ['en' => 'Pool', 'ar' => 'مسبح'],
        ])
            ->assertCreated()
            ->assertJsonPath('data.name.en', 'Pool');
    }

    public function test_admin_can_delete_feature(): void
    {
        $this->admin();
        $feature = Feature::create(['name' => ['en' => 'Gym', 'ar' => 'نادي']]);

        $this->deleteJson("/api/v1/admin/features/{$feature->id}")
            ->assertOk();

        $this->assertDatabaseMissing('features', ['id' => $feature->id]);
    }

    public function test_guest_cannot_create_feature(): void
    {
        $this->postJson('/api/v1/admin/features', [
            'name' => ['en' => 'Pool', 'ar' => 'مسبح'],
        ])->assertUnauthorized();
    }

    // ── FAQs ───────────────────────────────────────────────

    public function test_admin_can_crud_faq(): void
    {
        $this->admin();

        $create = $this->postJson('/api/v1/admin/faqs', [
            'question' => ['en' => 'Q?', 'ar' => 'س؟'],
            'answer' => ['en' => 'A', 'ar' => 'ج'],
            'is_published' => true,
        ])->assertCreated();

        $id = $create->json('data.id') ?? $create->json('id');

        $this->putJson("/api/v1/admin/faqs/{$id}", [
            'is_published' => false,
        ])->assertOk();

        $this->deleteJson("/api/v1/admin/faqs/{$id}")->assertOk();
        $this->assertDatabaseMissing('faqs', ['id' => $id]);
    }

    public function test_public_faqs_hide_unpublished(): void
    {
        Faq::create([
            'question' => ['en' => 'Public', 'ar' => 'عام'],
            'answer' => ['en' => 'Yes', 'ar' => 'نعم'],
            'is_published' => true,
        ]);
        Faq::create([
            'question' => ['en' => 'Hidden', 'ar' => 'مخفي'],
            'answer' => ['en' => 'No', 'ar' => 'لا'],
            'is_published' => false,
        ]);

        $this->getJson('/api/v1/faqs')
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }

    public function test_faq_validation_requires_both_locales(): void
    {
        $this->admin();

        $this->postJson('/api/v1/admin/faqs', [
            'question' => ['en' => 'Only EN'],
            'answer' => ['en' => 'A', 'ar' => 'ج'],
        ])->assertStatus(422);
    }

    // ── Testimonials ───────────────────────────────────────

    public function test_admin_can_create_testimonial_with_avatar(): void
    {
        Storage::fake('public');
        $this->admin();

        $this->post('/api/v1/admin/testimonials', [
            'name' => ['en' => 'Wade', 'ar' => 'ويد'],
            'title' => ['en' => 'Great', 'ar' => 'رائع'],
            'body' => ['en' => 'Body', 'ar' => 'نص'],
            'rating' => 5,
            'is_published' => 1,
            'avatar' => UploadedFile::fake()->image('avatar.jpg'),
        ], [
            'Accept' => 'application/json',
        ])->assertCreated();

        $this->assertDatabaseCount('testimonials', 1);
    }

    public function test_public_testimonials_only_published(): void
    {
        Testimonial::create([
            'name' => ['en' => 'A', 'ar' => 'أ'],
            'title' => ['en' => 'T', 'ar' => 'ع'],
            'body' => ['en' => 'B', 'ar' => 'ن'],
            'is_published' => true,
        ]);
        Testimonial::create([
            'name' => ['en' => 'B', 'ar' => 'ب'],
            'title' => ['en' => 'T2', 'ar' => 'ع2'],
            'body' => ['en' => 'B2', 'ar' => 'ن2'],
            'is_published' => false,
        ]);

        $this->getJson('/api/v1/testimonials')
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }

    // ── Clients ────────────────────────────────────────────

    public function test_admin_can_create_client_with_logo(): void
    {
        Storage::fake('public');
        $this->admin();

        $this->post('/api/v1/admin/clients', [
            'name' => ['en' => 'Acme', 'ar' => 'أكمي'],
            'is_published' => 1,
            'logo' => UploadedFile::fake()->image('logo.png'),
        ], [
            'Accept' => 'application/json',
        ])->assertCreated();
    }

    public function test_guest_cannot_delete_client(): void
    {
        $client = Client::create([
            'name' => ['en' => 'X', 'ar' => 'س'],
            'logo_path' => 'clients/x.png',
            'is_published' => true,
        ]);

        $this->deleteJson("/api/v1/admin/clients/{$client->id}")
            ->assertUnauthorized();
    }

    // ── Team members ───────────────────────────────────────

    public function test_admin_can_crud_team_member(): void
    {
        $this->admin();

        $res = $this->postJson('/api/v1/admin/team-members', [
            'name' => ['en' => 'Sarah', 'ar' => 'سارة'],
            'role' => ['en' => 'CEO', 'ar' => 'مديرة'],
            'is_published' => true,
        ])->assertCreated();

        $id = $res->json('data.id') ?? $res->json('id');

        $this->putJson("/api/v1/admin/team-members/{$id}", [
            'role' => ['en' => 'Founder', 'ar' => 'مؤسسة'],
        ])->assertOk();

        $this->deleteJson("/api/v1/admin/team-members/{$id}")->assertOk();
    }

    public function test_public_team_hides_unpublished(): void
    {
        TeamMember::create([
            'name' => ['en' => 'A', 'ar' => 'أ'],
            'role' => ['en' => 'Dev', 'ar' => 'مطور'],
            'is_published' => true,
        ]);
        TeamMember::create([
            'name' => ['en' => 'B', 'ar' => 'ب'],
            'role' => ['en' => 'HR', 'ar' => 'موارد'],
            'is_published' => false,
        ]);

        $this->getJson('/api/v1/team-members')
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }
}