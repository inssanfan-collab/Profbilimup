<?php

namespace App\Livewire\Admin\Analytics;

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

        $this->listener = $listener;
    }

    public function render(): View
    {
        $assignments = $this->listener->courseAssignments()
            ->with(['course', 'certificate'])
            ->get();

        return view('livewire.admin.analytics.listener-report', ['assignments' => $assignments])
            ->layout('layouts.app', ['header' => $this->pageHeader($this->listener->listenerProfile?->full_name ?? $this->listener->name)]);
    }
}
