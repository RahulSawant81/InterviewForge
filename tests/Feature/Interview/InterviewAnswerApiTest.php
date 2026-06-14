<?php

namespace Tests\Feature\Interview;

use App\Models\Interview;
use App\Models\InterviewAnswer;
use App\Models\InterviewQuestion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class InterviewAnswerApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_submit_answers(): void
    {
        $user = User::factory()->create();

        $interview = Interview::factory()->create([
            'user_id' => $user->id,
        ]);

        $question = InterviewQuestion::factory()->create([
            'interview_id' => $interview->id,
        ]);

        Sanctum::actingAs($user);

        $response = $this->postJson(
            "/api/v1/interviews/{$interview->id}/answers",
            [
                'answers' => [
                    [
                        'question_id' => $question->id,
                        'answer' => 'My answer',
                    ],
                ],
            ]
        );

        $response
            ->assertStatus(200)
            ->assertJsonPath(
                'message',
                'Answers submitted successfully'
            );

        $this->assertDatabaseHas(
            'interview_answers',
            [
                'interview_question_id' => $question->id,
            ]
        );
    }

    public function test_authenticated_user_can_view_answers(): void
    {
        $user = User::factory()->create();

        $interview = Interview::factory()->create([
            'user_id' => $user->id,
        ]);

        $question = InterviewQuestion::factory()->create([
            'interview_id' => $interview->id,
        ]);

        InterviewAnswer::factory()->create([
            'interview_question_id' => $question->id,
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson(
            "/api/v1/interviews/{$interview->id}/answers"
        );

        $response->assertStatus(200);
    }

    public function test_guest_cannot_access_answers(): void
    {
        $interview = Interview::factory()->create();

        $response = $this->getJson(
            "/api/v1/interviews/{$interview->id}/answers"
        );

        $response->assertStatus(401);
    }

    public function test_user_cannot_access_another_users_answers(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        $interview = Interview::factory()->create([
            'user_id' => $owner->id,
        ]);

        Sanctum::actingAs($otherUser);

        $response = $this->getJson(
            "/api/v1/interviews/{$interview->id}/answers"
        );

        $response->assertStatus(403);
    }

    public function test_validation_fails_for_invalid_answers_payload(): void
    {
        $user = User::factory()->create();

        $interview = Interview::factory()->create([
            'user_id' => $user->id,
        ]);

        Sanctum::actingAs($user);

        $response = $this->postJson(
            "/api/v1/interviews/{$interview->id}/answers",
            []
        );

        $response->assertStatus(422);
    }
}
