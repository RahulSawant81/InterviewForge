<?php

namespace App\Services\Auth;

use App\Models\Profile;
use App\Models\User;
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
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            Profile::create([
                'user_id' => $user->id,
            ]);

            return $user->load('profile');
        });
    }

    /**
     * Handle user login.
     *
     * @return array{user: User, token: string}|null
     */
    public function login(array $data): ?array
    {
        $user = User::where('email', $data['email'])->first();

        if (! $user) {
            return null;
        }

        if (! Hash::check($data['password'], $user->password)) {
            return null;
        }

        $token = $user->createToken('InterviewForge')->plainTextToken;

        return [
            'user' => $user->load('profile'),
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
}
