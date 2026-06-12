<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResumeUploadRequest;
use App\Http\Resources\ResumeResource;
use App\Models\Resume;
use App\Services\Resume\ResumeService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;


class ResumeController extends Controller
{
    use ApiResponseTrait;

    public function __construct( private readonly ResumeService $resumeService)
    {
        // Inject any necessary services here, e.g. ResumeService.
    }

    public function store(ResumeUploadRequest $request): JsonResponse
    {
        $resume = $this->resumeService->upload(
            auth()->id(),
            $request->file('resume'),
            $request->input('title')
        );

        return $this->successResponse(new ResumeResource($resume), 'Resume uploaded successfully', 201);
    }

    /**
     * List all resumes for the authenticated user.
     */
    public function index(): JsonResponse
    {
        $resumes = $this->resumeService->listResumes(auth()->id());

        return $this->successResponse(ResumeResource::collection($resumes), 'Resumes retrieved successfully');
    }

    /**
     * Get a specific resume by ID, ensuring it belongs to the authenticated user.
     */
    public function show(Resume $resume): JsonResponse
    {
        abort_if(
            $resume->user_id !== auth()->id(),
            403,
            'Unauthorized'
        );

        return $this->successResponse(
            new ResumeResource($resume),
            'Resume fetched successfully'
        );
    }

    /**
     * Delete Resume
     */
    public function destroy(Resume $resume): JsonResponse
    {
        abort_if(
            $resume->user_id !== auth()->id(),
            403,
            'Unauthorized'
        );

        $this->resumeService->delete($resume);

        return $this->successResponse(
            [],
            'Resume deleted successfully'
        );
    }

    public function download(Resume $resume): StreamedResponse
    {
        abort_if(
            $resume->user_id !== auth()->id(),
            403,
            'Unauthorized'
        );

        return $this->resumeService->download($resume);
    }
}
