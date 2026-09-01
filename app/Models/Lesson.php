<?php

namespace App\Models;

use Database\Factories\LessonFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['course_module_id', 'title', 'order', 'content_html', 'video_url'])]
class Lesson extends Model
{
    /** @use HasFactory<LessonFactory> */
    use HasFactory;

    public function courseModule(): BelongsTo
    {
        return $this->belongsTo(CourseModule::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(LessonFile::class);
    }

    /**
     * Turn a YouTube watch/share URL into an embeddable player URL.
     * Returns null if video_url isn't set or isn't a recognisable YouTube link.
     */
    public function videoEmbedUrl(): ?string
    {
        if (! $this->video_url) {
            return null;
        }

        $videoId = match (true) {
            (bool) preg_match('#youtu\.be/([\w-]{11})#', $this->video_url, $m) => $m[1],
            (bool) preg_match('#[?&]v=([\w-]{11})#', $this->video_url, $m) => $m[1],
            (bool) preg_match('#youtube\.com/embed/([\w-]{11})#', $this->video_url, $m) => $m[1],
            default => null,
        };

        return $videoId ? "https://www.youtube.com/embed/{$videoId}" : null;
    }
}
