<?php

namespace App\Models;

use App\Enums\CourseStatus;
use Database\Factories\CourseFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

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

    public function assignments(): HasMany
    {
        return $this->hasMany(CourseAssignment::class);
    }

    public function videoMeetings(): HasMany
    {
        return $this->hasMany(VideoMeeting::class);
    }

    public function curators(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'curator_course');
    }

    /**
     * All lessons in this course, in module order then lesson order.
     *
     * @return Collection<int, Lesson>
     */
    public function orderedLessons(): Collection
    {
        return $this->modules()->with('lessons')->get()->flatMap->lessons;
    }

    public function canBePublished(): bool
    {
        return $this->academic_hours >= self::MIN_ACADEMIC_HOURS_TO_PUBLISH;
    }
}
