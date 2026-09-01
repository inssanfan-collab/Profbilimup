<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['test_attempt_id', 'question_id', 'selected_choice_ids', 'free_text_answer', 'is_correct', 'points_awarded', 'reviewed_by', 'reviewed_at'])]
class TestAnswer extends Model
{
    protected function casts(): array
    {
        return [
            'selected_choice_ids' => 'array',
            'is_correct' => 'boolean',
            'reviewed_at' => 'datetime',
        ];
    }

    public function testAttempt(): BelongsTo
    {
        return $this->belongsTo(TestAttempt::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
