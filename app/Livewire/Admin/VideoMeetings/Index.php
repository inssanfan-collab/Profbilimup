<?php

namespace App\Livewire\Admin\VideoMeetings;

use App\Enums\VideoMeetingStatus;
use App\Livewire\Concerns\HasPageHeader;
use App\Models\Course;
use App\Models\VideoMeeting;
use App\Services\VideoConferenceService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Attributes\Locked;
use Livewire\Component;
use RuntimeException;

class Index extends Component
{
    use HasPageHeader;

    #[Locked]
    public Course $course;

    public string $name = '';

    public string $startsAt = '';

    public function mount(Course $course): void
    {
        $this->course = $course;
        $this->name = __('Видеоурок: :title', ['title' => $course->title]);
    }

    public function schedule(VideoConferenceService $videoConferenceService): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'startsAt' => ['nullable', 'date'],
        ]);

        $externalMeetingId = (string) Str::uuid();
        $moderatorPassword = Str::random(16);
        $attendeePassword = Str::random(16);

        try {
            $videoConferenceService->createMeeting($externalMeetingId, $validated['name'], $moderatorPassword, $attendeePassword);
        } catch (RuntimeException $e) {
            Log::warning('video_meeting.schedule_failed', ['course_id' => $this->course->id]);
            $this->addError('name', __('Не удалось создать видеовстречу. Проверьте подключение к серверу видеосвязи и попробуйте снова.'));

            return;
        }

        $this->course->videoMeetings()->create([
            'created_by' => auth()->id(),
            'external_meeting_id' => $externalMeetingId,
            'name' => $validated['name'],
            'moderator_password' => $moderatorPassword,
            'attendee_password' => $attendeePassword,
            'status' => VideoMeetingStatus::Scheduled,
            'starts_at' => $validated['startsAt'] !== '' ? $validated['startsAt'] : null,
        ]);

        $this->reset('startsAt');
        $this->name = __('Видеоурок: :title', ['title' => $this->course->title]);
    }

    public function joinAsModerator(VideoMeeting $meeting, VideoConferenceService $videoConferenceService)
    {
        abort_unless($meeting->course_id === $this->course->id, 403);

        $url = $videoConferenceService->joinUrl($meeting->external_meeting_id, $meeting->moderator_password, auth()->user()->name);

        return $this->redirect($url);
    }

    public function end(VideoMeeting $meeting, VideoConferenceService $videoConferenceService): void
    {
        abort_unless($meeting->course_id === $this->course->id, 403);

        $videoConferenceService->endMeeting($meeting->external_meeting_id, $meeting->moderator_password);

        $meeting->update([
            'status' => VideoMeetingStatus::Ended,
            'ended_at' => now(),
        ]);
    }

    public function render(): View
    {
        $meetings = $this->course->videoMeetings()->latest()->get();

        return view('livewire.admin.video-meetings.index', ['meetings' => $meetings])
            ->layout('layouts.app', ['header' => $this->pageHeader(__('Видеоуроки: :title', ['title' => $this->course->title]))]);
    }
}
