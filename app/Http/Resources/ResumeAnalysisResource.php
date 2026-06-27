<?php

namespace App\Http\Resources;

use App\Models\ResumeAnalysis;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ResumeAnalysis
 */
class ResumeAnalysisResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'resume_id' => $this->resume_id,
            'summary' => $this->summary,
            'overall_score' => $this->overall_score,
            'skills' => $this->skills,
            'strengths' => $this->strengths,
            'weaknesses' => $this->weaknesses,
            'recommendations' => $this->recommendations,
            'missing_skills' => $this->missing_skills,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
