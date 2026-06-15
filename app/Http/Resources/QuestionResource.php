<?php

namespace App\Http\Resources;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Question
 */
class QuestionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'category' => [
                'id' => $this->category?->id,
                'name' => $this->category?->name,
            ],

            'title' => $this->title,

            'question' => $this->question,

            'difficulty' => $this->difficulty,

            'question_type' => $this->question_type,

            'expected_answer' => $this->expected_answer,

            'is_active' => $this->is_active,

            'tags' => $this->whenLoaded(
                'tags',
                fn () => $this->tags->pluck('name')
            ),

            'created_at' => $this->created_at,
        ];
    }
}
