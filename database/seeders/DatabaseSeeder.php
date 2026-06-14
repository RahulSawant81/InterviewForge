<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // $this->call(CountryStateCityImportSeeder::class);

        $superAdmin = User::factory()->superAdmin()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
        ]);
        Profile::factory()->create([
            'user_id' => $superAdmin->id,
            'headline' => 'Platform Owner',
        ]);

        User::factory(2)
            ->admin()
            ->create()
            ->each(fn (User $user) => Profile::factory()->create([
                'user_id' => $user->id,
            ]));

        $testUser = User::factory()->userRole()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Profile::factory()->create([
            'user_id' => $testUser->id,
            'headline' => 'Laravel Developer',
        ]);

        User::factory(10)
            ->userRole()
            ->create()
            ->each(fn (User $user) => Profile::factory()->create([
                'user_id' => $user->id,
            ]));
    }
}
