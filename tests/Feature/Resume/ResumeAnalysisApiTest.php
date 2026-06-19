<?php

namespace Tests\Feature\Resume;

use App\Models\Resume;
use App\Models\ResumeAnalysis;
use App\Models\User;
use App\Services\Resume\ResumeEvaluationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ResumeAnalysisApiTest extends TestCase
{
    use RefreshDatabase;

    private function mockResumeEvaluation(): void
    {
        $this->mock(
            ResumeEvaluationService::class,
            function ($mock) {
                $mock->shouldReceive('evaluate')
                    ->once()
                    ->andReturn([
                        'overall_score' => 82,
                        'skills' => [
                            'PHP',
                            'Laravel',
                        ],
                        'strengths' => [
                            'Strong backend experience',
                        ],
                        'weaknesses' => [
                            'Limited cloud depth',
                        ],
                        'recommendations' => [
                            'Add measurable outcomes',
                        ],
                        'missing_skills' => [
                            'Docker',
                            'AWS',
                        ],
                    ]);
            }
        );
    }

    public function test_authenticated_user_can_generate_resume_analysis(): void
    {
        $this->mockResumeEvaluation();

        /** @var User $user */
        $user = User::factory()->create();

        /** @var Resume $resume */
        $resume = Resume::factory()->create([
            'user_id' => $user->id,
        ]);

        Sanctum::actingAs($user);

        $response = $this->postJson(
            "/api/v1/resumes/{$resume->id}/analysis"
        );

        $response
            ->assertStatus(200)
            ->assertJsonPath(
                'message',
                'Resume analyzed successfully'
            )
            ->assertJsonPath(
                'data.resume_id',
                $resume->id
            )
            ->assertJsonPath(
                'data.overall_score',
                '82.00'
            )
            ->assertJsonPath(
                'data.missing_skills.0',
                'Docker'
            );

        $this->assertDatabaseHas(
            'resume_analyses',
            [
                'resume_id' => $resume->id,
            ]
        );
    }

    public function test_authenticated_user_can_view_resume_analysis(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        /** @var Resume $resume */
        $resume = Resume::factory()->create([
            'user_id' => $user->id,
        ]);

        ResumeAnalysis::factory()->create([
            'resume_id' => $resume->id,
            'missing_skills' => [
                'System Design',
            ],
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson(
            "/api/v1/resumes/{$resume->id}/analysis"
        );

        $response
            ->assertStatus(200)
            ->assertJsonPath(
                'message',
                'Resume analysis retrieved successfully'
            )
            ->assertJsonPath(
                'data.resume_id',
                $resume->id
            )
            ->assertJsonPath(
                'data.missing_skills.0',
                'System Design'
            );
    }

    public function test_guest_cannot_access_resume_analysis(): void
    {
        /** @var Resume $resume */
        $resume = Resume::factory()->create();

        $response = $this->getJson(
            "/api/v1/resumes/{$resume->id}/analysis"
        );

        $response->assertStatus(401);
    }

    public function test_user_cannot_access_another_users_resume_analysis(): void
    {
        /** @var User $owner */
        $owner = User::factory()->create();

        /** @var User $otherUser */
        $otherUser = User::factory()->create();

        /** @var Resume $resume */
        $resume = Resume::factory()->create([
            'user_id' => $owner->id,
        ]);

        Sanctum::actingAs($otherUser);

        $response = $this->postJson(
            "/api/v1/resumes/{$resume->id}/analysis"
        );

        $response->assertStatus(403);
    }
}
