<?php

namespace App\Services\AI;

use App\Enums\DifficultyLevel;
use App\Enums\InterviewType;

class PromptBuilderService
{
    /**
     * @param array<int, array<string, mixed>> $items
     */
    public function build(string $interviewType, string $difficulty, array $items): string
    {
        return match ($interviewType) {

            InterviewType::MOCK->value => $this->buildMockPrompt(
                $difficulty,
                $items
            ),

            InterviewType::HR->value => $this->buildHrPrompt(
                $difficulty,
                $items
            ),

            InterviewType::MCQ->value => $this->buildMcqPrompt(
                $difficulty,
                $items
            ),

            InterviewType::CODING->value => $this->buildCodingPrompt(
                $difficulty,
                $items
            ),

            default => $this->buildMockPrompt(
                $difficulty,
                $items
            ),
        };
    }

    /**
     * @param array<int, array<string, mixed>> $items
     */
    private function buildMockPrompt(string $difficulty, array $items): string
    {
        return <<<PROMPT
        You are a senior technical interviewer.

        Interview Type:
        MOCK

        Difficulty:
        {$difficulty}

        {$this->difficultyGuidelines($difficulty)}

        Evaluate the candidate based on:

        - Technical accuracy
        - PHP knowledge
        - Laravel knowledge
        - API design
        - Database knowledge
        - Design patterns
        - Problem solving
        - Clarity of explanation

        Adjust scoring according to the difficulty level.

        Questions and Answers:

        {$this->formatItems($items)}

        {$this->jsonSchema()}
        PROMPT;
    }

    /**
     * @param array<int, array<string, mixed>> $items
     */
    private function buildHrPrompt(string $difficulty, array $items): string
    {

        return <<<PROMPT
        You are an HR interviewer.

        Interview Type:
        HR

        Difficulty:
        {$difficulty}

        {$this->difficultyGuidelines($difficulty)}

        Evaluate the candidate based on:

        - Communication
        - Teamwork
        - Leadership
        - Ownership
        - Conflict resolution
        - Professionalism
        - Confidence

        Adjust scoring according to the difficulty level.

        Questions and Answers:

        {$this->formatItems($items)}

        {$this->jsonSchema()}
        PROMPT;
    }

    /**
     * @param array<int, array<string, mixed>> $items
     */
    private function buildMcqPrompt(string $difficulty, array $items): string
    {

        return <<<PROMPT
        You are evaluating an MCQ assessment.

        Interview Type:
        MCQ

        Difficulty:
        {$difficulty}

        {$this->difficultyGuidelines($difficulty)}

        Evaluate based on:

        - Correctness
        - Accuracy
        - Subject knowledge

        Treat answers objectively.

        Questions and Answers:

        {$this->formatItems($items)}

        {$this->jsonSchema()}
        PROMPT;
    }

    /**
     * @param array<int, array<string, mixed>> $items
     */
    private function buildCodingPrompt(string $difficulty, array $items): string
    {

        return <<<PROMPT
        You are a senior software engineer.

        Interview Type:
        CODING

        Difficulty:
        {$difficulty}

        {$this->difficultyGuidelines($difficulty)}

        Evaluate based on:

        - Correctness
        - Code quality
        - Readability
        - Maintainability
        - Time complexity
        - Space complexity
        - Best practices
        - Error handling

        Adjust scoring according to the difficulty level.

        Questions and Answers:

        {$this->formatItems($items)}

        {$this->jsonSchema()}
        PROMPT;
    }

    /**
     * @param array<int, array<string, mixed>> $items
     */
    private function formatItems(array $items): string
    {

        return json_encode(
            $items,
            JSON_PRETTY_PRINT
        ) ?: '[]';
    }

    private function jsonSchema(): string
    {
        return <<<'JSON'
        Return ONLY valid JSON.

        Do not use markdown.
        Do not use code fences.
        Do not add explanations.

        overall_score must be an integer from 0 to 100.

        answer.score must be an integer from 0 to 100.

        {
        "overall_score": 85,
        "strengths": [],
        "weaknesses": [],
        "recommendations": [],
        "answers": [
            {
            "sequence": 1,
            "score": 80,
            "feedback": "Detailed feedback"
            }
        ]
        }
        JSON;
    }

    private function difficultyGuidelines(string $difficulty): string
    {

        return match ($difficulty) {

            DifficultyLevel::BEGINNER->value => <<<'TEXT'
            Expected candidate level: Beginner.

            Evaluate generously.

            Expect:
            - Basic concepts
            - Fundamental understanding
            - Simple explanations
            - Limited real-world experience
            TEXT,

            DifficultyLevel::INTERMEDIATE->value => <<<'TEXT'
            Expected candidate level: Intermediate.

            Expect:
            - Practical experience
            - Real-world examples
            - Best practices
            - Trade-off discussions
            TEXT,

            DifficultyLevel::ADVANCED->value => <<<'TEXT'
            Expected candidate level: Advanced.

            Expect:
            - Deep technical knowledge
            - Architecture decisions
            - Scalability considerations
            - Performance optimization
            - Leadership and mentoring experience
            TEXT,

            default => '',
        };
    }
}
