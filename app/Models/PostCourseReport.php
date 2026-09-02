<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['course_assignment_id', 'content', 'diagnostic_before', 'diagnostic_after', 'submitted_at'])]
class PostCourseReport extends Model
{
    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
        ];
    }

    public function courseAssignment(): BelongsTo
    {
        return $this->belongsTo(CourseAssignment::class);
    }
}
