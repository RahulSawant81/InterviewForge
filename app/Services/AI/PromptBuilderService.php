<?php

namespace App\Services\AI;

use App\Enums\InterviewType;

class PromptBuilderService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

     /**
     * @param array<int, array<string, mixed>> $items
     */
    public function build(
        string $interviewType,
        array $items
    ): string {

        return match ($interviewType) {

            InterviewType::MOCK->value =>
                $this->buildMockPrompt($items),

            InterviewType::HR->value =>
                $this->buildHrPrompt($items),

            InterviewType::MCQ->value =>
                $this->buildMcqPrompt($items),

            InterviewType::CODING->value =>
                $this->buildCodingPrompt($items),

            default =>
                $this->buildMockPrompt($items),
        };
    }

    /**
     * @param array<int, array<string, mixed>> $items
     */
    private function buildMockPrompt(array $items): string
    {

        return <<<PROMPT
        You are a senior technical interviewer.

        Evaluate the candidate's technical knowledge.

        Focus on:
        - PHP
        - Laravel
        - APIs
        - Databases
        - Design Patterns
        - Problem Solving

        Questions and Answers:

        {$this->formatItems($items)}

        {$this->jsonSchema()}
        PROMPT;
    }

    /**
     * @param array<int, array<string, mixed>> $items
     */
    private function buildHrPrompt(array $items): string
    {

        return <<<PROMPT
        You are an HR interviewer.

        Evaluate the candidate on:

        - Communication
        - Teamwork
        - Leadership
        - Ownership
        - Conflict Resolution
        - Professionalism

        Questions and Answers:

        {$this->formatItems($items)}

        {$this->jsonSchema()}
        PROMPT;
    }

    /**
     * @param array<int, array<string, mixed>> $items
     */
    private function buildMcqPrompt(array $items): string
    {

        return <<<PROMPT
        You are evaluating an MCQ assessment.

        Evaluate:

        - Accuracy
        - Knowledge
        - Correctness

        Questions and Answers:

        {$this->formatItems($items)}

        {$this->jsonSchema()}
        PROMPT;
    }

    /**
     * @param array<int, array<string, mixed>> $items
     */
    private function buildCodingPrompt(array $items): string
    {

        return <<<PROMPT
        You are a senior software engineer.

        Evaluate:

        - Code Quality
        - Correctness
        - Complexity
        - Performance
        - Maintainability
        - Best Practices

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
        return <<<JSON
        Return ONLY valid JSON.

        Do not use markdown.
        Do not use code fences.
        Do not add explanations.

        {
        "overall_score": 85,
        "strengths": [],
        "weaknesses": [],
        "recommendations": [],
        "answers": [
            {
            "sequence": 1,
            "score": 80,
            "feedback": "..."
            }
        ]
        }
        JSON;
    }
}
