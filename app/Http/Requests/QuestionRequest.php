<?php

namespace App\Http\Requests;

use App\Enums\DifficultyLevel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'category_id' => [
                'required',
                'exists:question_categories,id',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'question' => [
                'required',
                'string',
            ],

            'difficulty' => [
                'required',
                Rule::enum(DifficultyLevel::class),
            ],

            'question_type' => [
                'required',
                Rule::in([
                    'text',
                    'mcq',
                    'coding',
                ]),
            ],

            'options' => [
                'nullable',
                'array',
            ],

            'correct_answer' => [
                'nullable',
                'string',
            ],

            'expected_answer' => [
                'nullable',
                'string',
            ],

            'is_active' => [
                'boolean',
            ],

            'tag_ids' => [
                'nullable',
                'array',
            ],

            'tag_ids.*' => [
                'exists:tags,id',
            ],
        ];
    }
}
