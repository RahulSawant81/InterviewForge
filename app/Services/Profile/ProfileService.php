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
