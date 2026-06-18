<?php

namespace Tests\Feature\Interview;

use App\Enums\DifficultyLevel;
use App\Enums\InterviewType;
use App\Models\Interview;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class InterviewApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_interview(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/interviews', [
            'title' => 'Laravel Interview',
            'type' => InterviewType::MOCK->value,
            'difficulty' => DifficultyLevel::INTERMEDIATE->value,
            'technologies' => ['PHP', 'Laravel'],
            'total_questions' => 10,
        ]);

        $response
            ->assertStatus(201)
            ->assertJsonPath(
                'message',
                'Interview created successfully.'
            );

        $this->assertDatabaseCount(
            'interviews',
            1
        );
    }

    public function test_authenticated_user_can_list_interviews(): void
    {
        $user = User::factory()->create();

        Interview::factory()
            ->count(2)
            ->create([
                'user_id' => $user->id,
            ]);

        Sanctum::actingAs($user);

        $response = $this->getJson(
            '/api/v1/interviews'
        );

        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_view_interview(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        /** @var Interview $interview */
        $interview = Interview::factory()->create([
            'user_id' => $user->id,
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson(
            "/api/v1/interviews/{$interview->id}"
        );

        $response
            ->assertStatus(200)
            ->assertJsonPath(
                'data.id',
                $interview->id
            );
    }

    public function test_authenticated_user_can_start_interview(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        /** @var Interview $interview */
        $interview = Interview::factory()->create([
            'user_id' => $user->id,
        ]);

        Sanctum::actingAs($user);

        $response = $this->postJson(
            "/api/v1/interviews/{$interview->id}/start"
        );

        $response
            ->assertStatus(200)
            ->assertJsonPath(
                'message',
                'Interview started successfully'
            );
    }

    public function test_authenticated_user_can_generate_questions(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        /** @var Interview $interview */
        $interview = Interview::factory()->create([
            'user_id' => $user->id,
            'technologies' => ['PHP', 'Laravel'],
            'total_questions' => 5,
        ]);

        Sanctum::actingAs($user);

        $response = $this->postJson(
            "/api/v1/interviews/{$interview->id}/generate-questions"
        );

        $response->assertStatus(200);
    }

    public function test_guest_cannot_access_interviews(): void
    {
        $response = $this->getJson(
            '/api/v1/interviews'
        );

        $response->assertStatus(401);
    }
}
