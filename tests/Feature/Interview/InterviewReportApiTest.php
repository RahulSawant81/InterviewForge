<?php

namespace Tests\Feature\Interview;

use App\Models\Interview;
use App\Models\InterviewReport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class InterviewReportApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_report(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        /** @var Interview $interview */
        $interview = Interview::factory()->create([
            'user_id' => $user->id,
        ]);

        InterviewReport::factory()->create([
            'interview_id' => $interview->id,
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson(
            "/api/v1/interviews/{$interview->id}/report"
        );

        $response->assertStatus(200);
    }

    public function test_report_is_generated_if_missing(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        /** @var Interview $interview */
        $interview = Interview::factory()->create([
            'user_id' => $user->id,
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson(
            "/api/v1/interviews/{$interview->id}/report"
        );

        $response
            ->assertStatus(200)
            ->assertJsonPath(
                'message',
                'Interview report retrieved successfully'
            );

        $this->assertDatabaseHas(
            'interview_reports',
            [
                'interview_id' => $interview->id,
            ]
        );
    }

    public function test_guest_cannot_access_report(): void
    {
        /** @var Interview $interview */
        $interview = Interview::factory()->create();

        $response = $this->getJson(
            "/api/v1/interviews/{$interview->id}/report"
        );

        $response->assertStatus(401);
    }

    public function test_user_cannot_access_another_users_report(): void
    {
        /** @var User $owner */
        $owner = User::factory()->create();

        /** @var User $otherUser */
        $otherUser = User::factory()->create();

        /** @var Interview $interview */
        $interview = Interview::factory()->create([
            'user_id' => $owner->id,
        ]);

        Sanctum::actingAs($otherUser);

        $response = $this->getJson(
            "/api/v1/interviews/{$interview->id}/report"
        );

        $response->assertStatus(403);
    }
}
