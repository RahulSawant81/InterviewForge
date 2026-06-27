<?php

namespace App\Services\Resume;

use App\Models\Resume;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\Response;

class ResumeReportService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function download (Resume $resume): Response
    {
        $analysis = $resume->analysis;

        abort_if(
            !$analysis,
            404,
            'Resume analysis not found.'
        );
        $pdf = Pdf::loadView(
            'pdf.resumeAnalysis',
            [
                'resume' => $resume,
                'analysis' => $analysis,
            ]
        );

        // $fileName = sprintf(
        //     'Resume_analysis_%s.pdf',
        //     $resume->id
        // );

        return $pdf->download(
            "Resume-Analysis-{$resume->id}.pdf"
        );
    }
}
