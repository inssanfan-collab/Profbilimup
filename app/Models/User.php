<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\CuratorPermission;
use App\Enums\UserLocale;
use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'locale', 'must_set_password', 'permissions', 'has_all_courses_access', 'email_verified_at'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
            'locale' => UserLocale::class,
            'must_set_password' => 'boolean',
            'permissions' => 'array',
            'has_all_courses_access' => 'boolean',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    public function isListener(): bool
    {
        return $this->role === UserRole::Listener;
    }

    public function isCurator(): bool
    {
        return $this->role === UserRole::Curator;
    }

    /**
     * Админ имеет доступ ко всему; куратор — только к правам, включённым ему вручную.
     */
    public function hasPermission(CuratorPermission $permission): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        return $this->isCurator() && in_array($permission->value, $this->permissions ?? [], true);
    }

    /**
     * Админ имеет доступ ко всем курсам; куратор — либо ко всем (если ему это включили),
     * либо только к явно назначенным через curatorCourses().
     */
    public function hasCourseAccess(Course $course): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        if (! $this->isCurator()) {
            return false;
        }

        return $this->has_all_courses_access || $this->curatorCourses()->whereKey($course->id)->exists();
    }

    /**
     * Список id курсов, к которым куратор ограничен, для фильтрации запросов
     * (`whereIn('id', ...)` / `whereHas('course', fn ($q) => $q->whereIn('id', ...))`).
     * `null` означает «без ограничения» (админ или куратор с доступом ко всем курсам).
     *
     * @return array<int, int>|null
     */
    public function scopedCourseIds(): ?array
    {
        if ($this->isAdmin() || ($this->isCurator() && $this->has_all_courses_access)) {
            return null;
        }

        if (! $this->isCurator()) {
            return [];
        }

        return $this->curatorCourses()->pluck('courses.id')->all();
    }

    public function curatorCourses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'curator_course');
    }

    public function listenerProfile(): HasOne
    {
        return $this->hasOne(ListenerProfile::class);
    }

    public function courseAssignments(): HasMany
    {
        return $this->hasMany(CourseAssignment::class, 'listener_id');
    }
}
