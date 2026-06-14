<?php

namespace App\Services\Interview;

use App\Models\Interview;
use App\Models\InterviewReport;

class InterviewReportService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Generate a report for an interview.
     */
    public function generateReport(Interview $interview): InterviewReport
    {
        return InterviewReport::updateOrCreate(
            [
                'interview_id' => $interview->id,
            ],
            [
                'overall_score' => 75,

                'strengths' => [
                    'Good communication',
                    'Strong technical knowledge',
                ],

                'weaknesses' => [
                    'Needs more system design practice',
                ],

                'recommendations' => [
                    'Practice coding challenges',
                    'Improve SQL optimization skills',
                ],
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
