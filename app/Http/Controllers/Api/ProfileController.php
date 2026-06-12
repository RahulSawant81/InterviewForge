<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Http\Resources\ProfileResource;
use App\Services\Profile\ProfileService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    use ApiResponseTrait;

    // Profile-specific read/write logic lives in the service.
    protected ProfileService $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    public function show(): JsonResponse
    {
        // Every authenticated user should resolve to a single profile record.
        $profile = $this->profileService->getProfile((int) auth()->id());

        return $this->successResponse(
            new ProfileResource($profile)
        );
    }

    public function update(ProfileRequest $request): JsonResponse
    {
        $profile = $this->profileService->getProfile((int) auth()->id());

        $updatedProfile = $this->profileService->updateProfile(
            $profile, $request->validated()
        );

        return $this->successResponse(
            new ProfileResource($updatedProfile),
            'Profile updated successfully'
        );
    }

    public function destroy(): JsonResponse
    {
        // This performs a soft delete so profile data can be recovered later if needed.
        $profile = $this->profileService->getProfile((int) auth()->id());

        $this->profileService->deleteProfile($profile);

        return $this->successResponse(
            [],
            'Profile deleted successfully'
        );
    }
}
