<?php

namespace App\Services\AI;

use Gemini\Laravel\Facades\Gemini;
use Illuminate\Support\Facades\Log;

class GeminiQuestionService
{
    public function __construct(
        private readonly QuestionPromptBuilderService $promptBuilder
    ) {}

    /**
     * @param array<int, string> $skills
     *
     * @return array<string, mixed>
     */
    public function generateQuestions(
        string $interviewType,
        string $difficulty,
        string $questionSource,
        array $skills
    ): array {

        $prompt = $this->promptBuilder->build(
            $interviewType,
            $difficulty,
            $questionSource,
            $skills
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
                'Gemini question generation failed',
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
    private function fallbackResponse(): array
    {
        return [
            'questions' => [],
        ];
    }
}
