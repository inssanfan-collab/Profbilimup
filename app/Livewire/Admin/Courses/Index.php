<?php

namespace App\Livewire\Admin\Courses;

use App\Livewire\Concerns\HasPageHeader;
use App\Models\Course;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use HasPageHeader, WithPagination;

    public function render(): View
    {
        $courses = Course::query()
            ->withCount('modules')
            ->latest()
            ->paginate(15);

        return view('livewire.admin.courses.index', ['courses' => $courses])
            ->layout('layouts.app', ['header' => $this->pageHeader(__('Курсы'))]);
    }
}
