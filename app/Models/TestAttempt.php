<?php

namespace App\Models;

use App\Enums\TestAttemptStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['test_id', 'listener_id', 'attempt_number', 'status', 'passed', 'score_percent', 'started_at', 'submitted_at'])]
class TestAttempt extends Model
{
    protected function casts(): array
    {
        return [
            'status' => TestAttemptStatus::class,
            'passed' => 'boolean',
            'started_at' => 'datetime',
            'submitted_at' => 'datetime',
        ];
    }

    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    public function listener(): BelongsTo
    {
        return $this->belongsTo(User::class, 'listener_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(TestAnswer::class);
    }

    public function isTimeExpired(): bool
    {
        $limit = $this->test->time_limit_minutes;

        return $limit && $this->started_at->addMinutes($limit)->isPast();
    }
}
