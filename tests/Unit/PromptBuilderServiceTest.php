<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\AI\PromptBuilderService;
use Tests\TestCase;

class PromptBuilderServiceTest extends TestCase
{
    public function test_builds_mock_prompt(): void
    {
        $service = new PromptBuilderService();

        $prompt = $service->build(
            'mock',
            []
        );

        $this->assertStringContainsString(
            'technical interviewer',
            $prompt
        );
    }

    public function test_builds_hr_prompt(): void
    {
        $service = new PromptBuilderService();

        $prompt = $service->build(
            'hr',
            []
        );

        $this->assertStringContainsString(
            'HR interviewer',
            $prompt
        );
    }

    public function test_builds_mcq_prompt(): void
    {
        $service = new PromptBuilderService();

        $prompt = $service->build(
            'mcq',
            []
        );

        $this->assertStringContainsString(
            'MCQ assessment',
            $prompt
        );
    }

    public function test_builds_coding_prompt(): void
    {
        $service = new PromptBuilderService();

        $prompt = $service->build(
            'coding',
            []
        );

        $this->assertStringContainsString(
            'senior software engineer',
            $prompt
        );
    }
}
