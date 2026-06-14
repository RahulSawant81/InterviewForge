<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

use App\Enums\DifficultyLevel;
use App\Enums\InterviewType;
use Illuminate\Validation\Rules\Enum;


class InterviewStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Adjust this based on your authorization logic
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', new Enum(InterviewType::class)],
            'difficulty' => ['required', new Enum(DifficultyLevel::class)],
            'technologies.*' => ['string', 'max:100'],
            'total_questions' => ['required', 'integer', 'min:1', 'max:50'],
        ];
    }
}
