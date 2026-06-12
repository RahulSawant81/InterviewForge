<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_and_profile_is_created(): void
    {
        $response = $this->postJson('/api/v1/register', [
            'name' => 'Rahul',
            'email' => 'rahul@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.email', 'rahul@example.com')
            ->assertJsonPath('data.role', 'user');

        $this->assertDatabaseHas('users', [
            'email' => 'rahul@example.com',
        ]);

        $this->assertDatabaseHas('roles', [
            'name' => 'user',
        ]);

        $this->assertDatabaseHas('profiles', [
            'user_id' => 1,
        ]);
    }

    public function test_login_returns_user_with_profile_and_token(): void
    {
        $user = User::factory()->create([
            'email' => 'rahul@example.com',
            'password' => 'password123',
        ]);

        $user->profile()->create([
            'headline' => 'Laravel Developer',
        ]);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'rahul@example.com',
            'password' => 'password123',
        ]);

        $response->assertOk()
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'token',
                    'user' => [
                        'id',
                        'name',
                        'email',
                        'role',
                        'permissions',
                        'profile',
                    ],
                ],
            ])
            ->assertJsonPath('data.user.role', 'user')
            ->assertJsonPath('data.user.profile.headline', 'Laravel Developer');
    }

    public function test_authenticated_user_profile_endpoint_includes_profile_data(): void
    {
        $user = User::factory()->create();

        $user->profile()->create([
            'headline' => 'API Engineer',
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/me');

        $response->assertOk()
            ->assertJsonPath('data.email', $user->email)
            ->assertJsonPath('data.role', 'user')
            ->assertJsonPath('data.profile.headline', 'API Engineer');
    }
}
