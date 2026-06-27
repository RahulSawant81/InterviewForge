<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ResumeAnalysisResource;
use App\Models\Resume;
use App\Services\Resume\ResumeAnalysisService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class ResumeAnalysisController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        private readonly ResumeAnalysisService $resumeAnalysisService
    ) {}

    public function show(Resume $resume): JsonResponse
    {
        $this->authorizeResume($resume);

        $analysis = $this->resumeAnalysisService
            ->getAnalysis($resume);

        abort_if(
            ! $analysis,
            404,
            'Resume analysis not found'
        );

        return $this->successResponse(
            new ResumeAnalysisResource($analysis),
            'Resume analysis retrieved successfully'
        );
    }

    public function store(Resume $resume): JsonResponse
    {
        $this->authorizeResume($resume);

        $analysis = $this->resumeAnalysisService
            ->analyzeAndSave($resume);

        return $this->successResponse(
            new ResumeAnalysisResource($analysis),
            'Resume analyzed successfully'
        );
    }

    private function authorizeResume(Resume $resume): void
    {
        abort_if(
            $resume->user_id !== auth()->id(),
            403,
            'Unauthorized'
        );

    }
}
