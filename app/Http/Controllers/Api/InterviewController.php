<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\InterviewStoreRequest;
use App\Http\Resources\InterviewResource;
use App\Services\Interview\InterviewService;
use App\Services\Interview\InterviewQuestionService;
use Illuminate\Http\JsonResponse;
use App\Traits\ApiResponseTrait;
use App\Models\Interview;

class InterviewController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        private readonly InterviewService $interviewService,
        private readonly InterviewQuestionService $questionService
    ) {}

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

    /**
     * Display the specified interview.
     */
    public function show(Interview $interview): JsonResponse
    {
        abort_if(
            $interview->user_id !== auth()->id(),
            403,
            'Unauthorized'
        );

        return $this->successResponse(
            new InterviewResource($interview),
            'Interview fetched successfully'
        );
    }
    /**
     * Start the specified interview.
     */
    public function start(Interview $interview): JsonResponse
    {

        abort_if(
            $interview->user_id !== auth()->id(),
            403,
            'Unauthorized'
        );

        $interview = $this->interviewService
            ->start($interview);

        return $this->successResponse(
            new InterviewResource($interview),
            'Interview started successfully'
        );
    }

    /**
     * Submit the specified interview with answers.
     */
    public function submit(Interview $interview, Request $request): JsonResponse
    {
        abort_if(
            $interview->user_id !== auth()->id(),
            403,
            'Unauthorized'
        );

        $interview = $this->interviewService->submit($interview, $request->all());

        return $this->successResponse(
            new InterviewResource($interview),
            'Interview submitted successfully'
        );
    }

    /**
     * Get the report for the specified interview.
     */
    public function report(Interview $interview): JsonResponse
    {
        abort_if(
            $interview->user_id !== auth()->id(),
            403,
            'Unauthorized'
        );

        $report = $interview->report;

        if (!$report) {
            return $this->errorResponse(
                'Interview report not found',
                404
            );
        }

        return $this->successResponse(
            $report,
            'Interview report retrieved successfully'
        );
    }

    public function generateQuestions(Interview $interview): JsonResponse
    {
        abort_if(
            $interview->user_id !== auth()->id(),
            403,
            'Unauthorized'
        );

        $questions = $this->questionService->generateQuestions($interview);

        return $this->successResponse(
            $questions,
            'Questions generated successfully'
        );
    }
}
