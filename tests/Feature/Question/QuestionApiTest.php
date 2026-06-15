<?php

namespace Tests\Feature\Question;

use App\Enums\DifficultyLevel;
use App\Models\Question;
use App\Models\QuestionCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class QuestionApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_list_questions(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        Question::factory()
            ->count(3)
            ->create();

        $response = $this->getJson(
            '/api/v1/questions'
        );

        $response->assertOk();
    }

    public function test_authenticated_user_can_create_question(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $category = QuestionCategory::factory()
            ->create();

        $response = $this->postJson(
            '/api/v1/questions',
            [
                'category_id' => $category->id,
                'title' => 'Service Container',
                'question' => 'Explain Laravel Service Container.',
                'difficulty' => DifficultyLevel::INTERMEDIATE->value,
                'question_type' => 'text',
                'is_active' => true,
            ]
        );

        $response->assertStatus(201);

        $this->assertDatabaseHas(
            'questions',
            [
                'title' => 'Service Container',
            ]
        );
    }

    public function test_authenticated_user_can_view_question(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $question = Question::factory()
            ->create();

        $response = $this->getJson(
            "/api/v1/questions/{$question->id}"
        );

        $response->assertOk();
    }

    public function test_authenticated_user_can_update_question(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $question = Question::factory()
            ->create();

        $response = $this->putJson(
            "/api/v1/questions/{$question->id}",
            [
                'category_id' => $question->category_id,
                'title' => 'Updated Question',
                'question' => 'Updated Content',
                'difficulty' => DifficultyLevel::INTERMEDIATE->value,
                'question_type' => 'text',
                'is_active' => true,
            ]
        );

        $response->assertOk();

        $this->assertDatabaseHas(
            'questions',
            [
                'id' => $question->id,
                'title' => 'Updated Question',
            ]
        );
    }

    public function test_authenticated_user_can_delete_question(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $question = Question::factory()
            ->create();

        $response = $this->deleteJson(
            "/api/v1/questions/{$question->id}"
        );

        $response->assertOk();

        $this->assertDatabaseMissing(
            'questions',
            [
                'id' => $question->id,
            ]
        );
    }

    public function test_validation_fails_when_required_fields_are_missing(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->postJson(
            '/api/v1/questions',
            []
        );

        $response->assertStatus(422);
    }

    public function test_guest_cannot_access_questions(): void
    {
        $response = $this->getJson(
            '/api/v1/questions'
        );

        $response->assertStatus(401);
    }
}
