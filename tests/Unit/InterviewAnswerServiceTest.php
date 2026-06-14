<?php

namespace Tests\Unit;

use App\Models\Interview;
use App\Models\InterviewAnswer;
use App\Models\InterviewQuestion;
use App\Services\Interview\InterviewAnswerService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InterviewAnswerServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_submit_single_answer(): void
    {
        $question = InterviewQuestion::factory()->create();

        $service = app(
            InterviewAnswerService::class
        );

        $answer = $service->submitAnswer(
            $question,
            'Laravel Service Container manages dependencies.'
        );

        $this->assertInstanceOf(
            InterviewAnswer::class,
            $answer
        );

        $this->assertDatabaseHas(
            'interview_answers',
            [
                'interview_question_id' => $question->id,
            ]
        );
    }

    public function test_submit_bulk_answers(): void
    {
        $interview = Interview::factory()->create();

        $question1 = InterviewQuestion::factory()->create([
            'interview_id' => $interview->id,
        ]);

        $question2 = InterviewQuestion::factory()->create([
            'interview_id' => $interview->id,
        ]);

        $service = app(
            InterviewAnswerService::class
        );

        $answers = $service->submitBulkAnswers(
            $interview,
            [
                [
                    'question_id' => $question1->id,
                    'answer' => 'Answer One',
                ],
                [
                    'question_id' => $question2->id,
                    'answer' => 'Answer Two',
                ],
            ]
        );

        $this->assertCount(
            2,
            $answers
        );

        $this->assertDatabaseCount(
            'interview_answers',
            2
        );
    }

    public function test_get_answers_returns_interview_answers(): void
    {
        $interview = Interview::factory()->create();

        $question = InterviewQuestion::factory()->create([
            'interview_id' => $interview->id,
        ]);

        InterviewAnswer::factory()->create([
            'interview_question_id' => $question->id,
        ]);

        $service = app(
            InterviewAnswerService::class
        );

        $answers = $service->getAnswers(
            $interview
        );

        $this->assertCount(
            1,
            $answers
        );
    }

    public function test_submit_answer_updates_existing_answer(): void
    {
        $question = InterviewQuestion::factory()->create();

        $service = app(
            InterviewAnswerService::class
        );

        $service->submitAnswer(
            $question,
            'Old Answer'
        );

        $service->submitAnswer(
            $question,
            'Updated Answer'
        );

        $this->assertDatabaseCount(
            'interview_answers',
            1
        );

        $this->assertDatabaseHas(
            'interview_answers',
            [
                'interview_question_id' => $question->id,
                'answer' => 'Updated Answer',
            ]
        );
    }
}
