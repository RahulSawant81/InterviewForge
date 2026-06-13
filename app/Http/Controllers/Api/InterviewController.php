<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\InterviewStoreRequest;
use App\Http\Resources\InterviewResource;
use App\Services\Interview\InterviewService;
use Illuminate\Http\JsonResponse;
use App\Traits\ApiResponseTrait;

class InterviewController extends Controller
{
    use ApiResponseTrait;

    protected InterviewService $interviewService;

    public function __construct(InterviewService $interviewService)
    {
        $this->interviewService = $interviewService;
    }

    /**
     * Display a listing of the user's interviews.
     */
    public function index(): JsonResponse
    {
        $interviews = $this->interviewService->list(auth()->user());

        return $this->successResponse(
            InterviewResource::collection($interviews),
            'Interviews retrieved successfully.'
        );
    }


    /**
     * Store a newly created interview in storage.
     */
    public function store(InterviewStoreRequest $request): JsonResponse
    {
        $interview = $this->interviewService->create(auth()->user(), $request->validated());

        return $this->successResponse(
            new InterviewResource($interview),
            'Interview created successfully.',
            201
        );
    }
}
