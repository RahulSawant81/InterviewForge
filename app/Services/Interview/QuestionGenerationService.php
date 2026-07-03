<?php
/**
 * app\Services\Interview\QuestionGenerationService.php
 */
namespace App\Services\Interview;

use App\Models\Interview;
use App\Models\InterviewQuestion;
use App\Services\AI\GeminiQuestionService;
use App\Enums\InterviewType;

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
    // public function generateForInterview(Interview $interview): array
    // {

    //     if ($interview->type->value === 'hr') {
    //         return $this->generate(
    //             $interview->type->value,
    //             $interview->difficulty->value,
    //             'behavioral',
    //             []
    //         );
    //     }

    //     if (empty($interview->technologies)) {
    //         return [
    //             'questions' => [],
    //         ];
    //     }

    //     return $this->generate(
    //         $interview->type->value,
    //         $interview->difficulty->value,
    //         'skill_selection',
    //         $interview->technologies
    //     );
    // }

    public function generateForInterview(Interview $interview): array
    {
        return match ($interview->type) {

            InterviewType::TECHNICAL => $this->generateTechnicalQuestions($interview),

            InterviewType::HR => $this->generateHrQuestions($interview),

            InterviewType::MIXED => $this->generateMixedQuestions($interview),

        };
    }

    /**
     * @param array<int, array<string, mixed>> $questions
     */
    public function saveQuestions(Interview $interview, array $questions): void
    {

        $interview->questions()->delete();

        foreach ($questions as $index => $question) {

            $saved = InterviewQuestion::create([
                'interview_id' => $interview->id,
                'question' => $question['question'],
                'question_type' => $interview->type->value,
                'sequence' => $question['sequence'] ?? ($index + 1),
            ]);

            logger()->info('Saved Question', [
                'id' => $saved->id,
                'question' => $saved->question,
            ]);
        }


        // foreach ($questions as $index => $question) {
        //     try {
        //         logger()->info(json_encode($question));
        //         InterviewQuestion::create([
        //             'interview_id' => $interview->id,

        //             'question' => $question['question'],

        //             'question_type' => $interview
        //                 ->type
        //                 ->value,

        //             'sequence' => $question['sequence']
        //                 ?? ($index + 1),
        //         ]);
        //     } catch (\Thoeable $e) {
        //         logger()->error($e->getMessage());

        //         throw $e;
        //     }
        // }
    }

    private function generateTechnicalQuestions(Interview $interview): array
    {
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

    private function generateHrQuestions(Interview $interview): array
    {

        return $this->generate(
            $interview->type->value,
            $interview->difficulty->value,
            'behavioral',
            []
        );
    }

    private function generateMixedQuestions(Interview $interview): array
    {
        return $this->generate(
            $interview->type->value,
            $interview->difficulty->value,
            'mixed',
            $interview->technologies ?? []
        );
    }
}
