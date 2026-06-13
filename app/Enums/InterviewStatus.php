<?php

namespace App\Enums;

enum InterviewStatus: string
{
    case DRAFT = 'draft';
    case STARTED = 'started';
    case COMPLETED = 'completed';
    case CANCELED = 'canceled';
}
