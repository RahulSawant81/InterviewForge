<?php

namespace Database\Factories;

use App\Models\Resume;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResumeFactory extends Factory
{
    protected $model = Resume::class;

    public function definition(): array
    {
        $filename = fake()->uuid().'.pdf';

        return [
            'user_id' => User::factory(),

            'title' => fake()->sentence(3),

            'original_filename' => fake()->word().'.pdf',

            'stored_filename' => $filename,

            'file_path' => 'resumes/'.$filename,

            'mime_type' => 'application/pdf',

            'file_size' => fake()->numberBetween(
                10000,
                500000
            ),
        ];
    }
}
