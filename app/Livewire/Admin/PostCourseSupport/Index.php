<?php

namespace App\Livewire\Admin\PostCourseSupport;

use App\Enums\AssignmentStatus;
use App\Livewire\Concerns\HasPageHeader;
use App\Models\CourseAssignment;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Index extends Component
{
    use HasPageHeader;

    public function render(): View
    {
        $assignments = CourseAssignment::query()
            ->where('status', AssignmentStatus::Completed)
            ->with(['listener.listenerProfile', 'course', 'postCoursePlan', 'postCourseReference'])
            ->latest('completed_at')
            ->get();

        return view('livewire.admin.post-course-support.index', ['assignments' => $assignments])
            ->layout('layouts.app', ['header' => $this->pageHeader(__('Посткурсовое сопровождение'))]);
    }
}
