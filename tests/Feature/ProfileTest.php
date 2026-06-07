<?php

namespace Tests\Feature;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_profile(): void
    {
        $user = User::factory()->create();
        $profile = Profile::create([
            'user_id' => $user->id,
            'headline' => 'Backend Developer',
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/profile');

        $response->assertOk()
            ->assertJsonPath('data.id', $profile->id)
            ->assertJsonPath('data.headline', 'Backend Developer');
    }

    public function test_authenticated_user_can_update_profile_with_image(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        Profile::create([
            'user_id' => $user->id,
        ]);

        Sanctum::actingAs($user);

        $response = $this->patch('/api/v1/profile', [
            'profile_image' => UploadedFile::fake()->image('avatar.jpg'),
            'headline' => 'Senior PHP Developer',
            'experience_years' => 4.5,
            'current_company' => 'InterviewForge',
            'current_ctc' => 12.50,
            'expected_ctc' => 18.00,
            'linkedin_url' => 'https://www.linkedin.com/in/test-user',
            'github_url' => 'https://github.com/test-user',
            'portfolio_url' => 'https://test-user.dev',
            'bio' => 'Building APIs and interview tooling.',
        ]);

        $response->assertOk()
            ->assertJsonPath('message', 'Profile updated successfully')
            ->assertJsonPath('data.headline', 'Senior PHP Developer');

        $profile = $user->fresh()->profile;

        $this->assertNotNull($profile);
        $this->assertNotNull($profile->profile_image);
        Storage::disk('public')->assertExists($profile->profile_image);
    }

    public function test_authenticated_user_can_soft_delete_profile(): void
    {
        $user = User::factory()->create();
        $profile = Profile::create([
            'user_id' => $user->id,
            'headline' => 'To be deleted',
        ]);

        Sanctum::actingAs($user);

        $response = $this->deleteJson('/api/v1/profile');

        $response->assertOk()
            ->assertJsonPath('message', 'Profile deleted successfully');

        $this->assertSoftDeleted('profiles', [
            'id' => $profile->id,
        ]);
    }
}
