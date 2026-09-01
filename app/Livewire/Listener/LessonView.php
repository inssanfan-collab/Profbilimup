<?php

namespace App\Livewire\Listener;

use App\Enums\LessonProgressStatus;
use App\Livewire\Concerns\HasPageHeader;
use App\Models\CourseAssignment;
use App\Models\Lesson;
use App\Services\ProgressService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class LessonView extends Component
{
    use HasPageHeader;

    #[Locked]
    public CourseAssignment $assignment;

    #[Locked]
    public Lesson $lesson;

    public function mount(CourseAssignment $assignment, Lesson $lesson): void
    {
        abort_unless($assignment->listener_id === auth()->id(), 403);
        abort_unless($lesson->courseModule->course_id === $assignment->course_id, 404);
        abort_if($assignment->isOverdue(), 403);

        $progress = $assignment->lessonProgress()->where('lesson_id', $lesson->id)->first();
        abort_if(! $progress || $progress->status === LessonProgressStatus::Locked, 403);

        $this->assignment = $assignment;
        $this->lesson = $lesson;
    }

    public function complete(ProgressService $progressService): void
    {
        $progressService->completeLesson($this->assignment, $this->lesson);

        $this->redirectRoute('listener.courses.show', $this->assignment, navigate: true);
    }

    public function render(): View
    {
        $progress = $this->assignment->lessonProgress()->where('lesson_id', $this->lesson->id)->first();

        return view('livewire.listener.lesson-view', [
            'isCompleted' => $progress->status === LessonProgressStatus::Completed,
            'files' => $this->lesson->files,
        ])->layout('layouts.app', ['header' => $this->pageHeader($this->lesson->title)]);
    }
}
