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
        $isCertificate = $this->certificate->type === CertificateType::Certificate;

        return [
            'message' => $isCertificate
                ? __('Вам выдан сертификат № :number', ['number' => $this->certificate->certificate_number])
                : __('Вам выдана справка о прослушивании № :number', ['number' => $this->certificate->certificate_number]),
            'url' => route('listener.certificates.index'),
        ];
    }
}
