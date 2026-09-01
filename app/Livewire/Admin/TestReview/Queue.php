<?php

namespace App\Livewire\Admin\TestReview;

use App\Livewire\Concerns\HasPageHeader;
use App\Services\TestGradingService;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Queue extends Component
{
    use HasPageHeader;

    public function render(TestGradingService $testGradingService): View
    {
        return view('livewire.admin.test-review.queue', [
            'attempts' => $testGradingService->pendingReview(),
        ])->layout('layouts.app', ['header' => $this->pageHeader(__('Проверка текстовых ответов'))]);
    }
}
