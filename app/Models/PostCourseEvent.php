<?php

namespace App\Models;

use App\Enums\PostCourseEventType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['course_assignment_id', 'type', 'title', 'event_date', 'description'])]
class PostCourseEvent extends Model
{
    protected function casts(): array
    {
        return [
            'type' => PostCourseEventType::class,
            'event_date' => 'date',
        ];
    }

    public function courseAssignment(): BelongsTo
    {
        return $this->belongsTo(CourseAssignment::class);
    }
}
