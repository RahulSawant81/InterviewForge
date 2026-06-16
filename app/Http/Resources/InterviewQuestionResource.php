<?php

namespace App\Http\Resources;

use App\Models\InterviewQuestion;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin InterviewQuestion
 */
class InterviewQuestionResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'question' => $this->question,
            'question_type' => $this->question_type,
            'sequence' => $this->sequence,

            'answer' => $this->whenLoaded(
                'answer',
                fn () => [
                    'id' => $this->answer?->id,
                    'answer' => $this->answer?->answer,
                ]
            ),
        ];
    }
}
