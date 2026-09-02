<?php

namespace App\Livewire\Admin\Analytics;

use App\Enums\AssignmentStatus;
use App\Enums\UserRole;
use App\Livewire\Concerns\HasPageHeader;
use App\Models\Course;
use App\Models\CourseAssignment;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Dashboard extends Component
{
    use HasPageHeader;

    public function render(): View
    {
        $totalListeners = User::where('role', UserRole::Listener)->count();
        $totalCourses = Course::count();

        $assignments = CourseAssignment::query();
        $totalAssignments = (clone $assignments)->count();
        $completed = (clone $assignments)->where('status', AssignmentStatus::Completed)->count();
        $overdue = CourseAssignment::query()
            ->whereNotNull('deadline')
            ->where('deadline', '<', now())
            ->where('status', '!=', AssignmentStatus::Completed)
            ->count();

        $courses = Course::query()
            ->withCount([
                'assignments',
                'assignments as completed_assignments_count' => fn ($q) => $q->where('status', AssignmentStatus::Completed),
            ])
            ->get()
            ->map(function (Course $course) {
                $course->overdue_assignments_count = $course->assignments()
                    ->whereNotNull('deadline')
                    ->where('deadline', '<', now())
                    ->where('status', '!=', AssignmentStatus::Completed)
                    ->count();

                return $course;
            });

        return view('livewire.admin.analytics.dashboard', [
            'totalListeners' => $totalListeners,
            'totalCourses' => $totalCourses,
            'totalAssignments' => $totalAssignments,
            'completedAssignments' => $completed,
            'overdueAssignments' => $overdue,
            'completionRate' => $totalAssignments > 0 ? (int) round($completed / $totalAssignments * 100) : 0,
            'courses' => $courses,
        ])->layout('layouts.app', ['header' => $this->pageHeader(__('Аналитика'))]);
    }
}
