<?php

namespace App\Enums;

enum TestAttemptStatus: string
{
    case InProgress = 'in_progress';
    case Submitted = 'submitted';
    case AwaitingReview = 'awaiting_review';
    case Graded = 'graded';
}
