<?php

namespace Database\Seeders;

use App\Enums\DifficultyLevel;
use App\Models\Question;
use App\Models\QuestionCategory;
use Illuminate\Database\Seeder;

class LaravelQuestionSeeder extends Seeder
{
    public function run(): void
    {
        $category = QuestionCategory::where('slug', 'laravel')->firstOrFail();

        $questions = [
            ['What is Laravel Service Container?', DifficultyLevel::INTERMEDIATE],
            ['What is a Service Provider?', DifficultyLevel::INTERMEDIATE],
            ['Explain Dependency Injection in Laravel.', DifficultyLevel::INTERMEDIATE],
            ['What is Eloquent ORM?', DifficultyLevel::BEGINNER],
            ['Difference between hasOne and belongsTo?', DifficultyLevel::BEGINNER],
            ['Difference between hasMany and belongsToMany?', DifficultyLevel::BEGINNER],
            ['What are Accessors and Mutators?', DifficultyLevel::INTERMEDIATE],
            ['What are Laravel Casts?', DifficultyLevel::INTERMEDIATE],
            ['Explain Route Model Binding.', DifficultyLevel::INTERMEDIATE],
            ['What are Middleware?', DifficultyLevel::BEGINNER],
            ['What are Form Requests?', DifficultyLevel::BEGINNER],
            ['What are Policies?', DifficultyLevel::INTERMEDIATE],
            ['What are Gates?', DifficultyLevel::INTERMEDIATE],
            ['Difference between Policies and Gates?', DifficultyLevel::INTERMEDIATE],
            ['Difference between Sanctum and Passport?', DifficultyLevel::ADVANCED],
            ['What are Laravel Queues?', DifficultyLevel::INTERMEDIATE],
            ['What are Jobs?', DifficultyLevel::INTERMEDIATE],
            ['Explain Laravel Events and Listeners.', DifficultyLevel::INTERMEDIATE],
            ['What is Laravel Broadcasting?', DifficultyLevel::ADVANCED],
            ['What are Notifications?', DifficultyLevel::BEGINNER],
            ['What is Laravel Scheduler?', DifficultyLevel::INTERMEDIATE],
            ['Difference between Lazy Loading and Eager Loading?', DifficultyLevel::INTERMEDIATE],
            ['What are Global Scopes?', DifficultyLevel::ADVANCED],
            ['What are Local Scopes?', DifficultyLevel::INTERMEDIATE],
            ['Explain Database Transactions in Laravel.', DifficultyLevel::INTERMEDIATE],
            ['What are Observers?', DifficultyLevel::INTERMEDIATE],
            ['What is the Repository Pattern?', DifficultyLevel::ADVANCED],
            ['What are API Resources?', DifficultyLevel::BEGINNER],
            ['What is Laravel Octane?', DifficultyLevel::ADVANCED],
            ['Difference between first() and firstOrFail()?', DifficultyLevel::BEGINNER],
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
