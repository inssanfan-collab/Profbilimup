<?php

namespace App\Livewire\Admin\Courses;

use App\Enums\CuratorPermission;
use App\Livewire\Concerns\HasPageHeader;
use App\Models\Course;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use HasPageHeader, WithPagination;

    public function mount(): void
    {
        // Список курсов — общая точка входа для всех прав, «привязанных» к конкретному
        // курсу (структура курса и видеоуроки); внутри уже видны только те действия
        // (строки таблицы/ссылки), на которые у куратора реально есть право.
        abort_unless(
            auth()->user()->hasPermission(CuratorPermission::Courses)
                || auth()->user()->hasPermission(CuratorPermission::VideoMeetings),
            403,
        );
    }

    public function render(): View
    {
        $courseIds = auth()->user()->scopedCourseIds();

        $courses = Course::query()
            ->when($courseIds !== null, fn ($query) => $query->whereIn('id', $courseIds))
            ->withCount('modules')
            ->latest()
            ->paginate(15);

        return view('livewire.admin.courses.index', ['courses' => $courses])
            ->layout('layouts.app', ['header' => $this->pageHeader(__('Курсы'))]);
    }
}
