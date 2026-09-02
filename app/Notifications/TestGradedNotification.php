<?php

namespace App\Notifications;

use App\Models\TestAttempt;
use Illuminate\Notifications\Notification;

class TestGradedNotification extends Notification
{
    public function __construct(private readonly TestAttempt $attempt)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $lesson = $this->attempt->test->lesson;
        $assignment = $lesson->courseModule->course->assignments()
            ->where('listener_id', $this->attempt->listener_id)
            ->first();

        return [
            'message' => $this->attempt->passed
                ? __('Тест «:test» пройден: :score%', ['test' => $this->attempt->test->title, 'score' => $this->attempt->score_percent])
                : __('Тест «:test» не пройден: :score%', ['test' => $this->attempt->test->title, 'score' => $this->attempt->score_percent]),
            'url' => $assignment ? route('listener.lessons.show', [$assignment, $lesson]) : route('listener.dashboard'),
        ];
    }
}
