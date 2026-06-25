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
            $resumeText = 'No resume text could be extracted. Base your analysis on the available metadata only.';
        }

        return <<<PROMPT
You are an expert ATS Resume Reviewer, Senior Technical Recruiter, and Career Coach.

Your job is to analyze the candidate's resume exactly like an ATS system and provide constructive, professional feedback.

Resume Metadata

Title:
{$resume->title}

Original Filename:
{$resume->original_filename}

MIME Type:
{$resume->mime_type}

File Size:
{$resume->file_size}

Resume Content

{$resumeText}

Instructions

- Evaluate the resume as if it were submitted for a Senior Software Engineer or Full Stack Developer position.
- Focus on ATS compatibility.
- Identify technical skills accurately.
- Evaluate work experience, projects, education and overall presentation.
- Do NOT invent experience that is not present.
- Recommendations should be practical and actionable.
- Missing skills should only include technologies that are commonly expected for modern software engineering roles.
- Overall score must be between 0 and 100.

IMPORTANT

Return ONLY valid JSON.

Do NOT use markdown.

Do NOT use code fences.

Do NOT include explanations.

JSON Schema

{
  "summary": "A concise 3-5 sentence executive summary of the candidate's profile.",

  "overall_assessment": "Overall evaluation of resume quality and ATS readiness.",

  "overall_score": 78,

  "skills": [
    "PHP",
    "Laravel"
  ],

  "strengths": [
    "Strong Laravel expertise"
  ],

  "weaknesses": [
    "Limited cloud experience"
  ],

  "recommendations": [
    "Add measurable achievements"
  ],

  "missing_skills": [
    "Docker",
    "Kubernetes"
  ]
}
PROMPT;
    }
}
