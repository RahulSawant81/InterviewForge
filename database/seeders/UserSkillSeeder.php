<?php

namespace Database\Seeders;

use App\Models\Skill;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSkillSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'user@example.com')->first();

        if ($admin) {
            $admin->skills()->sync(
                Skill::whereIn('name', [
                    'PHP',
                    'Laravel',
                    'MySQL',
                    'Docker',
                    'AWS',
                ])->pluck('id')
            );
        }

        if ($user) {
            $user->skills()->sync(
                Skill::whereIn('name', [
                    'PHP',
                    'Laravel',
                    'Vue.js',
                    'JavaScript',
                    'Git',
                ])->pluck('id')
            );
        }
    }
}
