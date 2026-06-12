<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_forgot_password_creates_reset_token(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
        ]);

        $response = $this->postJson('/api/v1/forgot-password', [
            'email' => 'user@example.com',
        ]);

        $response->assertOk()
            ->assertJsonPath('message', 'Password reset link has been sent to your email');

        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => 'user@example.com',
        ]);
    }

    public function test_forgot_password_with_invalid_email_fails(): void
    {
        $response = $this->postJson('/api/v1/forgot-password', [
            'email' => 'nonexistent@example.com',
        ]);

        $response->assertUnprocessable();
    }

    public function test_reset_password_with_valid_token_succeeds(): void
    {
        $user = User::factory()->create([
            'email' => 'reset@example.com',
        ]);

        $token = 'valid-reset-token-123';

        DB::table('password_reset_tokens')->insert([
            'email' => $user->email,
            'token' => $token,
            'created_at' => now(),
        ]);

        $response = $this->postJson('/api/v1/reset-password', [
            'email' => 'reset@example.com',
            'token' => $token,
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ]);

        $response->assertOk()
            ->assertJsonPath('message', 'Password has been reset successfully');

        $this->assertDatabaseMissing('password_reset_tokens', [
            'email' => 'reset@example.com',
        ]);
    }

    public function test_reset_password_with_invalid_token_fails(): void
    {
        $user = User::factory()->create([
            'email' => 'reset@example.com',
        ]);

        DB::table('password_reset_tokens')->insert([
            'email' => $user->email,
            'token' => 'valid-token',
            'created_at' => now(),
        ]);

        $response = $this->postJson('/api/v1/reset-password', [
            'email' => 'reset@example.com',
            'token' => 'invalid-token',
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ]);

        $response->assertStatus(400)
            ->assertJsonPath('message', 'Invalid or expired reset token');

        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => 'reset@example.com',
        ]);
    }

    public function test_reset_password_with_expired_token_fails(): void
    {
        $user = User::factory()->create([
            'email' => 'reset@example.com',
        ]);

        DB::table('password_reset_tokens')->insert([
            'email' => $user->email,
            'token' => 'expired-token',
            'created_at' => now()->subHours(2),
        ]);

        $response = $this->postJson('/api/v1/reset-password', [
            'email' => 'reset@example.com',
            'token' => 'expired-token',
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ]);

        $response->assertStatus(400)
            ->assertJsonPath('message', 'Invalid or expired reset token');

        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => 'reset@example.com',
        ]);
    }
}
