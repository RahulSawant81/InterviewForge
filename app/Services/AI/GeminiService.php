<?php

namespace App\Services\AI;

use Gemini\Laravel\Facades\Gemini;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    public function __construct(
        private readonly PromptBuilderService $promptBuilder
    ) {}
    /**
     * @param array<int, array<string, mixed>> $items
     * @return array<string, mixed>
     */
    public function evaluateInterview(string $interviewType, string $difficulty, array $items): array
    {
        $prompt = $this->promptBuilder->build(
            $interviewType,
            $difficulty,
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
                return $this->fallbackResponse();
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

            return $this->fallbackResponse();
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function fallbackResponse(string $message = 'AI evaluation unavailable.'): array
    {
        return [
            'overall_score' => 60,
            'strengths' => [],
            'weaknesses' => [
                $message,
            ],
            'recommendations' => [
                'Please try again later.',
            ],
            'answers' => [],
        ];
    }
}
