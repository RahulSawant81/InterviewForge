<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuestionRequest;
use App\Http\Resources\QuestionResource;
use App\Models\Question;
use App\Services\Question\QuestionService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        private readonly QuestionService $questionService
    ) {}

    /**
     * Display a listing of questions.
     */
    public function index(Request $request): JsonResponse
    {
        $questions = QuestionResource::collection(
            $this->questionService->paginate(
                $request->all()
            )
        );

        return $this->successResponse(
            $questions,
            'Questions fetched successfully.'
        );
    }

    /**
     * Store a newly created question.
     */
    public function store(
        QuestionRequest $request
    ): JsonResponse {
        $question = $this->questionService->create(
            $request->validated()
        );

        return $this->successResponse(
            QuestionResource::make($question),
            'Question created successfully.',
            201
        );
    }

    /**
     * Display the specified question.
     */
    public function show(
        Question $question
    ): JsonResponse {
        return $this->successResponse(
            QuestionResource::make(
                $question->load([
                    'category',
                    'tags',
                ])
            ),
            'Question fetched successfully.'
        );
    }

    /**
     * Update the specified question.
     */
    public function update(
        QuestionRequest $request,
        Question $question
    ): JsonResponse {
        $question = $this->questionService->update(
            $question,
            $request->validated()
        );

        return $this->successResponse(
            QuestionResource::make($question),
            'Question updated successfully.'
        );
    }

    /**
     * Remove the specified question.
     */
    public function destroy(
        Question $question
    ): JsonResponse {
        $this->questionService->delete(
            $question
        );

        return $this->successResponse(
            [],
            'Question deleted successfully.'
        );
    }
}
