<?php

namespace Tests\Unit;

use App\Models\Interview;
use App\Models\InterviewReport;
use App\Services\Interview\InterviewReportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InterviewReportServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test generating an interview report.
     */
    public function test_generate_report(): void
    {
        /** @var Interview $interview */
        $interview = Interview::factory()->create();

        $service = app(InterviewReportService::class);

        $report = $service->generateReport($interview);

        $this->assertInstanceOf(
            InterviewReport::class,
            $report
        );

        $this->assertDatabaseHas(
            'interview_reports',
            [
                'interview_id' => $interview->id,
            ]
        );
    }

    /**
     * Test retrieving an existing interview report.
     */
    public function test_get_report(): void
    {
        /** @var InterviewReport $report */
        $report = InterviewReport::factory()->create();

        $service = app(
            InterviewReportService::class
        );

        /** @var Interview $interview */
        $interview = $report->interview;

        $result = $service->getReport(
            $interview
        );

        $this->assertEquals(
            $report->id,
            $result->id
        );
    }

    /**
     * Test regenerating an interview report.
     */
    public function test_regenerate_report(): void
    {
        /** @var InterviewReport $report */
        $report = InterviewReport::factory()->create([
            'overall_score' => 50,
        ]);

        $service = app(
            InterviewReportService::class
        );

        /** @var Interview $interview */
        $interview = $report->interview;

        $updatedReport = $service->regenerateReport(
            $interview
        );

        $this->assertInstanceOf(
            InterviewReport::class,
            $updatedReport
        );
    }
}
