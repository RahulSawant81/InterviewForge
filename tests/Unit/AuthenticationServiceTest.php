<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\Auth\AuthenticationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_with_token_updates_user_password(): void
    {
        $user = User::factory()->create([
            'email' => 'reset@example.com',
        ]);

        $token = 'valid-reset-token';

        DB::table('password_reset_tokens')->insert([
            'email' => $user->email,
            'token' => $token,
            'created_at' => now(),
        ]);

        $service = new AuthenticationService();
        $result = $service->resetPasswordWithToken($user->email, $token, 'new-password-123');

        $this->assertTrue($result);

        $user->refresh();
        $this->assertTrue(Hash::check('new-password-123', $user->password));
        $this->assertDatabaseMissing('password_reset_tokens', ['email' => $user->email]);
    }

    public function test_reset_password_with_expired_token_fails(): void
    {
        $user = User::factory()->create([
            'email' => 'expired@example.com',
        ]);

        $token = 'expired-reset-token';

        $createdAt = Carbon::now()->subHours(2)->toDateTimeString();

        DB::table('password_reset_tokens')->insert([
            'email' => $user->email,
            'token' => $token,
            'created_at' => $createdAt,
        ]);

        $service = new AuthenticationService();
        $result = $service->resetPasswordWithToken($user->email, $token, 'new-password-123');

        $this->assertFalse($result);
        $this->assertDatabaseHas('password_reset_tokens', ['email' => $user->email]);
    }

    public function test_reset_password_with_invalid_token_fails(): void
    {
        $user = User::factory()->create([
            'email' => 'invalid@example.com',
        ]);

        DB::table('password_reset_tokens')->insert([
            'email' => $user->email,
            'token' => 'some-token',
            'created_at' => now(),
        ]);

        $service = new AuthenticationService();
        $result = $service->resetPasswordWithToken($user->email, 'wrong-token', 'new-password-123');

        $this->assertFalse($result);
        $this->assertDatabaseHas('password_reset_tokens', ['email' => $user->email]);
    }
}
