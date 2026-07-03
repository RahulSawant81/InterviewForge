<?php

namespace App\Enums;

enum QuestionType: string
{
    case CODING = 'coding';

    case MCQ = 'mcq';

    case THEORY = 'theory';

    case SCENARIO = 'scenario';
}
