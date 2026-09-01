<?php

namespace App\Models;

use App\Enums\AssignmentStatus;
use App\Enums\FinalOutcome;
use Database\Factories\CourseAssignmentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['course_id', 'listener_id', 'assigned_by', 'deadline', 'status', 'final_outcome', 'agreement_accepted_at', 'assigned_at', 'completed_at', 'retake_available_at'])]
class CourseAssignment extends Model
{
    /** @use HasFactory<CourseAssignmentFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'status' => AssignmentStatus::class,
            'final_outcome' => FinalOutcome::class,
            'deadline' => 'date',
            'agreement_accepted_at' => 'datetime',
            'assigned_at' => 'datetime',
            'completed_at' => 'datetime',
            'retake_available_at' => 'date',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function listener(): BelongsTo
    {
        return $this->belongsTo(User::class, 'listener_id');
    }

    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function lessonProgress(): HasMany
    {
        return $this->hasMany(LessonProgress::class);
    }

    public function certificate(): HasOne
    {
        return $this->hasOne(Certificate::class);
    }

    public function isOverdue(): bool
    {
        return $this->deadline
            && $this->deadline->isPast()
            && $this->status !== AssignmentStatus::Completed;
    }

    public function progressPercent(): int
    {
        $total = $this->lessonProgress()->count();

        if ($total === 0) {
            return 0;
        }

        $completed = $this->lessonProgress()->where('status', 'completed')->count();

        return (int) round($completed / $total * 100);
    }
}
