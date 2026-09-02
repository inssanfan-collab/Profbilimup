<?php

namespace App\Models;

use App\Enums\PostCourseSupportStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['course_assignment_id', 'content', 'status', 'submitted_at', 'reviewed_by', 'reviewed_at', 'review_note'])]
class PostCoursePlan extends Model
{
    protected function casts(): array
    {
        return [
            'status' => PostCourseSupportStatus::class,
            'submitted_at' => 'datetime',
            'reviewed_at' => 'datetime',
        ];
    }

    public function courseAssignment(): BelongsTo
    {
        return $this->belongsTo(CourseAssignment::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function lessonPlans(): HasMany
    {
        return $this->hasMany(PostCourseLessonPlan::class);
    }
}
