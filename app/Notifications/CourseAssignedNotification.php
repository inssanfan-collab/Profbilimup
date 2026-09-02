<?php

namespace App\Notifications;

use App\Models\CourseAssignment;
use Illuminate\Notifications\Notification;

class CourseAssignedNotification extends Notification
{
    public function __construct(private readonly CourseAssignment $assignment)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => __('Вам назначен курс «:title»', ['title' => $this->assignment->course->title]),
            'url' => route('listener.courses.show', $this->assignment),
        ];
    }
}
