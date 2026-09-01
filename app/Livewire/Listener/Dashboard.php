<?php

namespace App\Livewire\Listener;

use App\Livewire\Concerns\HasPageHeader;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Dashboard extends Component
{
    use HasPageHeader;

    public function render(): View
    {
        $assignments = auth()->user()->courseAssignments()->with('course')->latest('assigned_at')->get();

        return view('livewire.listener.dashboard', ['assignments' => $assignments])
            ->layout('layouts.app', ['header' => $this->pageHeader(__('Мои курсы'))]);
    }
}
