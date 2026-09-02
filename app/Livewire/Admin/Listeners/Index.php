<?php

namespace App\Livewire\Admin\Listeners;

use App\Enums\CuratorPermission;
use App\Enums\UserRole;
use App\Livewire\Concerns\HasPageHeader;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\URL as UrlFacade;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use HasPageHeader, WithPagination;

    #[Url]
    public string $search = '';

    public function mount(): void
    {
        abort_unless(auth()->user()->hasPermission(CuratorPermission::Listeners), 403);
    }

    public function inviteLink(User $user): string
    {
        return UrlFacade::temporarySignedRoute('invite.accept', now()->addDays(14), ['user' => $user->id]);
    }

    public function render(): View
    {
        $courseIds = auth()->user()->scopedCourseIds();

        $listeners = User::query()
            ->where('role', UserRole::Listener)
            ->when($courseIds !== null, fn ($query) => $query->whereHas(
                'courseAssignments',
                fn ($q) => $q->whereIn('course_id', $courseIds),
            ))
            ->with('listenerProfile')
            ->when($this->search !== '', fn ($query) => $query
                ->where(fn ($q) => $q
                    ->where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%")))
            ->latest()
            ->paginate(15);

        return view('livewire.admin.listeners.index', ['listeners' => $listeners])
            ->layout('layouts.app', ['header' => $this->pageHeader(__('Слушатели'))]);
    }
}
