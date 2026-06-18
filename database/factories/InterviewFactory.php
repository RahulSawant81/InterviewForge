<?php

namespace Database\Factories;

use App\Enums\DifficultyLevel;
use App\Enums\InterviewStatus;
use App\Enums\InterviewType;
use App\Models\Interview;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InterviewFactory extends Factory
{
    protected $model = Interview::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),

            'title' => fake()->sentence(3),

            'type' => InterviewType::MOCK,

            'technologies' => [
                'PHP',
                'Laravel',
            ],

            'difficulty' => DifficultyLevel::INTERMEDIATE,

            'status' => InterviewStatus::DRAFT,

            'total_questions' => fake()->numberBetween(
                5,
                20
            ),

            'started_at' => null,

            'completed_at' => null,
        ];
    }
}
