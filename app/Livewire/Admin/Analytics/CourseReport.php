<?php

namespace App\Livewire\Admin\Analytics;

use App\Enums\CuratorPermission;
use App\Livewire\Concerns\HasPageHeader;
use App\Models\Course;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class CourseReport extends Component
{
    use HasPageHeader;

    #[Locked]
    public Course $course;

    public function mount(Course $course): void
    {
        abort_unless(auth()->user()->hasPermission(CuratorPermission::Analytics), 403);
        abort_unless(auth()->user()->hasCourseAccess($course), 403);

        $this->course = $course;
    }

    public function render(): View
    {
        $assignments = $this->course->assignments()
            ->with(['listener.listenerProfile', 'certificate'])
            ->get();

        return view('livewire.admin.analytics.course-report', ['assignments' => $assignments])
            ->layout('layouts.app', ['header' => $this->pageHeader(__('Отчёт по курсу: :title', ['title' => $this->course->title]))]);
    }
}
