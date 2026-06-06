<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\Auth\AuthenticationService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiResponseTrait;

    protected $authService;

    public function __construct(AuthenticationService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->authService->register($request->validated());
        return $this->successResponse(new UserResource($user), 'User registered successfully', 201);
    }

    public function login(LoginRequest $request)
    {
        $result = $this->authService->login($request->validated());

        if (!$result) {
            return $this->errorResponse('Invalid credentials', 401);
        }

        return $this->successResponse([
            'token' => $result['token'],
            'user' => new UserResource(
                $result['user']
            )
        ], 'Login successful');
    }

    public function logout()
    {
        $this->authService->logout(auth()->user());

        return $this->successResponse(
            [],
            'Logout successful'
        );
    }

    public function profile()
    {
        return $this->successResponse(
            new UserResource(auth()->user()),
            'User profile retrieved successfully'
        );
    }
}
