<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\InterviewAnswerRequest;
use App\Http\Requests\InterviewSingleAnswerRequest;
use App\Http\Requests\InterviewStoreRequest;
use App\Http\Resources\InterviewAnswerResource;
use App\Http\Resources\InterviewReportResource;
use App\Http\Resources\InterviewResource;
use App\Http\Resources\InterviewQuestionResource;
use App\Models\InterviewQuestion;
use App\Models\Interview;
use App\Services\Interview\InterviewAnswerService;
use App\Services\Interview\InterviewQuestionService;
use App\Services\Interview\InterviewReportService;
use App\Services\Interview\InterviewService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InterviewController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        private readonly InterviewService $interviewService,
        private readonly InterviewQuestionService $questionService,
        private readonly InterviewAnswerService $answerService,
        private readonly InterviewReportService $reportService
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
     * Complete the interview after all answers
     * have been submitted.
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
    // public function report(Interview $interview): JsonResponse
    // {
    //     abort_if(
    //         $interview->user_id !== auth()->id(),
    //         403,
    //         'Unauthorized'
    //     );

    //     $report = $interview->report;

    //     if (!$report) {
    //         return $this->errorResponse(
    //             'Interview report not found',
    //             404
    //         );
    //     }

    //     return $this->successResponse(
    //         $report,
    //         'Interview report retrieved successfully'
    //     );
    // }

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

    public function submitAnswers(Interview $interview, InterviewAnswerRequest $request): JsonResponse
    {
        abort_if(
            $interview->user_id !== auth()->id(),
            403,
            'Unauthorized'
        );

        $answers = $this->answerService->submitBulkAnswers($interview, $request->validated()['answers']);

        return $this->successResponse(
            InterviewAnswerResource::collection($answers),
            'Answers submitted successfully'
        );

    }

    public function getAnswers(Interview $interview): JsonResponse
    {
        abort_if(
            $interview->user_id !== auth()->id(),
            403,
            'Unauthorized'
        );

        $answers = $this->answerService->getAnswers($interview);

        return $this->successResponse(
            InterviewAnswerResource::collection($answers),
            'Answers retrieved successfully'
        );
    }

    public function report(Interview $interview): JsonResponse
    {
        abort_if(
            $interview->user_id !== auth()->id(),
            403,
            'Unauthorized'
        );

        $report = $this->reportService
            ->getReport($interview);

        if (! $report) {
            $report = $this->reportService
                ->generateReport($interview);
        }

        return $this->successResponse(
            new InterviewReportResource($report),
            'Interview report retrieved successfully'
        );
    }

    public function questions(Interview $interview): JsonResponse
    {
        abort_if(
            $interview->user_id !== auth()->id(),
            403,
            'Unauthorized'
        );

        $questions = $this->questionService
            ->getQuestions(
                $interview
            );

        return $this->successResponse(
            InterviewQuestionResource::collection(
                $questions
            ),
            'Interview questions fetched successfully.'
        );
    }

    public function answer(InterviewSingleAnswerRequest $request, InterviewQuestion $question): JsonResponse
    {
        $answer = $this->answerService
            ->submitAnswer(
                $question,
                $request->validated()['answer']
            );

        return $this->successResponse(
            $answer,
            'Answer submitted successfully.'
        );
    }
}
