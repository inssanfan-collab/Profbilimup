<?php

namespace App\Models;

use App\Enums\CourseStatus;
use Database\Factories\CourseFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['title', 'description', 'academic_hours', 'status', 'cover_image_path', 'created_by'])]
class Course extends Model
{
    /** @use HasFactory<CourseFactory> */
    use HasFactory;

    /**
     * Минимальная продолжительность курса для публикации (п.25 Приказа МОН РК №95).
     */
    public const int MIN_ACADEMIC_HOURS_TO_PUBLISH = 36;

    protected function casts(): array
    {
        return [
            'status' => CourseStatus::class,
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function modules(): HasMany
    {
        return $this->hasMany(CourseModule::class)->orderBy('order');
    }

    public function canBePublished(): bool
    {
        return $this->academic_hours >= self::MIN_ACADEMIC_HOURS_TO_PUBLISH;
    }
}
