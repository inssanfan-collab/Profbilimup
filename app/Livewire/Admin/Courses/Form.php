<?php

namespace App\Livewire\Admin\Courses;

use App\Enums\CourseStatus;
use App\Enums\CuratorPermission;
use App\Livewire\Concerns\HasPageHeader;
use App\Models\Course;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Form extends Component
{
    use HasPageHeader;

    #[Locked]
    public ?Course $course = null;

    public string $title = '';

    public string $description = '';

    public ?int $academic_hours = null;

    public function mount(?Course $course = null): void
    {
        abort_unless(auth()->user()->hasPermission(CuratorPermission::Courses), 403);

        if ($course?->exists) {
            abort_unless(auth()->user()->hasCourseAccess($course), 403);

            $this->course = $course;
            $this->title = $course->title;
            $this->description = (string) $course->description;
            $this->academic_hours = $course->academic_hours;
        } else {
            // Куратор с ограниченным списком курсов не может создавать новые —
            // новый курс по определению не входит в заранее заданный список.
            abort_unless(auth()->user()->isAdmin() || auth()->user()->has_all_courses_access, 403);
        }
    }

    public function save()
    {
        $validated = $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'academic_hours' => ['required', 'integer', 'min:1', 'max:1000'],
        ]);

        if ($this->course) {
            $this->course->update($validated);

            return redirect()->route('admin.courses.builder', $this->course);
        }

        $course = Course::create([
            ...$validated,
            'status' => CourseStatus::Draft,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.courses.builder', $course);
    }

    public function render(): View
    {
        $title = $this->course ? __('Параметры курса') : __('Новый курс');

        return view('livewire.admin.courses.form')
            ->layout('layouts.app', ['header' => $this->pageHeader($title)]);
    }
}
