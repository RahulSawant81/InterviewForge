<?php

namespace Tests\Unit;

use App\Enums\DifficultyLevel;
use App\Models\Question;
use App\Models\QuestionCategory;
use App\Models\Interview;
use App\Services\Interview\InterviewQuestionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InterviewQuestionServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_generate_questions_for_interview(): void
    {
        $category = QuestionCategory::factory()->create([
            'slug' => 'php',
        ]);

        Question::factory()
            ->count(10)
            ->create([
                'category_id' => $category->id,
                'difficulty' => DifficultyLevel::INTERMEDIATE,
                'is_active' => true,
        ]);


        /** @var Interview $interview */
        $interview = Interview::factory()->create([
            'technologies' => ['PHP'],
            'difficulty' => DifficultyLevel::INTERMEDIATE,
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
        $category = QuestionCategory::factory()->create([
            'slug' => 'php',
        ]);

        Question::factory()
            ->count(5)
            ->create([
                'category_id' => $category->id,
                'difficulty' => DifficultyLevel::INTERMEDIATE,
                'is_active' => true,
        ]);

        /** @var Interview $interview */
        $interview = Interview::factory()->create([
            'technologies' => ['PHP'],
            'difficulty' => DifficultyLevel::INTERMEDIATE,
            'total_questions' => 3,
        ]);

        $service = app(
            InterviewQuestionService::class
        );

        $questions = $service->generateQuestions(
            $interview
        );

        $this->assertCount(3, $questions);

        $this->assertEquals(1, $questions[0]->sequence);
        $this->assertEquals(2, $questions[1]->sequence);
        $this->assertEquals(3, $questions[2]->sequence);
    }

    public function test_unknown_technology_generates_no_questions(): void
    {
        /** @var Interview $interview */
        $interview = Interview::factory()->create([
            'technologies' => ['Python'],
            'difficulty' => DifficultyLevel::INTERMEDIATE,
            'total_questions' => 1,
        ]);

        $service = app(
            InterviewQuestionService::class
        );

        $questions = $service->generateQuestions(
            $interview
        );

        $this->assertCount(
            0,
            $questions
        );
    }
}
