<?php

namespace Tests\Feature\Profile;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_profile(): void
    {
        $user = User::factory()->create();

        Profile::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this
            ->actingAs($user, 'sanctum')
            ->getJson('/api/v1/profile');

        $response->assertStatus(200);
    }

    public function test_guest_cannot_view_profile(): void
    {
        $response = $this->getJson('/api/v1/profile');

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_update_profile(): void
    {
        $user = User::factory()->create();

        Profile::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this
            ->actingAs($user, 'sanctum')
            ->patchJson('/api/v1/profile', [
                'headline' => 'Senior Laravel Developer',
                'bio' => '8 years of experience in Laravel',
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('profiles', [
            'user_id' => $user->id,
            'headline' => 'Senior Laravel Developer',
        ]);
    }

    public function test_profile_update_validation_fails(): void
    {
        $user = User::factory()->create();

        Profile::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this
            ->actingAs($user, 'sanctum')
            ->patchJson('/api/v1/profile', [
                'headline' => str_repeat('A', 300),
            ]);

        $response->assertStatus(422);
    }

    public function test_authenticated_user_can_delete_profile(): void
    {
        $user = User::factory()->create();

        $profile = Profile::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this
            ->actingAs($user, 'sanctum')
            ->deleteJson('/api/v1/profile');

        $response->assertStatus(200);

        $this->assertSoftDeleted(
            'profiles',
            [
                'id' => $profile->id,
            ]
        );
    }
}
