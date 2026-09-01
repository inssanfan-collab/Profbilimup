<?php

namespace App\Livewire\Admin\Courses;

use App\Enums\UserRole;
use App\Livewire\Concerns\HasPageHeader;
use App\Models\Course;
use App\Models\User;
use App\Services\ProgressService;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Assign extends Component
{
    use HasPageHeader;

    #[Locked]
    public Course $course;

    public ?int $listenerId = null;

    public string $deadline = '';

    public function mount(Course $course): void
    {
        $this->course = $course;
    }

    public function assign(ProgressService $progressService): void
    {
        $validated = $this->validate([
            'listenerId' => [
                'required',
                'integer',
                Rule::exists('users', 'id')->where('role', UserRole::Listener->value),
                Rule::unique('course_assignments', 'listener_id')->where('course_id', $this->course->id),
            ],
            'deadline' => ['nullable', 'date', 'after_or_equal:today'],
        ], [
            'listenerId.unique' => __('Этот курс уже назначен выбранному слушателю.'),
        ]);

        $listener = User::where('role', UserRole::Listener)->findOrFail($validated['listenerId']);

        $progressService->assignCourse(
            $this->course,
            $listener,
            auth()->user(),
            $validated['deadline'] !== '' ? \Illuminate\Support\Carbon::parse($validated['deadline']) : null,
        );

        $this->reset('listenerId', 'deadline');
    }

    public function render(): View
    {
        $assignedListenerIds = $this->course->assignments()->pluck('listener_id');

        $availableListeners = User::query()
            ->where('role', UserRole::Listener)
            ->whereNotIn('id', $assignedListenerIds)
            ->orderBy('name')
            ->get();

        $assignments = $this->course->assignments()->with('listener.listenerProfile')->latest()->get();

        return view('livewire.admin.courses.assign', [
            'availableListeners' => $availableListeners,
            'assignments' => $assignments,
        ])->layout('layouts.app', ['header' => $this->pageHeader(__('Назначение курса: :title', ['title' => $this->course->title]))]);
    }
}
