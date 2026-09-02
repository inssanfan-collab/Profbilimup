<?php

namespace App\Notifications;

use App\Models\PostCourseLessonPlan;
use Illuminate\Notifications\Notification;

class LessonPlanFeedbackNotification extends Notification
{
    public function __construct(private readonly PostCourseLessonPlan $lessonPlan)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $assignment = $this->lessonPlan->postCoursePlan->courseAssignment;

        return [
            'message' => __('Получена обратная связь по плану урока «:title»', ['title' => $this->lessonPlan->title]),
            'url' => route('listener.post-course-support.show', $assignment),
        ];
    }
}
