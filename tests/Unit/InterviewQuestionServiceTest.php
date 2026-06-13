<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Interview;
use App\Models\User;
use App\Services\Interview\InterviewQuestionService;

class InterviewQuestionServiceTest extends TestCase
{
    public function test_generate_questions_for_interview(): void
    {
        $interview = Interview::factory()->create([
            'technologies' => [
                'PHP',
                'Laravel',
            ],
            'total_questions' => 5,
        ]);

        $service = app(
            InterviewQuestionService::class
        );

        $questions = $service->generateQuestions(
            $interview
        );

        $this->assertCount(
            5,
            $questions
        );

        $this->assertDatabaseCount(
            'interview_questions',
            5
        );
    }

    public function test_questions_have_sequence_numbers(): void
    {
        $interview = Interview::factory()->create([
            'technologies' => ['PHP'],
            'total_questions' => 3,
        ]);

        $service = app(
            InterviewQuestionService::class
        );

        $questions = $service->generateQuestions(
            $interview
        );

        $this->assertEquals(
            1,
            $questions[0]->sequence
        );

        $this->assertEquals(
            2,
            $questions[1]->sequence
        );

        $this->assertEquals(
            3,
            $questions[2]->sequence
        );
    }

    public function test_unknown_technology_generates_fallback_question(): void
    {
        $interview = Interview::factory()->create([
            'technologies' => ['Python'],
            'total_questions' => 1,
        ]);

        $service = app(
            InterviewQuestionService::class
        );

        $questions = $service->generateQuestions(
            $interview
        );

        $this->assertStringContainsString(
            'Python',
            $questions->first()->question
        );
    }
}
