<?php

namespace Database\Factories;

use App\Models\Interview;
use App\Models\InterviewReport;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InterviewReport>
 */
class InterviewReportFactory extends Factory
{
    protected $model = InterviewReport::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'interview_id' => Interview::factory(),

            'overall_score' => fake()->randomFloat(
                2,
                40,
                100
            ),

            'strengths' => [
                'Good communication',
                'Strong Laravel knowledge',
            ],

            'weaknesses' => [
                'Needs improvement in system design',
            ],

            'recommendations' => [
                'Practice more coding challenges',
                'Improve SQL optimization skills',
            ],
        ];
    }
}
