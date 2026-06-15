<?php

namespace Database\Seeders;

use App\Enums\DifficultyLevel;
use App\Models\Question;
use App\Models\QuestionCategory;
use Illuminate\Database\Seeder;

class MysqlQuestionSeeder extends Seeder
{
    public function run(): void
    {
        $category = QuestionCategory::where('slug', 'mysql')->firstOrFail();

        $questions = [
            ['What is Normalization?', DifficultyLevel::BEGINNER],
            ['Explain 1NF, 2NF and 3NF.', DifficultyLevel::INTERMEDIATE],
            ['What is Indexing?', DifficultyLevel::BEGINNER],
            ['Difference between Clustered and Non-Clustered Index?', DifficultyLevel::ADVANCED],
            ['What is a Composite Index?', DifficultyLevel::INTERMEDIATE],
            ['Difference between INNER JOIN and LEFT JOIN?', DifficultyLevel::BEGINNER],
            ['What are Transactions?', DifficultyLevel::BEGINNER],
            ['Explain ACID Properties.', DifficultyLevel::INTERMEDIATE],
            ['What is a Deadlock?', DifficultyLevel::ADVANCED],
            ['What is Query Optimization?', DifficultyLevel::ADVANCED],
            ['Difference between DELETE, TRUNCATE and DROP?', DifficultyLevel::BEGINNER],
            ['What are Stored Procedures?', DifficultyLevel::INTERMEDIATE],
            ['What are Views?', DifficultyLevel::BEGINNER],
            ['What are Triggers?', DifficultyLevel::INTERMEDIATE],
            ['What is Database Sharding?', DifficultyLevel::ADVANCED],
        ];

        foreach ($questions as $item) {
            Question::create([
                'category_id' => $category->id,
                'title' => $item[0],
                'question' => $item[0],
                'difficulty' => $item[1],
                'question_type' => 'text',
                'is_active' => true,
            ]);
        }
    }
}
