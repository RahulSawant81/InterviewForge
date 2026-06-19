<?php

namespace App\Services\AI;

use App\Models\Resume;

class ResumeAnalysisPromptBuilderService
{
    public function build(
        Resume $resume,
        string $resumeText
    ): string {
        $resumeText = trim($resumeText);

        if ($resumeText === '') {
            $resumeText = 'No resume text could be extracted. Base the analysis on the available metadata only.';
        }

        return <<<PROMPT
You are an expert resume reviewer for software and technical roles.

Review the resume and identify strengths, weaknesses, recommendations, current skills, and missing skills.

Resume Metadata:
- Title: {$resume->title}
- Original Filename: {$resume->original_filename}
- MIME Type: {$resume->mime_type}
- File Size: {$resume->file_size}

Resume Content:
{$resumeText}

Return ONLY valid JSON.
Do not use markdown.
Do not use code fences.
Do not add explanations.

overall_score must be an integer from 0 to 100.

{
  "overall_score": 78,
  "skills": ["PHP", "Laravel"],
  "strengths": ["Clear backend experience"],
  "weaknesses": ["Limited cloud exposure"],
  "recommendations": ["Add measurable achievements"],
  "missing_skills": ["Docker", "AWS"]
}
PROMPT;
    }
}
