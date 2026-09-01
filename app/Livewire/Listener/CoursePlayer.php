<?php

namespace App\Livewire\Listener;

use App\Livewire\Concerns\HasPageHeader;
use App\Models\CourseAssignment;
use App\Services\ProgressService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class CoursePlayer extends Component
{
    use HasPageHeader;

    #[Locked]
    public CourseAssignment $assignment;

    public function mount(CourseAssignment $assignment): void
    {
        abort_unless($assignment->listener_id === auth()->id(), 403);

        $this->assignment = $assignment;
    }

    public function acceptAgreement(ProgressService $progressService): void
    {
        abort_if($this->assignment->isOverdue(), 403);

        $progressService->acceptAgreement($this->assignment);
    }

    public function render(): View
    {
        $modules = $this->assignment->course->modules()->with('lessons')->get();

        $progressByLessonId = $this->assignment->lessonProgress()->get()->keyBy('lesson_id');

        return view('livewire.listener.course-player', [
            'modules' => $modules,
            'progressByLessonId' => $progressByLessonId,
        ])->layout('layouts.app', ['header' => $this->pageHeader($this->assignment->course->title)]);
    }
}
