<?php

namespace App\Notifications;

use App\Models\VideoMeeting;
use Illuminate\Notifications\Notification;

class VideoMeetingScheduledNotification extends Notification
{
    public function __construct(private readonly VideoMeeting $meeting)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $assignment = $this->meeting->course->assignments()
            ->where('listener_id', $notifiable->id)
            ->first();

        return [
            'message' => __('Запланирован видеоурок «:name» по курсу «:course»', [
                'name' => $this->meeting->name,
                'course' => $this->meeting->course->title,
            ]),
            'url' => $assignment ? route('listener.courses.show', $assignment) : route('listener.dashboard'),
        ];
    }
}
