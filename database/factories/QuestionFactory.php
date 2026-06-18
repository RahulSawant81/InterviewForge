<?php

namespace Database\Factories;

use App\Enums\DifficultyLevel;
use App\Models\Question;
use App\Models\QuestionCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Question>
 */
class QuestionFactory extends Factory
{
    protected $model = Question::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'category_id' => QuestionCategory::factory(),

            'title' => fake()->sentence(),

            'question' => fake()->paragraph(),

            'difficulty' => fake()->randomElement([
                DifficultyLevel::BEGINNER,
                DifficultyLevel::INTERMEDIATE,
                DifficultyLevel::ADVANCED,
            ]),

            'question_type' => 'text',

            'expected_answer' => fake()->paragraph(),

            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn () => [
            'is_active' => false,
        ]);
    }

    public function coding(): static
    {
        return $this->state(fn () => [
            'question_type' => 'coding',
        ]);
    }
}
