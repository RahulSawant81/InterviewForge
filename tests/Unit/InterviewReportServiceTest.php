<?php

namespace Tests\Unit;

use App\Models\Interview;
use App\Models\InterviewAnswer;
use App\Models\InterviewQuestion;
use App\Models\InterviewReport;
use App\Services\AI\InterviewEvaluationService;
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

        $this->mock(
            InterviewEvaluationService::class,
            function ($mock) {
                $mock->shouldReceive('evaluate')
                    ->once()
                    ->andReturn([
                        'overall_score' => 85,
                        'strengths' => [
                            'Good Laravel knowledge',
                        ],
                        'weaknesses' => [],
                        'recommendations' => [],
                        'answers' => [],
                    ]);
            }
        );

        $service = app(
            InterviewReportService::class
        );

        $report = $service->generateReport(
            $interview
        );

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

        $this->mock(
            InterviewEvaluationService::class,
            function ($mock) {
                $mock->shouldReceive('evaluate')
                    ->once()
                    ->andReturn([
                        'overall_score' => 90,
                        'strengths' => [],
                        'weaknesses' => [],
                        'recommendations' => [],
                        'answers' => [],
                    ]);
            }
        );

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

        $this->assertEquals(
            90,
            (int) $updatedReport->overall_score
        );
    }

    /**
     * Test updating answer score and feedback.
     */
    public function test_generate_report_updates_answer_feedback(): void
    {
        /** @var Interview $interview */
        $interview = Interview::factory()->create();

        /** @var InterviewQuestion $question */
        $question = InterviewQuestion::factory()->create([
            'interview_id' => $interview->id,
            'sequence' => 1,
        ]);

        /** @var InterviewAnswer $answer */
        $answer = InterviewAnswer::factory()->create([
            'interview_question_id' => $question->id,
        ]);

        $this->mock(
            InterviewEvaluationService::class,
            function ($mock) {
                $mock->shouldReceive('evaluate')
                    ->once()
                    ->andReturn([
                        'overall_score' => 85,
                        'strengths' => [],
                        'weaknesses' => [],
                        'recommendations' => [],
                        'answers' => [
                            [
                                'sequence' => 1,
                                'score' => 80,
                                'feedback' => 'Good answer',
                            ],
                        ],
                    ]);
            }
        );

        $service = app(
            InterviewReportService::class
        );

        $service->generateReport(
            $interview
        );

        $answer->refresh();

        $this->assertEquals(
            80,
            (int) $answer->score
        );

        $this->assertEquals(
            'Good answer',
            $answer->feedback
        );
    }
}
