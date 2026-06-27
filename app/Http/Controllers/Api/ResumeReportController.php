<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Resume;
use App\Services\Resume\ResumeReportService;
use Symfony\Component\HttpFoundation\Response;

class ResumeReportController extends Controller
{
    public function __construct(
        private readonly ResumeReportService $resumeReportService
    ) {}

    public function __invoke(
        Resume $resume
    ): Response {

        abort_if(
            $resume->user_id !== auth()->id(),
            403,
            'Unauthorized'
        );

        return $this->resumeReportService
            ->download($resume);
    }
}
