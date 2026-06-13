<?php

namespace App\Services\Auth;

use App\Enums\UserStatus;
use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthenticationService
{
    /**
     * Handle user registration.
     */
    public function register(array $data): User
    {
        // Create the user and the linked profile in one transaction to avoid partial data.
        return DB::transaction(function () use ($data) {
            $defaultRoleId = Role::query()
                ->where('name', 'user')
                ->firstOrFail()
                ->id;

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role_id' => $defaultRoleId,
            ]);

            Profile::create([
                'user_id' => $user->id,
            ]);

            return $user->load([
                'profile',
                'role.permissions'
            ]);
        });
    }

    /**
     * Handle user login.
     *
     * @return array{user: User, token: string}|null
     */
    public function login(array $data): ?array
    {
        $user = User::query()
            ->with(['profile', 'role.permissions'])
            ->where('email', $data['email'])
            ->first();

        if (! $user) {
            return null;
        }

        if (! Hash::check($data['password'], $user->password)) {
            return null;
        }

        if ($user->status !== UserStatus::ACTIVE) {
            return null;
        }

        $token = $user->createToken('InterviewForge')->plainTextToken;

        return [
            'user' => $user->load(['profile', 'role.permissions']),
            'token' => $token,
        ];
    }

    /**
     * Handle user logout.
     */
    public function logout(User $user): void
    {
        // Revoke every active token for a full logout across devices.
        $user->tokens()->delete();
    }

    /**
     * Handle password reset.
     *
     * @return bool True if the password was successfully reset, false otherwise.
     */
    public function resetPassword(User $user, string $currentPassword, string $newPassword): bool
    {
        if (! Hash::check($currentPassword, $user->password)) {
            return false;
        }

        $user->update([
            'password' => Hash::make($newPassword),
        ]);

        return true;
    }

    /**
     * Handle password reset via email token.
     *
     * @return bool True if the password was successfully reset, false otherwise.
     */
    public function resetPasswordWithToken(string $email, string $token, string $newPassword): bool
    {
        $record = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->where('token', $token)
            ->first();

        if (! $record || ! $record->created_at) {
            return false;
        }

        $createdAt = Carbon::parse($record->created_at);

        // Check if the token is expired (valid for 60 minutes)
        if ($createdAt->lessThan(now()->subMinutes(60))) {
            return false;
        }

        $user = User::query()->where('email', $email)->first();

        if (! $user) {
            return false;
        }

        $user->update([
            'password' => Hash::make($newPassword),
        ]);

        // Delete the token after successful reset
        DB::table('password_reset_tokens')->where('email', $email)->delete();

        return true;
    }

}
