<?php

namespace App\Livewire\Admin\PostCourseSupport;

use App\Enums\PostCourseSupportStatus;
use App\Livewire\Concerns\HasPageHeader;
use App\Models\CourseAssignment;
use App\Models\PostCourseLessonPlan;
use App\Services\CertificateService;
use App\Services\PostCourseSupportService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Show extends Component
{
    use HasPageHeader;

    #[Locked]
    public CourseAssignment $assignment;

    public string $reviewNote = '';

    public array $feedbackText = [];

    public function mount(CourseAssignment $assignment): void
    {
        $this->assignment = $assignment;
    }

    public function approvePlan(PostCourseSupportService $service): void
    {
        $service->reviewPlan($this->assignment->postCoursePlan, PostCourseSupportStatus::Approved, auth()->user());
        $this->reset('reviewNote');
    }

    public function rejectPlan(PostCourseSupportService $service): void
    {
        $this->validate(['reviewNote' => ['required', 'string']]);

        $service->reviewPlan($this->assignment->postCoursePlan, PostCourseSupportStatus::Rejected, auth()->user(), $this->reviewNote);
        $this->reset('reviewNote');
    }

    public function giveFeedback(PostCourseLessonPlan $lessonPlan, PostCourseSupportService $service): void
    {
        abort_unless($lessonPlan->postCoursePlan->course_assignment_id === $this->assignment->id, 403);

        $text = trim($this->feedbackText[$lessonPlan->id] ?? '');

        if ($text === '') {
            return;
        }

        $service->giveLessonPlanFeedback($lessonPlan, $text, auth()->user());
        $this->feedbackText[$lessonPlan->id] = '';
    }

    public function issueReference(PostCourseSupportService $service, CertificateService $certificateService): void
    {
        $service->issueReference($this->assignment, $certificateService);
    }

    public function render(): View
    {
        $plan = $this->assignment->postCoursePlan()->with('lessonPlans')->first();

        return view('livewire.admin.post-course-support.show', [
            'plan' => $plan,
            'reports' => $this->assignment->postCourseReports()->latest()->get(),
            'events' => $this->assignment->postCourseEvents()->latest('event_date')->get(),
            'reference' => $this->assignment->postCourseReference,
        ])->layout('layouts.app', ['header' => $this->pageHeader($this->assignment->listener->listenerProfile?->full_name ?? $this->assignment->listener->name)]);
    }
}
