<?php

namespace Database\Factories;

use App\Models\Resume;
use App\Models\ResumeAnalysis;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ResumeAnalysis>
 */
class ResumeAnalysisFactory extends Factory
{
    protected $model = ResumeAnalysis::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'resume_id' => Resume::factory(),

            'overall_score' => fake()->randomFloat(
                2,
                40,
                100
            ),

            'skills' => [
                'PHP',
                'Laravel',
                'MySQL',
            ],

            'strengths' => [
                'Strong backend fundamentals',
                'Clear project experience',
            ],

            'weaknesses' => [
                'Needs deeper cloud experience',
            ],

            'recommendations' => [
                'Add measurable achievements to projects',
                'Highlight architecture decisions more clearly',
            ],

            'missing_skills' => [
                'Docker',
                'AWS',
                'System Design',
            ],
        ];
    }
}
