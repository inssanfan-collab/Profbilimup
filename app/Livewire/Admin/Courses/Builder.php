<?php

namespace App\Livewire\Admin\Courses;

use App\Enums\CourseStatus;
use App\Livewire\Concerns\HasPageHeader;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\Lesson;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Builder extends Component
{
    use HasPageHeader;

    #[Locked]
    public Course $course;

    public string $newModuleTitle = '';

    public ?int $editingModuleId = null;

    public string $editingModuleTitle = '';

    public ?int $addingLessonToModuleId = null;

    public string $newLessonTitle = '';

    public ?int $editingLessonId = null;

    public string $editingLessonTitle = '';

    public function mount(Course $course): void
    {
        $this->course = $course;
    }

    public function addModule(): void
    {
        $this->validate(['newModuleTitle' => ['required', 'string', 'max:255']]);

        $nextOrder = ((int) $this->course->modules()->max('order')) + 1;

        $this->course->modules()->create([
            'title' => $this->newModuleTitle,
            'order' => $nextOrder,
        ]);

        $this->newModuleTitle = '';
    }

    public function startEditingModule(CourseModule $module): void
    {
        $this->editingModuleId = $module->id;
        $this->editingModuleTitle = $module->title;
    }

    public function saveModuleTitle(): void
    {
        $this->validate(['editingModuleTitle' => ['required', 'string', 'max:255']]);

        CourseModule::whereBelongsTo($this->course)->findOrFail($this->editingModuleId)
            ->update(['title' => $this->editingModuleTitle]);

        $this->editingModuleId = null;
    }

    public function deleteModule(CourseModule $module): void
    {
        abort_unless($module->course_id === $this->course->id, 403);

        $module->delete();
    }

    public function moveModuleUp(CourseModule $module): void
    {
        $this->swapModuleOrder($module, previous: true);
    }

    public function moveModuleDown(CourseModule $module): void
    {
        $this->swapModuleOrder($module, previous: false);
    }

    private function swapModuleOrder(CourseModule $module, bool $previous): void
    {
        abort_unless($module->course_id === $this->course->id, 403);

        $neighbour = $this->course->modules()
            ->where('order', $previous ? '<' : '>', $module->order)
            ->orderBy('order', $previous ? 'desc' : 'asc')
            ->first();

        if (! $neighbour) {
            return;
        }

        [$a, $b] = [$module->order, $neighbour->order];
        $module->update(['order' => $b]);
        $neighbour->update(['order' => $a]);
    }

    public function startAddingLesson(CourseModule $module): void
    {
        $this->addingLessonToModuleId = $module->id;
        $this->newLessonTitle = '';
    }

    public function addLesson(): void
    {
        $this->validate(['newLessonTitle' => ['required', 'string', 'max:255']]);

        $module = CourseModule::whereBelongsTo($this->course)->findOrFail($this->addingLessonToModuleId);

        $nextOrder = ((int) $module->lessons()->max('order')) + 1;

        $module->lessons()->create([
            'title' => $this->newLessonTitle,
            'order' => $nextOrder,
        ]);

        $this->addingLessonToModuleId = null;
        $this->newLessonTitle = '';
    }

    public function startEditingLesson(Lesson $lesson): void
    {
        $this->editingLessonId = $lesson->id;
        $this->editingLessonTitle = $lesson->title;
    }

    public function saveLessonTitle(): void
    {
        $this->validate(['editingLessonTitle' => ['required', 'string', 'max:255']]);

        $lesson = Lesson::findOrFail($this->editingLessonId);
        abort_unless($lesson->courseModule->course_id === $this->course->id, 403);

        $lesson->update(['title' => $this->editingLessonTitle]);

        $this->editingLessonId = null;
    }

    public function deleteLesson(Lesson $lesson): void
    {
        abort_unless($lesson->courseModule->course_id === $this->course->id, 403);

        $lesson->delete();
    }

    public function moveLessonUp(Lesson $lesson): void
    {
        $this->swapLessonOrder($lesson, previous: true);
    }

    public function moveLessonDown(Lesson $lesson): void
    {
        $this->swapLessonOrder($lesson, previous: false);
    }

    private function swapLessonOrder(Lesson $lesson, bool $previous): void
    {
        abort_unless($lesson->courseModule->course_id === $this->course->id, 403);

        $neighbour = $lesson->courseModule->lessons()
            ->where('order', $previous ? '<' : '>', $lesson->order)
            ->orderBy('order', $previous ? 'desc' : 'asc')
            ->first();

        if (! $neighbour) {
            return;
        }

        [$a, $b] = [$lesson->order, $neighbour->order];
        $lesson->update(['order' => $b]);
        $neighbour->update(['order' => $a]);
    }

    public function publish(): void
    {
        if (! $this->course->canBePublished()) {
            $this->addError('publish', __('Нельзя опубликовать курс менее :min академических часов.', ['min' => Course::MIN_ACADEMIC_HOURS_TO_PUBLISH]));

            return;
        }

        $this->course->update(['status' => CourseStatus::Published]);
    }

    public function unpublish(): void
    {
        $this->course->update(['status' => CourseStatus::Draft]);
    }

    public function render(): View
    {
        $modules = $this->course->modules()->with('lessons')->get();

        return view('livewire.admin.courses.builder', ['modules' => $modules])
            ->layout('layouts.app', ['header' => $this->pageHeader($this->course->title)]);
    }
}
