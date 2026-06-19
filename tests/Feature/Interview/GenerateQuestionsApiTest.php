<?php

namespace Tests\Feature\Interview;

use App\Enums\DifficultyLevel;
use App\Enums\InterviewType;
use App\Models\Interview;
use App\Models\InterviewQuestion;
use App\Models\User;
use App\Services\AI\GeminiQuestionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GenerateQuestionsApiTest extends TestCase
{
    use RefreshDatabase;

    private function mockGeneratedQuestions(int $count = 5): void
    {
        $questions = [];

        for ($sequence = 1; $sequence <= $count; $sequence++) {
            $questions[] = [
                'sequence' => $sequence,
                'question' => "Generated question {$sequence}",
            ];
        }

        $this->mock(
            GeminiQuestionService::class,
            function ($mock) use ($questions) {
                $mock->shouldReceive('generateQuestions')
                    ->once()
                    ->andReturn([
                        'questions' => $questions,
                    ]);
            }
        );
    }

    public function test_generated_questions_are_saved(): void
    {
        $this->mockGeneratedQuestions();

        /** @var User $user */
        $user = User::factory()->create();

        /** @var Interview $interview */
        $interview = Interview::factory()->create([
            'user_id' => $user->id,
            'type' => InterviewType::MOCK->value,
            'difficulty' => DifficultyLevel::INTERMEDIATE->value,
            'technologies' => [
                'PHP',
                'Laravel',
                'MySQL',
            ],
            'total_questions' => 5,
        ]);

        Sanctum::actingAs($user);

        $response = $this->postJson(
            "/api/v1/interviews/{$interview->id}/generate-questions"
        );

        $response
            ->assertStatus(200)
            ->assertJsonPath(
                'message',
                'Questions generated successfully'
            )
            ->assertJsonPath(
                'data.count',
                5
            );

        $this->assertDatabaseCount(
            'interview_questions',
            5
        );

        $this->assertDatabaseHas(
            'interview_questions',
            [
                'interview_id' => $interview->id,
                'sequence' => 1,
                'question' => 'Generated question 1',
            ]
        );

        $this->assertSame(
            5,
            InterviewQuestion::query()
                ->where('interview_id', $interview->id)
                ->count()
        );
    }

    public function test_guest_cannot_generate_questions(): void
    {
        /** @var Interview $interview */
        $interview = Interview::factory()->create();

        $response = $this->postJson(
            "/api/v1/interviews/{$interview->id}/generate-questions"
        );

        $response->assertStatus(401);
    }
}
