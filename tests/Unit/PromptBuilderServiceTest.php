<?php

namespace Tests\Unit;

use App\Enums\DifficultyLevel;
use App\Services\AI\PromptBuilderService;
use Tests\TestCase;

class PromptBuilderServiceTest extends TestCase
{
    public function test_builds_mock_prompt(): void
    {
        $service = new PromptBuilderService;

        $prompt = $service->build(
            'mock',
            DifficultyLevel::BEGINNER->value,
            []
        );

        $this->assertStringContainsString(
            'technical interviewer',
            $prompt
        );
    }

    public function test_builds_hr_prompt(): void
    {
        $service = new PromptBuilderService;

        $prompt = $service->build(
            'hr',
            DifficultyLevel::BEGINNER->value,
            []
        );

        $this->assertStringContainsString(
            'HR interviewer',
            $prompt
        );
    }

    public function test_builds_mcq_prompt(): void
    {
        $service = new PromptBuilderService;

        $prompt = $service->build(
            'mcq',
            DifficultyLevel::BEGINNER->value,
            []
        );

        $this->assertStringContainsString(
            'MCQ assessment',
            $prompt
        );
    }

    public function test_builds_coding_prompt(): void
    {
        $service = new PromptBuilderService;

        $prompt = $service->build(
            'coding',
            DifficultyLevel::BEGINNER->value,
            []
        );

        $this->assertStringContainsString(
            'senior software engineer',
            $prompt
        );
    }

    public function test_beginner_difficulty_guidelines_are_added(): void
    {
        $service = new PromptBuilderService;

        $prompt = $service->build(
            'mock',
            DifficultyLevel::BEGINNER->value,
            []
        );

        $this->assertStringContainsString(
            'Expected candidate level: Beginner',
            $prompt
        );
    }
}
