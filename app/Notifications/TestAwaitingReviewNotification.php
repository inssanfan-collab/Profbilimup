<?php

namespace App\Notifications;

use App\Models\TestAttempt;
use Illuminate\Notifications\Notification;

class TestAwaitingReviewNotification extends Notification
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
        return [
            'message' => __('Слушатель :listener сдал текстовые ответы на проверку («:test»)', [
                'listener' => $this->attempt->listener->listenerProfile?->full_name ?? $this->attempt->listener->name,
                'test' => $this->attempt->test->title,
            ]),
            'url' => route('admin.test-review.show', $this->attempt),
        ];
    }
}
