<?php

namespace App\Enums;

enum DifficultyLevel: string
{
    case BEGINNER = 'beginner';
    case INTERMEDIATE = 'intermediate';
    case ADVANCED = 'advanced';


    public function label(): string
    {
        return match ($this) {
            self::BEGINNER => 'Beginner',
            self::INTERMEDIATE => 'Intermediate',
            self::ADVANCED => 'Advanced',
        };
    }
}
