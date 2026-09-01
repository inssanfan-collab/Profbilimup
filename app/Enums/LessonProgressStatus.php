<?php

namespace App\Enums;

enum LessonProgressStatus: string
{
    case Locked = 'locked';
    case Available = 'available';
    case Completed = 'completed';
}
