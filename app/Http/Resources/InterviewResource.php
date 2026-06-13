<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InterviewResource extends JsonResource
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

            'title' => $this->title,

            'type' => [
                'value' => $this->type?->value,
                'name' => ucfirst($this->type?->value),
            ],

            'difficulty' => [
                'value' => $this->difficulty?->value,
                'name' => ucfirst($this->difficulty?->value),
            ],

            'technologies' => $this->technologies,

            'status' => [
                'value' => $this->status?->value,
                'name' => ucfirst($this->status?->value),
            ],

            'total_questions' => $this->total_questions,

            'started_at' => $this->started_at,

            'completed_at' => $this->completed_at,

            'created_at' => $this->created_at,
        ];
    }
}
