<?php

namespace App\Services\AI;

class QuestionPromptBuilderService
{
    /**
     * @param array<int, string> $skills
     */
    public function build(
        string $interviewType,
        string $difficulty,
        string $questionSource,
        array $skills
    ): string {

        $skillsText = implode(
            ', ',
            $skills
        );

        return <<<PROMPT
You are an expert technical interviewer.

Interview Type:
{$interviewType}

Difficulty:
{$difficulty}

Question Source:
{$questionSource}

Skills:
{$skillsText}

Generate interview questions based on the provided skills.

Rules:

- Return ONLY valid JSON.
- Do not use markdown.
- Do not use code fences.
- Do not add explanations.
- Questions must match the interview type.
- Questions must match the difficulty level.
- Focus on the provided skills.

Expected schema:

{
  "questions": [
    {
      "sequence": 1,
      "question": "What is Dependency Injection?"
    }
  ]
}
PROMPT;
    }
}
