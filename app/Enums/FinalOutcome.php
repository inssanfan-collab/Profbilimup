<?php

namespace App\Enums;

enum FinalOutcome: string
{
    case Pending = 'pending';
    case Passed = 'passed';
    case AttendanceOnly = 'attendance_only';
}
