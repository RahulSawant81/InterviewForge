<?php

namespace Tests\Unit;

use App\Models\Resume;
use App\Models\ResumeAnalysis;
use App\Services\Resume\ResumeAnalysisService;
use App\Services\Resume\ResumeEvaluationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResumeAnalysisServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_analyze_and_save_creates_resume_analysis(): void
    {
        /** @var Resume $resume */
        $resume = Resume::factory()->create();

        $this->mock(
            ResumeEvaluationService::class,
            function ($mock) {
                $mock->shouldReceive('evaluate')
                    ->once()
                    ->andReturn([
                        'overall_score' => 88,
                        'skills' => [
                            'PHP',
                            'Laravel',
                        ],
                        'strengths' => [
                            'Strong project experience',
                        ],
                        'weaknesses' => [
                            'Needs more cloud examples',
                        ],
                        'recommendations' => [
                            'Highlight leadership impact',
                        ],
                        'missing_skills' => [
                            'AWS',
                        ],
                    ]);
            }
        );

        $service = app(
            ResumeAnalysisService::class
        );

        $analysis = $service->analyzeAndSave(
            $resume
        );

        $this->assertInstanceOf(
            ResumeAnalysis::class,
            $analysis
        );

        $this->assertDatabaseHas(
            'resume_analyses',
            [
                'resume_id' => $resume->id,
            ]
        );
    }

    public function test_get_analysis_returns_existing_analysis(): void
    {
        /** @var ResumeAnalysis $analysis */
        $analysis = ResumeAnalysis::factory()->create();

        $service = app(
            ResumeAnalysisService::class
        );

        $result = $service->getAnalysis(
            $analysis->resume
        );

        $this->assertNotNull($result);
        $this->assertSame(
            $analysis->id,
            $result->id
        );
    }
}
