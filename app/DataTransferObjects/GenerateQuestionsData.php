<?php

namespace App\DataTransferObjects;

class GenerateQuestionsData
{
    /**
     * Create a new class instance.
     *
     * @param array<int, string> $skills
     */
    public function __construct(
        public readonly string $interviewType,
        public readonly string $difficulty,
        public readonly string $questionSource,
        public readonly array $skills,
    ) {}
}
