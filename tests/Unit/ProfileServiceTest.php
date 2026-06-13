<?php

namespace Tests\Unit;

use App\Models\Profile;
use App\Models\User;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Services\Profile\ProfileService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_profile_returns_existing_profile(): void
    {
        $user = User::factory()->create();

        $profile = Profile::factory()->create([
            'user_id' => $user->id,
        ]);

        $service = app(ProfileService::class);

        $result = $service->getProfile($user->id);

        $this->assertEquals(
            $profile->id,
            $result->id
        );
    }

    public function test_get_profile_creates_profile_if_missing(): void
    {
        $user = User::factory()->create();

        $service = app(ProfileService::class);

        $profile = $service->getProfile($user->id);

        $this->assertInstanceOf(
            Profile::class,
            $profile
        );

        $this->assertDatabaseHas('profiles', [
            'user_id' => $user->id,
        ]);
    }

    public function test_get_profile_restores_soft_deleted_profile(): void
    {
        $user = User::factory()->create();

        $profile = Profile::factory()->create([
            'user_id' => $user->id,
        ]);

        $profile->delete();

        $service = app(ProfileService::class);

        $result = $service->getProfile($user->id);

        $this->assertNull(
            $result->deleted_at
        );
    }

    public function test_update_profile_updates_fields(): void
    {
        $profile = Profile::factory()->create();

        $service = app(ProfileService::class);

        $updated = $service->updateProfile(
            $profile,
            [
                'headline' => 'Senior Laravel Developer',
                'bio' => 'Laravel Expert',
            ]
        );

        $this->assertEquals(
            'Senior Laravel Developer',
            $updated->headline
        );

        $this->assertEquals(
            'Laravel Expert',
            $updated->bio
        );
    }

    public function test_delete_profile_soft_deletes_profile(): void
    {
        $profile = Profile::factory()->create();

        $service = app(ProfileService::class);

        $service->deleteProfile($profile);

        $this->assertSoftDeleted(
            'profiles',
            [
                'id' => $profile->id,
            ]
        );
    }
}
