<?php

namespace App\Services\Resume;

use App\Models\Resume;
use App\Services\AI\GeminiResumeAnalysisService;
use Illuminate\Support\Facades\Storage;

class ResumeEvaluationService
{
    public function __construct(
        private readonly GeminiResumeAnalysisService $geminiResumeAnalysisService
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function evaluate(Resume $resume): array
    {
        return $this->geminiResumeAnalysisService
            ->analyze(
                $resume,
                $this->extractResumeText($resume)
            );
    }

    private function extractResumeText(Resume $resume): string
    {
        if (
            ! $resume->file_path ||
            ! Storage::disk('public')->exists($resume->file_path)
        ) {
            return '';
        }

        $contents = Storage::disk('public')->get(
            $resume->file_path
        );

        $contents = preg_replace(
            '/[^\x20-\x7E\r\n\t]+/',
            ' ',
            $contents
        );

        $contents = is_string($contents)
            ? preg_replace('/\s+/', ' ', $contents)
            : '';

        return is_string($contents)
            ? trim(substr($contents, 0, 8000))
            : '';
    }
}
