<?php

namespace App\Enums;

enum InterviewType: string
{
    case TECHNICAL = 'technical';

    case HR = 'hr';

    case MIXED = 'mixed';

    public function label(): string
    {
        return match ($this) {
            self::TECHNICAL => 'Technical',
            self::HR => 'HR',
            self::MIXED => 'Mixed',
        };
    }
}
