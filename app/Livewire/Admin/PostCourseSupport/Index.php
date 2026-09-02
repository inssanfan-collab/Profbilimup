<?php

namespace App\Livewire\Admin\PostCourseSupport;

use App\Enums\AssignmentStatus;
use App\Enums\CuratorPermission;
use App\Livewire\Concerns\HasPageHeader;
use App\Models\CourseAssignment;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Index extends Component
{
    use HasPageHeader;

    public function mount(): void
    {
        abort_unless(auth()->user()->hasPermission(CuratorPermission::PostCourseSupport), 403);
    }

    public function render(): View
    {
        $courseIds = auth()->user()->scopedCourseIds();

        $assignments = CourseAssignment::query()
            ->where('status', AssignmentStatus::Completed)
            ->when($courseIds !== null, fn ($q) => $q->whereIn('course_id', $courseIds))
            ->with(['listener.listenerProfile', 'course', 'postCoursePlan', 'postCourseReference'])
            ->latest('completed_at')
            ->get();

        return view('livewire.admin.post-course-support.index', ['assignments' => $assignments])
            ->layout('layouts.app', ['header' => $this->pageHeader(__('Посткурсовое сопровождение'))]);
    }
}
