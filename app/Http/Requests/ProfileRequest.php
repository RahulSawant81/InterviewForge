<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'profile_image' => 'nullable|image|max:2048',
            'phone_code' => 'nullable|string|max:10',
            'phone' => 'nullable|string|max:20',
            'country_id' => 'nullable|exists:countries,id',
            'state_id' => [
                'nullable',
                'exists:states,id',
                Rule::exists('states', 'id')->where(function ($query) {
                    $countryId = $this->input('country_id');

                    if ($countryId) {
                        $query->where('country_id', $countryId);
                    }
                }),
            ],
            'city_id' => [
                'nullable',
                'exists:cities,id',
                Rule::exists('cities', 'id')->where(function ($query) {
                    $stateId = $this->input('state_id');

                    if ($stateId) {
                        $query->where('state_id', $stateId);
                    }
                }),
            ],
            'headline' => 'nullable|string|max:255',
            'experience_years' => 'nullable|numeric|min:0',
            'current_company' => 'nullable|string|max:255',
            'current_ctc' => 'nullable|numeric|min:0',
            'expected_ctc' => 'nullable|numeric|min:0',
            'linkedin_url' => 'nullable|url|max:255',
            'github_url' => 'nullable|url|max:255',
            'portfolio_url' => 'nullable|url|max:255',
            'bio' => 'nullable|string|max:2000',
        ];
    }
}
