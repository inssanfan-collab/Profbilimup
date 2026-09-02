<?php

namespace App\Enums;

enum VideoMeetingStatus: string
{
    case Scheduled = 'scheduled';
    case Ended = 'ended';
}
