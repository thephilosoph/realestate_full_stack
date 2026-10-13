<?php

namespace Tests\Feature;

use App\Models\Feature;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PropertyTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        return $user;
    }

    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'title' => ['en' => 'Seaside Villa', 'ar' => 'فيلا على البحر'],
            'description' => ['en' => 'A villa.', 'ar' => 'فيلا.'],
            'location' => ['en' => 'Alexandria', 'ar' => 'الإسكندرية'],
            'price' => 250000.50,
            'bedrooms' => 3,
            'bathrooms' => 2,
            'type' => 'Villa',
            'is_published' => true,
        ], $overrides);
    }

    // ── Public API ─────────────────────────────────────────

    public function test_public_index_only_shows_published_properties(): void
    {
        Property::create($this->validPayload(['is_published' => true]));
        Property::create($this->validPayload(['is_published' => false]));

        $this->getJson('/api/v1/properties')
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }

    public function test_public_show_returns_404_for_unpublished_property(): void
    {
        $property = Property::create($this->validPayload(['is_published' => false]));

        $this->getJson("/api/v1/properties/{$property->id}")
            ->assertNotFound();
    }

    public function test_public_show_returns_published_property_with_images_and_features(): void
    {
        $property = Property::create($this->validPayload());
        $feature = Feature::create(['name' => ['en' => 'Pool', 'ar' => 'مسبح']]);
        $property->features()->attach($feature);

        $this->getJson("/api/v1/properties/{$property->id}")
            ->assertOk()
            ->assertJsonPath('data.title.en', 'Seaside Villa')
            ->assertJsonPath('data.title.ar', 'فيلا على البحر')
            ->assertJsonCount(1, 'data.features');
    }

    // ── Admin CRUD ─────────────────────────────────────────

    public function test_guest_cannot_create_property(): void
    {
        $this->postJson('/api/v1/admin/properties', $this->validPayload())
            ->assertUnauthorized();
    }

    public function test_guest_cannot_delete_property(): void
    {
        $property = Property::create($this->validPayload());

        $this->deleteJson("/api/v1/admin/properties/{$property->id}")
            ->assertUnauthorized();
    }

    public function test_admin_can_create_property_with_features(): void
    {
        $this->admin();
        $pool = Feature::create(['name' => ['en' => 'Pool', 'ar' => 'مسبح']]);
        $gym = Feature::create(['name' => ['en' => 'Gym', 'ar' => 'نادي']]);

        $this->postJson('/api/v1/admin/properties', $this->validPayload([
            'feature_ids' => [$pool->id, $gym->id],
        ]))
            ->assertCreated()
            ->assertJsonPath('data.title.ar', 'فيلا على البحر')
            ->assertJsonCount(2, 'data.features');

        $this->assertDatabaseHas('properties', ['price' => 250000.50]);
    }

    public function test_admin_can_update_property_and_sync_features(): void
    {
        $this->admin();
        $property = Property::create($this->validPayload());
        $oldFeature = Feature::create(['name' => ['en' => 'Old', 'ar' => 'قديم']]);
        $newFeature = Feature::create(['name' => ['en' => 'New', 'ar' => 'جديد']]);
        $property->features()->attach($oldFeature);

        $this->putJson("/api/v1/admin/properties/{$property->id}", [
            'price' => 999999.99,
            'is_published' => false,
            'feature_ids' => [$newFeature->id],
        ])->assertOk();

        $property->refresh();
        $this->assertEquals('999999.99', $property->price);
        $this->assertFalse($property->is_published);
        $this->assertEquals([$newFeature->id], $property->features->pluck('id')->all());
    }

    public function test_admin_delete_removes_property_and_image_files(): void
    {
        Storage::fake('public');
        $this->admin();
        $property = Property::create($this->validPayload());
        $property->images()->create([
            'path' => 'properties/'.$property->id.'/a.jpg',
            'is_primary' => true,
            'sort_order' => 1,
        ]);
        Storage::disk('public')->put('properties/'.$property->id.'/a.jpg', 'content');

        $this->deleteJson("/api/v1/admin/properties/{$property->id}")->assertOk();

        $this->assertDatabaseMissing('properties', ['id' => $property->id]);
        $this->assertDatabaseCount('property_images', 0);
        Storage::disk('public')->assertMissing('properties/'.$property->id.'/a.jpg');
    }

    // ── Validation ─────────────────────────────────────────

    public function test_validation_requires_title_in_both_locales(): void
    {
        $this->admin();

        $this->postJson('/api/v1/admin/properties', $this->validPayload([
            'title' => ['en' => 'Only English'],
        ]))->assertStatus(422);
    }

    public function test_validation_rejects_negative_price(): void
    {
        $this->admin();

        $this->postJson('/api/v1/admin/properties', $this->validPayload([
            'price' => -5,
        ]))->assertStatus(422);
    }

    public function test_validation_rejects_non_image_upload(): void
    {
        Storage::fake('public');
        $this->admin();

        $this->postJson('/api/v1/admin/properties', $this->validPayload([
            'images' => [UploadedFile::fake()->create('report.pdf', 100, 'application/pdf')],
        ]))->assertStatus(422);
    }

    public function test_validation_rejects_unknown_feature_id(): void
    {
        $this->admin();

        $this->postJson('/api/v1/admin/properties', $this->validPayload([
            'feature_ids' => [99999],
        ]))->assertStatus(422);
    }

    // ── Images ─────────────────────────────────────────────

    public function test_create_with_images_stores_files_and_sets_first_as_primary(): void
    {
        Storage::fake('public');
        $this->admin();

        $response = $this->post('/api/v1/admin/properties', $this->validPayload([
            'images' => [
                UploadedFile::fake()->image('one.jpg'),
                UploadedFile::fake()->image('two.jpg'),
            ],
        ]), ['Accept' => 'application/json']);

        $response->assertCreated();

        $images = PropertyImage::query()->orderBy('sort_order')->get();
        $this->assertCount(2, $images);
        $this->assertTrue((bool) $images[0]->is_primary);
        $this->assertFalse((bool) $images[1]->is_primary);
        Storage::disk('public')->assertExists($images[0]->path);
        Storage::disk('public')->assertExists($images[1]->path);
    }

    public function test_updating_property_with_new_images_keeps_existing_primary(): void
    {
        Storage::fake('public');
        $this->admin();
        $property = Property::create($this->validPayload());
        $existing = $property->images()->create([
            'path' => 'properties/'.$property->id.'/existing.jpg',
            'is_primary' => true,
            'sort_order' => 1,
        ]);

        $this->post("/api/v1/admin/properties/{$property->id}", array_merge(
            $this->validPayload(),
            ['_method' => 'PUT', 'images' => [UploadedFile::fake()->image('new.jpg')]],
        ), ['Accept' => 'application/json'])->assertOk();

        $this->assertTrue((bool) $existing->fresh()->is_primary);
        $this->assertCount(2, $property->images()->get());
    }

    public function test_delete_image_removes_file_and_reassigns_primary(): void
    {
        Storage::fake('public');
        $this->admin();
        $property = Property::create($this->validPayload());
        $primary = $property->images()->create([
            'path' => 'properties/'.$property->id.'/p.jpg',
            'is_primary' => true,
            'sort_order' => 1,
        ]);
        $secondary = $property->images()->create([
            'path' => 'properties/'.$property->id.'/s.jpg',
            'is_primary' => false,
            'sort_order' => 2,
        ]);
        Storage::disk('public')->put($primary->path, 'content');

        $this->deleteJson("/api/v1/admin/property-images/{$primary->id}")->assertOk();

        Storage::disk('public')->assertMissing($primary->path);
        $this->assertTrue((bool) $secondary->fresh()->is_primary);
    }

    public function test_set_primary_moves_flag_to_selected_image(): void
    {
        $this->admin();
        $property = Property::create($this->validPayload());
        $first = $property->images()->create([
            'path' => 'properties/'.$property->id.'/1.jpg',
            'is_primary' => true,
            'sort_order' => 1,
        ]);
        $second = $property->images()->create([
            'path' => 'properties/'.$property->id.'/2.jpg',
            'is_primary' => false,
            'sort_order' => 2,
        ]);

        $this->postJson("/api/v1/admin/property-images/{$second->id}/primary")->assertOk();

        $this->assertFalse((bool) $first->fresh()->is_primary);
        $this->assertTrue((bool) $second->fresh()->is_primary);
    }
}
