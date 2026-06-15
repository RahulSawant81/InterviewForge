<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PhpQuestionSeeder::class,
            LaravelQuestionSeeder::class,
            MysqlQuestionSeeder::class,
        ]);
    }
}
