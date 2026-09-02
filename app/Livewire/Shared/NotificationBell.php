<?php

namespace App\Livewire\Shared;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class NotificationBell extends Component
{
    public function markAsRead(string $notificationId)
    {
        $notification = auth()->user()->notifications()->whereKey($notificationId)->first();

        abort_unless($notification, 404);

        $notification->markAsRead();

        return $this->redirect($notification->data['url'] ?? route('dashboard'));
    }

    public function markAllAsRead(): void
    {
        auth()->user()->unreadNotifications->markAsRead();
    }

    public function render(): View
    {
        $notifications = auth()->user()->notifications()->latest()->limit(10)->get();

        return view('livewire.shared.notification-bell', [
            'notifications' => $notifications,
            'unreadCount' => auth()->user()->unreadNotifications()->count(),
        ]);
    }
}
