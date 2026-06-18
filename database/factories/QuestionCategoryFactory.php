<?php

namespace Database\Factories;

use App\Models\QuestionCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QuestionCategory>
 */
class QuestionCategoryFactory extends Factory
{
    protected $model = QuestionCategory::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->word();

        return [
            'name' => ucfirst($name),
            'slug' => str()->slug($name),
            'description' => fake()->sentence(),
        ];
    }
}
