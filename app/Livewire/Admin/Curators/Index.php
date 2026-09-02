<?php

namespace App\Livewire\Admin\Curators;

use App\Enums\UserRole;
use App\Livewire\Concerns\HasPageHeader;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Index extends Component
{
    use HasPageHeader;

    public function mount(): void
    {
        abort_unless(auth()->user()->isAdmin(), 403);
    }

    public function render(): View
    {
        $curators = User::query()
            ->where('role', UserRole::Curator)
            ->withCount('curatorCourses')
            ->latest()
            ->get();

        return view('livewire.admin.curators.index', ['curators' => $curators])
            ->layout('layouts.app', ['header' => $this->pageHeader(__('Кураторы'))]);
    }
}
