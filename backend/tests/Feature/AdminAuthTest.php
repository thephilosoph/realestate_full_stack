<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_login(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@estatein.test',
            'password' => 'password',
        ]);

        $this->postJson('/api/v1/admin/login', [
            'email' => 'admin@estatein.test',
            'password' => 'password',
        ])
            ->assertOk()
            ->assertJsonStructure(['token', 'token_type', 'user' => ['id', 'name', 'email']]);
    }

    public function test_login_fails_with_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'admin@estatein.test',
            'password' => 'password',
        ]);

        $this->postJson('/api/v1/admin/login', [
            'email' => 'admin@estatein.test',
            'password' => 'wrong',
        ])->assertStatus(422);
    }

    public function test_guest_cannot_access_me(): void
    {
        $this->getJson('/api/v1/admin/me')->assertUnauthorized();
    }

    public function test_authenticated_admin_can_access_me(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $this->getJson('/api/v1/admin/me')
            ->assertOk()
            ->assertJsonPath('user.email', $user->email);
    }

    public function test_admin_can_logout(): void
{
    $user = User::factory()->create();

    $tokenResult = $user->createToken('admin-token');
    $plainTextToken = $tokenResult->plainTextToken;
    $tokenId = $tokenResult->accessToken->id;

    $this->withHeader('Authorization', 'Bearer '.$plainTextToken)
        ->postJson('/api/v1/admin/logout')
        ->assertOk();

    // Token should be gone from DB
    $this->assertDatabaseMissing('personal_access_tokens', [
        'id' => $tokenId,
    ]);

    // Clear cached guard between HTTP calls in the same test
    $this->app['auth']->forgetGuards();

    $this->withHeader('Authorization', 'Bearer '.$plainTextToken)
        ->getJson('/api/v1/admin/me')
        ->assertUnauthorized();
}
}