<?php

namespace App\Notifications;

use App\Models\PostCoursePlan;
use Illuminate\Notifications\Notification;

class PostCoursePlanSubmittedNotification extends Notification
{
    public function __construct(private readonly PostCoursePlan $plan)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $assignment = $this->plan->courseAssignment;

        return [
            'message' => __('Слушатель :listener сдал индивидуальный план посткурсового сопровождения («:course»)', [
                'listener' => $assignment->listener->listenerProfile?->full_name ?? $assignment->listener->name,
                'course' => $assignment->course->title,
            ]),
            'url' => route('admin.post-course-support.show', $assignment),
        ];
    }
}
