<?php

namespace App\Services\AI;

use App\Models\Resume;
use Gemini\Laravel\Facades\Gemini;
use Illuminate\Support\Facades\Log;

class GeminiResumeAnalysisService
{
    public function __construct(
        private readonly ResumeAnalysisPromptBuilderService $promptBuilder
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function analyze(
        Resume $resume,
        string $resumeText
    ): array {
        $prompt = $this->promptBuilder
            ->build($resume, $resumeText);

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

            Log::info('Gemini Raw Response',
                [
                    'response' => $text,
                ]
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
                    Log::error(
                    'Gemini resume analysis failed',
                    [
                        'message' => $e->getMessage(),
                        'exception' => $e::class,
                    ]
                );
                return $this->fallbackResponse();
            }

            return $decoded;
        } catch (\Throwable $e) {
            Log::error(
                'Gemini resume analysis failed',
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
            'overall_score' => 60,
            'skills' => [],
            'strengths' => [],
            'weaknesses' => [
                'Resume analysis unavailable.',
            ],
            'recommendations' => [
                'Please try again later.',
            ],
            'missing_skills' => [],
        ];
    }
}
