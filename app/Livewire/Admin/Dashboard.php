<?php

namespace App\Livewire\Admin;

use App\Enums\AssignmentStatus;
use App\Enums\CourseStatus;
use App\Enums\UserRole;
use App\Livewire\Concerns\HasPageHeader;
use App\Models\Course;
use App\Models\CourseAssignment;
use App\Models\User;
use App\Services\TestGradingService;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Dashboard extends Component
{
    use HasPageHeader;

    public function render(TestGradingService $testGradingService): View
    {
        $overdueAssignments = CourseAssignment::query()
            ->whereNotNull('deadline')
            ->where('deadline', '<', now())
            ->where('status', '!=', AssignmentStatus::Completed)
            ->count();

        return view('livewire.admin.dashboard', [
            'totalListeners' => User::where('role', UserRole::Listener)->count(),
            'totalCourses' => Course::where('status', CourseStatus::Published)->count(),
            'inProgressAssignments' => CourseAssignment::where('status', AssignmentStatus::InProgress)->count(),
            'overdueAssignments' => $overdueAssignments,
            'pendingReviewCount' => $testGradingService->pendingReview()->count(),
            'recentAssignments' => CourseAssignment::query()
                ->with(['listener.listenerProfile', 'course'])
                ->latest('assigned_at')
                ->limit(5)
                ->get(),
        ])->layout('layouts.app', ['header' => $this->pageHeader(__('Панель администратора'))]);
    }
}
