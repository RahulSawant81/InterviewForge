<?php

namespace App\Enums;

enum InterviewType: string
{
    case MCQ = 'mcq';
    case CODING = 'coding';
    case MOCK = 'mock';
    case HR = 'hr';

}
