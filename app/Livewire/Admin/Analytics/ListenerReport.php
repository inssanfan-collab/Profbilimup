<?php

namespace App\Livewire\Admin\Analytics;

use App\Enums\CuratorPermission;
use App\Enums\UserRole;
use App\Livewire\Concerns\HasPageHeader;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class ListenerReport extends Component
{
    use HasPageHeader;

    #[Locked]
    public User $listener;

    public function mount(User $listener): void
    {
        abort_unless($listener->role === UserRole::Listener, 404);
        abort_unless(auth()->user()->hasPermission(CuratorPermission::Analytics), 403);

        $courseIds = auth()->user()->scopedCourseIds();

        if ($courseIds !== null) {
            abort_unless($listener->courseAssignments()->whereIn('course_id', $courseIds)->exists(), 403);
        }

        $this->listener = $listener;
    }

    public function render(): View
    {
        $courseIds = auth()->user()->scopedCourseIds();

        $assignments = $this->listener->courseAssignments()
            ->when($courseIds !== null, fn ($q) => $q->whereIn('course_id', $courseIds))
            ->with(['course', 'certificate'])
            ->get();

        return view('livewire.admin.analytics.listener-report', ['assignments' => $assignments])
            ->layout('layouts.app', ['header' => $this->pageHeader($this->listener->listenerProfile?->full_name ?? $this->listener->name)]);
    }
}
