<?php

namespace App\Services\Interview;

use App\Models\Interview;
use App\Models\InterviewQuestion;
use App\Services\AI\GeminiQuestionService;

class QuestionGenerationService
{
    public function __construct(
        private readonly GeminiQuestionService $geminiQuestionService
    ) {}

    /**
     * @param array<int, string> $skills
     *
     * @return array<string, mixed>
     */
    public function generate(
        string $interviewType,
        string $difficulty,
        string $questionSource,
        array $skills
    ): array {

        return $this->geminiQuestionService
            ->generateQuestions(
                $interviewType,
                $difficulty,
                $questionSource,
                $skills
            );
    }

    /**
     * Generate questions for an interview.
     *
     * @return array<string, mixed>
     */
    public function generateForInterview(
        Interview $interview
    ): array {

        if (empty($interview->technologies)) {
            return [
                'questions' => [],
            ];
        }

        return $this->generate(
            $interview->type->value,
            $interview->difficulty->value,
            'skill_selection',
            $interview->technologies
        );
    }

    /**
     * @param array<int, array<string, mixed>> $questions
     */
    public function saveQuestions(
        Interview $interview,
        array $questions
    ): void {

        $interview->questions()->delete();

        foreach ($questions as $index => $question) {

            InterviewQuestion::create([
                'interview_id' => $interview->id,

                'question' => $question['question'],

                'question_type' => $interview
                    ->type
                    ->value,

                'sequence' => $question['sequence']
                    ?? ($index + 1),
            ]);
        }
    }
}
