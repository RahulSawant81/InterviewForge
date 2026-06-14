<?php

namespace Database\Seeders;

use App\Models\Resume;
use App\Models\User;
use Illuminate\Database\Seeder;

class ResumeSeeder extends Seeder
{
    public function run(): void
    {
        User::all()->each(function ($user) {
            Resume::factory()
                ->count(3)
                ->create([
                    'user_id' => $user->id,
                ]);
        });
    }
}