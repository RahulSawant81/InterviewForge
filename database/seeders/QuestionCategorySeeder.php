<?php

namespace Database\Seeders;

use App\Models\QuestionCategory;
use Illuminate\Database\Seeder;

class QuestionCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        QuestionCategory::insert([
            [
                'name' => 'PHP',
                'slug' => 'php',
            ],
            [
                'name' => 'Laravel',
                'slug' => 'laravel',
            ],
            [
                'name' => 'MySQL',
                'slug' => 'mysql',
            ],
            [
                'name' => 'Vue.js',
                'slug' => 'vuejs',
            ],
            [
                'name' => 'React',
                'slug' => 'react',
            ],
            [
                'name' => 'Docker',
                'slug' => 'docker',
            ],
            [
                'name' => 'AWS',
                'slug' => 'aws',
            ],
        ]);
    }
}
