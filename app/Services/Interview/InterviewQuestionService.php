<?php

namespace App\Services\Interview;

use App\Models\Interview;
use App\Models\InterviewQuestion;
use Illuminate\Support\Collection;
use App\Services\Question\QuestionService;

class InterviewQuestionService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        private readonly QuestionService $questionService
    ) {}

    /**
     * Generate and assign questions to an interview.
     *
     * @return Collection<int, InterviewQuestion>
     */
    public function generateQuestions(Interview $interview): Collection
    {
        /** @var Collection<int, \App\Models\Question> $questions */
        $questions = collect();

        foreach ($interview->technologies as $technology) {

            $technologyQuestions = $this->questionService
                ->getRandomQuestions(
                    $technology,
                    $interview->difficulty->value,
                    $interview->total_questions
                );

            $questions = $questions->merge(
                $technologyQuestions
            );
        }

        $questions = $questions
            ->shuffle()
            ->take($interview->total_questions)
            ->values();

        foreach ($questions as $index => $question) {
            InterviewQuestion::create([
                'interview_id' => $interview->id,
                'question' => $question->question,
                'question_type' => $question->question_type,
                'sequence' => $index + 1,
            ]);
        }

        return InterviewQuestion::query()
            ->where('interview_id', $interview->id)
            ->orderBy('sequence')
            ->get();
    }


    /**
     * @return Collection<int, InterviewQuestion>
     */
    public function getQuestions(
        Interview $interview
    ): Collection {
        return InterviewQuestion::query()
            ->with('answer')
            ->where(
                'interview_id',
                $interview->id
            )
            ->orderBy('sequence')
            ->get();
    }
}
