<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['post_course_plan_id', 'title', 'content', 'feedback_text', 'feedback_by', 'feedback_at'])]
class PostCourseLessonPlan extends Model
{
    protected function casts(): array
    {
        return [
            'feedback_at' => 'datetime',
        ];
    }

    public function postCoursePlan(): BelongsTo
    {
        return $this->belongsTo(PostCoursePlan::class);
    }

    public function feedbackBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'feedback_by');
    }
}
