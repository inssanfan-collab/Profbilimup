<?php

namespace App\Models;

use App\Enums\VideoMeetingStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['course_id', 'lesson_id', 'created_by', 'external_meeting_id', 'name', 'moderator_password', 'attendee_password', 'status', 'starts_at', 'ended_at'])]
class VideoMeeting extends Model
{
    protected function casts(): array
    {
        return [
            'status' => VideoMeetingStatus::class,
            'starts_at' => 'datetime',
            'ended_at' => 'datetime',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
