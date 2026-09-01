<?php

namespace App\Models;

use App\Enums\LessonProgressStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['course_assignment_id', 'lesson_id', 'status', 'completed_at'])]
class LessonProgress extends Model
{
    protected function casts(): array
    {
        return [
            'status' => LessonProgressStatus::class,
            'completed_at' => 'datetime',
        ];
    }

    public function courseAssignment(): BelongsTo
    {
        return $this->belongsTo(CourseAssignment::class);
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }
}
