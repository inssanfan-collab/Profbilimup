<?php

namespace App\Livewire\Listener\Certificates;

use App\Livewire\Concerns\HasPageHeader;
use App\Models\Certificate;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Index extends Component
{
    use HasPageHeader;

    public function render(): View
    {
        $certificates = Certificate::query()
            ->whereHas('courseAssignment', fn ($q) => $q->where('listener_id', auth()->id()))
            ->with('courseAssignment.course')
            ->latest('issued_at')
            ->get();

        return view('livewire.listener.certificates.index', ['certificates' => $certificates])
            ->layout('layouts.app', ['header' => $this->pageHeader(__('Мои сертификаты'))]);
    }
}
