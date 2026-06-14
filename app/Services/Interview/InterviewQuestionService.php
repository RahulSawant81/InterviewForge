<?php

namespace App\Services\Interview;

use App\Models\Interview;
use App\Models\InterviewQuestion;
use Illuminate\Support\Collection;

class InterviewQuestionService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Generate and assign questions to an interview based on type, difficulty, and technologies.
     */
    public function generateQuestions(Interview $interview): Collection
    {
        // This is a placeholder implementation.
        // In a real scenario, this would:
        // 1. Query questions from a database based on type, difficulty, and technologies
        // 2. Randomly select the required number of questions
        // 3. Assign them sequences
        // 4. Create InterviewQuestion records
        // 5. Return the created questions
        // For now, return empty collection - to be implemented with actual question database

        $questions = [];

        foreach ($interview->technologies as $technology) {
            $technologQuestions = match (strtolower($technology)) {
                'php' => [
                    'What are traits in PHP?',
                    'Explain oop principles in PHP.',
                    'Difference between interface class and abstract class',
                ],
                'laravel' => [
                    'Explain the service container in Laravel.',
                    'What is Eloquent ORM',
                    'Difference between Sanctum and Passport',
                ],
                'mysql' => [
                    'What is indexing?',
                    'Difference between INNER JOIN and LEFT JOIN.',
                    'What is normalization?',
                ],
                default => [
                    "Explain your experience with {$technology}.",
                ],
            };

            $questions = array_merge($questions, $technologQuestions);
        }

        $questions = array_slice($questions, 0, $interview->total_questions);

        foreach ($questions as $index => $question) {
            InterviewQuestion::create([
                'interview_id' => $interview->id,
                'question' => $question,
                'question_type' => 'text', // This can be extended to support different question types
                'sequence' => $index + 1,
            ]);
        }

        return InterviewQuestion::query()
            ->where('interview_id', $interview->id)
            ->orderBy('sequence')
            ->get();
    }
}
