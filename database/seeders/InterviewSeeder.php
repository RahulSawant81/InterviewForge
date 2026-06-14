<?php

namespace Database\Seeders;

use App\Models\Interview;
use App\Models\User;
use Illuminate\Database\Seeder;

class InterviewSeeder extends Seeder
{
    public function run(): void
    {
        User::all()->each(function ($user) {
            Interview::factory()
                ->count(5)
                ->create([
                    'user_id' => $user->id,
                ]);
        });
    }
}
