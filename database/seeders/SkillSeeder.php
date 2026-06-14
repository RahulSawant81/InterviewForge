<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skills = [
            'PHP',
            'Laravel',
            'Vue.js',
            'React',
            'JavaScript',
            'TypeScript',
            'MySQL',
            'PostgreSQL',
            'Redis',
            'Docker',
            'AWS',
            'Azure',
            'Git',
            'Linux',
            'Python',
            'Node.js',
            'HTML',
            'CSS',
            'Bootstrap',
            'Tailwind CSS',
        ];

        foreach ($skills as $skill) {
            Skill::firstOrCreate([
                'name' => $skill,
            ]);
        }
    }
}
