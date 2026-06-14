<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InterviewAnswerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'answers' => [
                'required',
                'array',
                'min:1',
            ],

            'answers.*.question_id' => [
                'required',
                'integer',
                'exists:interview_questions,id',
            ],

            'answers.*.answer' => [
                'required',
                'string',
                'max:5000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'answers.required' => 'Answers are required.',
            'answers.array' => 'Answers must be an array.',
            'answers.min' => 'At least one answer is required.',

            'answers.*.question_id.required' => 'Question ID is required.',
            'answers.*.question_id.exists' => 'Selected question does not exist.',

            'answers.*.answer.required' => 'Answer is required.',
            'answers.*.answer.max' => 'Answer may not exceed 5000 characters.',
        ];
    }
}
