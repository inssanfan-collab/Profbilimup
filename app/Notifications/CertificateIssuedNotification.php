<?php

namespace App\Notifications;

use App\Enums\CertificateType;
use App\Models\Certificate;
use Illuminate\Notifications\Notification;

class CertificateIssuedNotification extends Notification
{
    public function __construct(private readonly Certificate $certificate)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $message = match ($this->certificate->type) {
            CertificateType::Certificate => __('Вам выдан сертификат № :number', ['number' => $this->certificate->certificate_number]),
            CertificateType::AttendanceReference => __('Вам выдана справка о прослушивании № :number', ['number' => $this->certificate->certificate_number]),
            CertificateType::PostCourseReference => __('Вам выдана справка о прохождении посткурсового сопровождения № :number', ['number' => $this->certificate->certificate_number]),
        };

        return [
            'message' => $message,
            'url' => route('listener.certificates.index'),
        ];
    }
}
