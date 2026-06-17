<?php

namespace App\Services\AI;

use Gemini\Laravel\Facades\Gemini;

class GeminiService
{
    /**
     * @return array<string, mixed>
     */
    public function evaluateInterview(
        string $interviewType,
        array $questions,
        array $answers
    ): array {

        $prompt = $this->buildPrompt(
            $interviewType,
            $questions,
            $answers
        );

        $response = Gemini::generativeModel(
            model: 'gemini-2.5-flash'
        )->generateContent(
            $prompt
        );

        $text = trim($response->text());

        $text = str_replace(
            [
                '```json',
                '```',
            ],
            '',
            $text
        );

        $text = trim($text);

        /** @var array<string, mixed>|null $decoded */
        $decoded = json_decode(
            $text,
            true
        );

        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'overall_score' => 0,
                'strengths' => [],
                'weaknesses' => [],
                'recommendations' => [
                    'AI evaluation could not be parsed.',
                ],
            ];
        }

        return $decoded;
    }

    /**
     * @param array<int, mixed> $questions
     * @param array<int, mixed> $answers
     */
    private function buildPrompt(
        string $interviewType,
        array $questions,
        array $answers
    ): string {

        return <<<PROMPT
You are a senior technical interviewer.

Interview Type:
{$interviewType}

Questions:
{$this->formatQuestions($questions)}

Answers:
{$this->formatAnswers($answers)}

Evaluate the candidate's answers.

overall_score must be an integer between 0 and 100.

Return ONLY valid JSON.

Do not use markdown.
Do not use code fences.
Do not wrap the response in ```json.
Do not add explanations.

Expected schema:

{
  "overall_score": 85,
  "strengths": [],
  "weaknesses": [],
  "recommendations": []
}
PROMPT;
    }

    /**
     * @param array<int, mixed> $questions
     */
    private function formatQuestions(
        array $questions
    ): string {
        return json_encode(
            $questions,
            JSON_PRETTY_PRINT
        ) ?: '[]';
    }

    /**
     * @param array<int, mixed> $answers
     */
    private function formatAnswers(
        array $answers
    ): string {
        return json_encode(
            $answers,
            JSON_PRETTY_PRINT
        ) ?: '[]';
    }
}
