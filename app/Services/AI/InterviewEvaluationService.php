<?php

namespace App\Services\AI;

use App\Models\Interview;
use App\Enums\InterviewType;

class InterviewEvaluationService
{
    public function __construct(
        private readonly GeminiService $geminiService
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function evaluate(Interview $interview): array
    {
        $interview->loadMissing([
            'questions.answer',
        ]);

        $questions = $interview
            ->questions
            ->pluck('question')
            ->toArray();

        $answers = $interview
            ->questions
            ->map(
                fn ($question) => $question->answer?->answer ?? ''
            )
            ->filter()
            ->values()
            ->toArray();

        return $this->geminiService
            ->evaluateInterview(
                $interview->type->value,
                $questions,
                $answers
        );
    }
}
