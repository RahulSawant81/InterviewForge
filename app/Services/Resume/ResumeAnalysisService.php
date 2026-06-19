<?php

namespace App\Services\Resume;

use App\Models\Resume;
use App\Models\ResumeAnalysis;

class ResumeAnalysisService
{
    public function __construct(
        private readonly ResumeEvaluationService $resumeEvaluationService
    ) {}

    public function analyzeAndSave(
        Resume $resume
    ): ResumeAnalysis {
        $analysis = $this->resumeEvaluationService
            ->evaluate($resume);

        return $this->saveAnalysis(
            $resume,
            $analysis
        );
    }

    /**
     * @param array<string, mixed> $analysis
     */
    public function saveAnalysis(
        Resume $resume,
        array $analysis
    ): ResumeAnalysis {
        return ResumeAnalysis::updateOrCreate(
            [
                'resume_id' => $resume->id,
            ],
            [
                'overall_score' => (int) (
                    $analysis['overall_score'] ?? 60
                ),
                'skills' => $analysis['skills'] ?? [],
                'strengths' => $analysis['strengths'] ?? [],
                'weaknesses' => $analysis['weaknesses'] ?? [],
                'recommendations' => $analysis['recommendations'] ?? [],
                'missing_skills' => $analysis['missing_skills'] ?? [],
            ]
        );
    }

    public function getAnalysis(
        Resume $resume
    ): ?ResumeAnalysis {
        return ResumeAnalysis::query()
            ->where('resume_id', $resume->id)
            ->first();
    }
}
