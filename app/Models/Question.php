<?php

namespace App\Models;

use App\Enums\QuestionType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['test_id', 'type', 'question_text', 'order', 'points'])]
class Question extends Model
{
    protected function casts(): array
    {
        return [
            'type' => QuestionType::class,
        ];
    }

    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    public function choices(): HasMany
    {
        return $this->hasMany(QuestionChoice::class)->orderBy('order');
    }
}
