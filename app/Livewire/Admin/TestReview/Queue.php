<?php

namespace App\Livewire\Admin\TestReview;

use App\Enums\CuratorPermission;
use App\Livewire\Concerns\HasPageHeader;
use App\Services\TestGradingService;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Queue extends Component
{
    use HasPageHeader;

    public function mount(): void
    {
        abort_unless(auth()->user()->hasPermission(CuratorPermission::TestReview), 403);
    }

    public function render(TestGradingService $testGradingService): View
    {
        return view('livewire.admin.test-review.queue', [
            'attempts' => $testGradingService->pendingReview(auth()->user()->scopedCourseIds()),
        ])->layout('layouts.app', ['header' => $this->pageHeader(__('Проверка текстовых ответов'))]);
    }
}
