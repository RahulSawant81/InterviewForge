<?php

namespace Database\Factories;

use App\Models\InterviewAnswer;
use App\Models\InterviewQuestion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InterviewAnswer>
 */
class InterviewAnswerFactory extends Factory
{

    protected $model = InterviewAnswer::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'interview_question_id' => InterviewQuestion::factory(),

            'answer' => fake()->paragraph(),

            'score' => fake()->numberBetween(
                0,
                10
            ),

            'feedback' => fake()->sentence(),
        ];
    }
}
