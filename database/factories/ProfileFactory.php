<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Profile>
 */
class ProfileFactory extends Factory
{
    protected $model = Profile::class;

    public function definition(): array
    {
        $username = fake()->unique()->userName();
        // $city = City::query()->inRandomOrder()->with(['state', 'country'])->firstOrFail();

        $city = City::query()->inRandomOrder()->with(['state', 'country'])->first();


        return [
            'user_id' => User::factory(),

            'country_id' => $city?->state?->country_id,
            'state_id' => $city?->state_id,
            'city_id' => $city?->id,

            'headline' => fake()->jobTitle(),
            'experience_years' => fake()->randomFloat(1, 0, 12),
            'current_company' => fake()->company(),
            'current_ctc' => fake()->randomFloat(2, 3, 25),
            'expected_ctc' => fake()->randomFloat(2, 5, 30),
            'linkedin_url' => 'https://www.linkedin.com/in/'.$username,
            'github_url' => 'https://github.com/'.$username,
            'portfolio_url' => 'https://'.fake()->domainName(),
            'bio' => fake()->paragraph(),
        ];
    }
}
