<?php

namespace App\Services\AI;

use App\Models\Interview;

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

        $items = $interview->questions->map(function ($question) {
            return [
                'sequence' => $question->sequence,
                'question' => $question->question,
                'answer' => $question->answer?->answer,
            ];
        })
        ->values()
        ->toArray();

        return $this->geminiService
            ->evaluateInterview(
                $interview->type->value,
                $items
            );
    }
}
