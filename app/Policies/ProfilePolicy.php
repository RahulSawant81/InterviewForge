<?php

namespace App\Policies;

use App\Models\Profile;
use App\Models\User;

class ProfilePolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return null;
    }

    public function view(User $user, Profile $profile): bool
    {
        return $user->id === $profile->user_id || $user->isAdmin();
    }

    public function update(User $user, Profile $profile): bool
    {
        return $user->id === $profile->user_id || $user->isAdmin();
    }

    public function delete(User $user, Profile $profile): bool
    {
        return $user->id === $profile->user_id || $user->isAdmin();
    }
}
