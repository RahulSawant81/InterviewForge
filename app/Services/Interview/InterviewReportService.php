<?php

namespace App\Services\Interview;

use App\Models\Interview;
use App\Models\InterviewReport;
use App\Services\AI\InterviewEvaluationService;


class InterviewReportService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        private readonly InterviewEvaluationService $evaluationService
    ) {}

    /**
     * Generate a report for an interview.
     */
    public function generateReport(Interview $interview): InterviewReport
    {
        $evaluation = $this->evaluationService->evaluate($interview);

        return InterviewReport::updateOrCreate(
            [
                'interview_id' => $interview->id,
            ],
            [
                'overall_score' => (int) (
                    $evaluation['overall_score']
                    ?? 60
                ),

                'strengths' => $evaluation['strengths'] ?? [],

                'weaknesses' => $evaluation['weaknesses'] ?? [],

                'recommendations' => $evaluation['recommendations'] ?? [],
            ]
        );
    }

    /**
     * Get report for interview.
     */
    public function getReport(Interview $interview): ?InterviewReport
    {
        return InterviewReport::query()
            ->where('interview_id', $interview->id)
            ->first();
    }

    /**
     * Regenerate report.
     */
    public function regenerateReport(Interview $interview): InterviewReport
    {
        InterviewReport::query()
            ->where('interview_id', $interview->id)
            ->delete();

        return $this->generateReport($interview);
    }
}
