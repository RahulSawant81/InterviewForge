<?php

namespace Tests\Feature\Interview;

use App\Models\User;
use App\Models\Interview;
use App\Models\InterviewQuestion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class InterviewQuestionApiTest extends TestCase
{

    use RefreshDatabase;

    public function test_authenticated_user_can_get_interview_questions(): void
    {
        $user = User::factory()->create();

        /** @var Interview $interview */
        $interview = Interview::factory()->create([
            'user_id' => $user->id,
        ]);

        /** @var InterviewQuestion $interview */
        InterviewQuestion::factory()
            ->count(3)
            ->create([
                'interview_id' => $interview->id,
            ]);

        Sanctum::actingAs($user);

        $response = $this->getJson(
            "/api/v1/interviews/{$interview->id}/questions"
        );

        $response->assertOk();
    }
}
