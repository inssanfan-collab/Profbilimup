<?php

namespace App\Livewire\Listener;

use App\Enums\VideoMeetingStatus;
use App\Livewire\Concerns\HasPageHeader;
use App\Models\CourseAssignment;
use App\Models\VideoMeeting;
use App\Services\ProgressService;
use App\Services\VideoConferenceService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class CoursePlayer extends Component
{
    use HasPageHeader;

    #[Locked]
    public CourseAssignment $assignment;

    public function mount(CourseAssignment $assignment): void
    {
        abort_unless($assignment->listener_id === auth()->id(), 403);

        $this->assignment = $assignment;
    }

    public function acceptAgreement(ProgressService $progressService): void
    {
        abort_if($this->assignment->isOverdue(), 403);

        $progressService->acceptAgreement($this->assignment);
    }

    public function joinVideoMeeting(VideoMeeting $meeting, VideoConferenceService $videoConferenceService)
    {
        abort_unless($meeting->course_id === $this->assignment->course_id, 403);
        abort_unless($meeting->status === VideoMeetingStatus::Scheduled, 403);
        abort_if($this->assignment->isOverdue(), 403);

        $fullName = auth()->user()->listenerProfile?->full_name ?? auth()->user()->name;
        $url = $videoConferenceService->joinUrl($meeting->external_meeting_id, $meeting->attendee_password, $fullName);

        return $this->redirect($url);
    }

    public function render(): View
    {
        $modules = $this->assignment->course->modules()->with('lessons')->get();

        $progressByLessonId = $this->assignment->lessonProgress()->get()->keyBy('lesson_id');

        $videoMeetings = $this->assignment->course->videoMeetings()
            ->where('status', VideoMeetingStatus::Scheduled)
            ->orderBy('starts_at')
            ->get();

        return view('livewire.listener.course-player', [
            'modules' => $modules,
            'progressByLessonId' => $progressByLessonId,
            'videoMeetings' => $videoMeetings,
        ])->layout('layouts.app', ['header' => $this->pageHeader($this->assignment->course->title)]);
    }
}
