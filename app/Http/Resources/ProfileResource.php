<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'profile_image' => $this->profile_image,
            'profile_image_url' => $this->profile_image ? Storage::url($this->profile_image) : null,
            'phone' => $this->phone,
            'country_id' => $this->country_id,
            'country' => $this->country?->name,
            'state_id' => $this->state_id,
            'state' => $this->state?->name,
            'city_id' => $this->city_id,
            'city' => $this->city?->name,
            'headline' => $this->headline,
            'experience_years' => $this->experience_years,
            'current_company' => $this->current_company,
            'current_ctc' => $this->current_ctc,
            'expected_ctc' => $this->expected_ctc,
            'linkedin_url' => $this->linkedin_url,
            'github_url' => $this->github_url,
            'portfolio_url' => $this->portfolio_url,
            'bio' => $this->bio,
        ];
    }
}
