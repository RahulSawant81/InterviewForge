<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InterviewAnswerResource extends JsonResource
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
            'question_id' => $this->interview_question_id,
            'question' => $this->whenLoaded(
                'question',
                fn () => $this->question?->question
            ),
            'answer' => $this->answer,
            'score' => $this->score,
            'feedback' => $this->feedback,
            'created_at' => $this->created_at,
        ];
    }
}
