<?php

namespace App\Services\Profile;

use App\Models\Profile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class ProfileService
{
    public function getProfile(int $userId): Profile
    {
        // Restore the soft-deleted profile instead of creating duplicate rows for one user.
        $profile = Profile::withTrashed()->firstOrCreate([
            'user_id' => $userId,
        ]);

        if ($profile->trashed()) {
            $profile->restore();
        }

        return $profile;
    }

    /**
     * @param array{
     *     profile_image?: UploadedFile|null,
     *     phone_code?: string|null,
     *     phone?: string|null,
     *     country_id?: int|null,
     *     state_id?: int|null,
     *     city_id?: int|null,
     *     headline?: string|null,
     *     experience_years?: float|int|null,
     *     current_company?: string|null,
     *     current_ctc?: float|int|null,
     *     expected_ctc?: float|int|null,
     *     linkedin_url?: string|null,
     *     github_url?: string|null,
     *     portfolio_url?: string|null,
     *     bio?: string|null
     * } $data
     */
    public function updateProfile(Profile $profile, array $data): Profile
    {

        $profile->fill(Arr::except($data, ['profile_image']));

        if (($data['profile_image'] ?? null) instanceof UploadedFile) {
            // Replace the previous file so unused images do not accumulate on disk.
            if ($profile->profile_image) {
                Storage::disk('public')->delete($profile->profile_image);
            }

            $profile->profile_image = $data['profile_image']->store('profiles', 'public');
        }

        $profile->save();

        return $profile;
    }

    public function deleteProfile(Profile $profile): void
    {
        // Soft delete keeps the row available for restore/audit purposes.
        $profile->delete();
    }
}
