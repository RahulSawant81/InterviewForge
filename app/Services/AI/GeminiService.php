<?php

namespace App\Services\AI;

use Gemini\Laravel\Facades\Gemini;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    /**
     * @param array<int, array<string, mixed>> $items
     * @return array<string, mixed>
     */
    public function evaluateInterview(string $interviewType, array $items): array
    {
        $prompt = $this->buildPrompt(
            $interviewType,
            $items
        );

        try {

            $response = Gemini::generativeModel(
                model: config('gemini.model')
            )->generateContent(
                $prompt
            );

            $text = trim(
                $response->text()
            );

            $text = str_replace(
                [
                    '```json',
                    '```',
                ],
                '',
                $text
            );

            /** @var array<string, mixed>|null $decoded */
            $decoded = json_decode(
                trim($text),
                true
            );

            if (
                json_last_error()
                !== JSON_ERROR_NONE
            ) {
                return [
                    'overall_score' => 60,
                    'strengths' => [],
                    'weaknesses' => [
                        'AI evaluation could not be parsed.',
                    ],
                    'recommendations' => [],
                    'answers' => [],
                ];
            }

            return $decoded;

        } catch (\Throwable $e) {

            Log::error(
                'Gemini evaluation failed',
                [
                    'message' => $e->getMessage(),
                    'exception' => $e::class,
                ]
            );

            return [
                'overall_score' => 60,
                'strengths' => [],
                'weaknesses' => [
                    'AI evaluation unavailable.',
                ],
                'recommendations' => [
                    'Please try again later.',
                ],
                'answers' => [],
            ];
        }
    }

    /**
     * @param array<int, array<string, mixed>> $items
     */
    private function buildPrompt(string $interviewType, array $items): string
    {

        return <<<PROMPT
            You are a senior technical interviewer.

            Interview Type:
            {$interviewType}

            Questions and Answers:
            {$this->formatItems($items)}

            Evaluate each answer individually.

            For each item:
            - Assign a score from 0 to 100
            - Provide constructive feedback

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
            "recommendations": [],
            "answers": [
                {
                "sequence": 1,
                "score": 80,
                "feedback": "..."
                }
            ]
        }
        PROMPT;
    }

    /**
     * @param array<int, array<string, mixed>> $items
     */
    private function formatItems(
        array $items
    ): string {
        return json_encode(
            $items,
            JSON_PRETTY_PRINT
        ) ?: '[]';
    }

    /**
     * @return array<string, mixed>
     */
    // public function evaluateAnswer(string $question, string $answer, string $interviewType): array
    // {
    //     $prompt = <<<PROMPT
    // You are a senior interviewer.

    // Interview Type:
    // {$interviewType}

    // Question:
    // {$question}

    // Candidate Answer:
    // {$answer}

    // Evaluate the answer.

    // score must be an integer between 0 and 100.

    // Return ONLY valid JSON.

    // {
    // "score": 85,
    // "feedback": "Detailed feedback"
    // }
    // PROMPT;

    //     $response = Gemini::generativeModel(
    //         model: 'gemini-2.5-flash'
    //     )->generateContent(
    //         $prompt
    //     );

    //     $text = trim($response->text());

    //     $text = str_replace(
    //         [
    //             '```json',
    //             '```',
    //         ],
    //         '',
    //         $text
    //     );

    //     /** @var array<string,mixed>|null $decoded */
    //     $decoded = json_decode(
    //         trim($text),
    //         true
    //     );

    //     return $decoded ?? [
    //         'score' => 60,
    //         'feedback' => 'AI evaluation unavailable.',
    //     ];
    // }
}
