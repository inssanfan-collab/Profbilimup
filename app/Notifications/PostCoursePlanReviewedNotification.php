<?php

namespace App\Notifications;

use App\Enums\PostCourseSupportStatus;
use App\Models\PostCoursePlan;
use Illuminate\Notifications\Notification;

class PostCoursePlanReviewedNotification extends Notification
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
            'message' => $this->plan->status === PostCourseSupportStatus::Approved
                ? __('Ваш индивидуальный план посткурсового сопровождения одобрен («:course»)', ['course' => $assignment->course->title])
                : __('Ваш индивидуальный план посткурсового сопровождения отправлен на доработку («:course»)', ['course' => $assignment->course->title]),
            'url' => route('listener.post-course-support.show', $assignment),
        ];
    }
}
