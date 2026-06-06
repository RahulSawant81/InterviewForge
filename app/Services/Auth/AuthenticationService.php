<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\Hash;

class AuthenticationService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle user registration.
     * @return User
     *
     */
    public function register(array $data): User
    {
        // Registration logic

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        Profile::create([
            'user_id' => $user->id,
        ]);

        return $user;
    }

    /**
     * Handle user login.
     * @return array|null
     */
    public function login(array $data): ?array
    {
        // Login logic

        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            return null;
        }

        if (!Hash::check($data['password'], $user->password)) {
            return null;
        }

        $token = $user->createToken('InterviewForge')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    /**
     * Handle user logout.
     */
    public function logout(User $user): void
    {
        // Logout logic

        $user->tokens()->delete();
    }
}
