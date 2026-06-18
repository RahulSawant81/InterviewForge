<?php

namespace App\Services\Interview;

use App\Models\Interview;
use App\Models\InterviewAnswer;
use App\Models\InterviewQuestion;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class InterviewAnswerService
{
    /**
     * Submit a single answer.
     */
    public function submitAnswer(InterviewQuestion $question, string $answer): InterviewAnswer
    {
        return InterviewAnswer::updateOrCreate(
            [
                'interview_question_id' => $question->id,
            ],
            [
                'answer' => $answer,
            ]
        );
    }

    /**
     * Submit multiple answers in one request.
     *
     * @param array<int, array{
     *     question_id:int,
     *     answer:string
     * }> $answers
     *
     * @return Collection<int, InterviewAnswer>
     */
    public function submitBulkAnswers(Interview $interview, array $answers): Collection
    {
        DB::transaction(function () use ($answers) {
            foreach ($answers as $item) {
                InterviewAnswer::updateOrCreate(
                    [
                        'interview_question_id' => $item['question_id'],
                    ],
                    [
                        'answer' => $item['answer'],
                    ]
                );
            }
        });

        return InterviewAnswer::query()
            ->whereHas('question', function ($query) use ($interview) {
                $query->where('interview_id', $interview->id);
            })
            ->get();
    }

    /**
     * @return Collection<int, InterviewAnswer>
     */
    public function getAnswers(Interview $interview): Collection
    {
        return InterviewAnswer::query()
            ->with('question')
            ->whereHas('question', function ($query) use ($interview) {
                $query->where('interview_id', $interview->id);
            })
            ->get();
    }
}
