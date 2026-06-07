<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\Auth\AuthenticationService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

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
            new UserResource($user->loadMissing('profile')),
            'User profile retrieved successfully'
        );
    }
}
