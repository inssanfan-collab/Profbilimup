<?php

namespace App\Livewire\Listener\PostCourseSupport;

use App\Enums\AssignmentStatus;
use App\Enums\PostCourseEventType;
use App\Livewire\Concerns\HasPageHeader;
use App\Models\CourseAssignment;
use App\Services\PostCourseSupportService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Show extends Component
{
    use HasPageHeader;

    #[Locked]
    public CourseAssignment $assignment;

    public string $planContent = '';

    public string $newLessonPlanTitle = '';

    public string $newLessonPlanContent = '';

    public string $reportContent = '';

    public string $diagnosticBefore = '';

    public string $diagnosticAfter = '';

    public string $eventType = 'methodological_event';

    public string $eventTitle = '';

    public string $eventDate = '';

    public string $eventDescription = '';

    public function mount(CourseAssignment $assignment): void
    {
        abort_unless($assignment->listener_id === auth()->id(), 403);
        abort_unless($assignment->status === AssignmentStatus::Completed, 403);

        $this->assignment = $assignment;
        $this->planContent = $assignment->postCoursePlan?->content ?? '';
    }

    public function submitPlan(PostCourseSupportService $service): void
    {
        $this->validate(['planContent' => ['required', 'string']]);

        $service->submitPlan($this->assignment, $this->planContent);
    }

    public function addLessonPlan(PostCourseSupportService $service): void
    {
        $validated = $this->validate([
            'newLessonPlanTitle' => ['required', 'string', 'max:255'],
            'newLessonPlanContent' => ['required', 'string'],
        ]);

        $service->addLessonPlan($this->assignment->postCoursePlan, $validated['newLessonPlanTitle'], $validated['newLessonPlanContent']);

        $this->reset('newLessonPlanTitle', 'newLessonPlanContent');
    }

    public function addReport(PostCourseSupportService $service): void
    {
        $validated = $this->validate([
            'reportContent' => ['required', 'string'],
            'diagnosticBefore' => ['nullable', 'string'],
            'diagnosticAfter' => ['nullable', 'string'],
        ]);

        $service->addReport(
            $this->assignment,
            $validated['reportContent'],
            $validated['diagnosticBefore'] ?: null,
            $validated['diagnosticAfter'] ?: null,
        );

        $this->reset('reportContent', 'diagnosticBefore', 'diagnosticAfter');
    }

    public function addEvent(PostCourseSupportService $service): void
    {
        $validated = $this->validate([
            'eventType' => ['required', 'in:methodological_event,conference,seminar,other'],
            'eventTitle' => ['required', 'string', 'max:255'],
            'eventDate' => ['required', 'date'],
            'eventDescription' => ['nullable', 'string'],
        ]);

        $service->addEvent(
            $this->assignment,
            $validated['eventType'],
            $validated['eventTitle'],
            \Illuminate\Support\Carbon::parse($validated['eventDate']),
            $validated['eventDescription'] ?: null,
        );

        $this->reset('eventTitle', 'eventDate', 'eventDescription');
    }

    public function render(): View
    {
        $plan = $this->assignment->postCoursePlan()->with('lessonPlans')->first();

        return view('livewire.listener.post-course-support.show', [
            'plan' => $plan,
            'reports' => $this->assignment->postCourseReports()->latest()->get(),
            'events' => $this->assignment->postCourseEvents()->latest('event_date')->get(),
            'eventTypes' => PostCourseEventType::cases(),
        ])->layout('layouts.app', ['header' => $this->pageHeader(__('Посткурсовое сопровождение: :title', ['title' => $this->assignment->course->title]))]);
    }
}
