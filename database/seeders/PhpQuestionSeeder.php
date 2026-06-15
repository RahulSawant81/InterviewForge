<?php

namespace Database\Seeders;

use App\Enums\DifficultyLevel;
use App\Models\Question;
use App\Models\QuestionCategory;
use Illuminate\Database\Seeder;

class PhpQuestionSeeder extends Seeder
{
    public function run(): void
    {
        $category = QuestionCategory::where('slug', 'php')->firstOrFail();

        $questions = [
            ['What are Traits in PHP?', DifficultyLevel::INTERMEDIATE],
            ['Difference between Interface and Abstract Class?', DifficultyLevel::BEGINNER],
            ['What is Dependency Injection?', DifficultyLevel::INTERMEDIATE],
            ['What is Composer?', DifficultyLevel::BEGINNER],
            ['Explain Namespaces in PHP.', DifficultyLevel::BEGINNER],
            ['What is PSR and why is it important?', DifficultyLevel::INTERMEDIATE],
            ['Difference between include, require, include_once and require_once.', DifficultyLevel::BEGINNER],
            ['What are Magic Methods in PHP?', DifficultyLevel::INTERMEDIATE],
            ['What is Late Static Binding?', DifficultyLevel::ADVANCED],
            ['Difference between == and ===.', DifficultyLevel::BEGINNER],
            ['What is Autoloading?', DifficultyLevel::INTERMEDIATE],
            ['What are Anonymous Functions?', DifficultyLevel::BEGINNER],
            ['What are Closures?', DifficultyLevel::INTERMEDIATE],
            ['What are Generators in PHP?', DifficultyLevel::ADVANCED],
            ['Explain Exception Handling in PHP.', DifficultyLevel::BEGINNER],
            ['What are Sessions and Cookies?', DifficultyLevel::BEGINNER],
            ['How does Garbage Collection work in PHP?', DifficultyLevel::ADVANCED],
            ['What are Attributes in PHP 8?', DifficultyLevel::INTERMEDIATE],
            ['What is Covariance and Contravariance?', DifficultyLevel::ADVANCED],
            ['What are Anonymous Classes?', DifficultyLevel::INTERMEDIATE],
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
