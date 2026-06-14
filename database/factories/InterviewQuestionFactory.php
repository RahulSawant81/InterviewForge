<?php

namespace Database\Factories;

use App\Models\Interview;
use App\Models\InterviewQuestion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InterviewQuestion>
 */
class InterviewQuestionFactory extends Factory
{
    protected $model = InterviewQuestion::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'interview_id' => Interview::factory(),

            'question' => fake()->sentence(),

            'question_type' => 'text',

            'sequence' => fake()->numberBetween(
                1,
                20
            ),
        ];
    }
}
