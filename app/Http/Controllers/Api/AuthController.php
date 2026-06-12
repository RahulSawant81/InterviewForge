<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\Auth\AuthenticationService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    use ApiResponseTrait;

    // Keep controller methods thin and delegate business logic to the service layer.
    protected AuthenticationService $authService;

    public function __construct(AuthenticationService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        // The request class has already validated the payload by this point.
        $user = $this->authService->register($request->validated());

        return $this->successResponse(new UserResource($user), 'User registered successfully', 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->authService->login($request->validated());

        if (! $result) {
            return $this->errorResponse('Invalid credentials', 401);
        }

        return $this->successResponse([
            'token' => $result['token'],
            'user' => new UserResource(
                $result['user']
            ),
        ], 'Login successful');
    }

    public function logout(): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $this->authService->logout($user);

        return $this->successResponse(
            [],
            'Logout successful'
        );
    }

    public function me(): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        // loadMissing avoids an extra query when the relation is already eager loaded.
        return $this->successResponse(
            new UserResource($user->loadMissing(['profile', 'role.permissions'])),
            'User profile retrieved successfully'
        );
    }

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $email = $request->validated('email');

        // Generate a secure token
        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'email' => $email,
                'token' => $token,
                'created_at' => now(),
            ]
        );

        // TODO: Send email with reset link containing the token
        // Example: Mail::send('emails.reset-password', ['token' => $token], function ($message) use ($email) { ... })

        return $this->successResponse(
            [],
            'Password reset link has been sent to your email'
        );
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $data = $request->validated();

        $result = $this->authService->resetPasswordWithToken(
            $data['email'],
            $data['token'],
            $data['password']
        );

        if (! $result) {
            return $this->errorResponse('Invalid or expired reset token', 400);
        }

        return $this->successResponse(
            [],
            'Password has been reset successfully'
        );
    }
}
