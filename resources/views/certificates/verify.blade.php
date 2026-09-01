<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Проверка сертификата — BilimUP</title>
    @vite(['resources/css/app.css'])
</head>
<body class="font-sans antialiased bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full mx-4 bg-white shadow-sm rounded-lg p-8">
        @if ($certificate)
            <div class="text-green-600 font-semibold text-lg mb-4">✓ Документ подлинный</div>
            <dl class="space-y-2 text-sm">
                <div>
                    <dt class="text-gray-500">ФИО</dt>
                    <dd class="font-medium">{{ $certificate->courseAssignment->listener->listenerProfile?->full_name ?? $certificate->courseAssignment->listener->name }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Курс</dt>
                    <dd class="font-medium">{{ $certificate->courseAssignment->course->title }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Тип документа</dt>
                    <dd class="font-medium">{{ $certificate->type === \App\Enums\CertificateType::Certificate ? 'Сертификат' : 'Справка о прослушивании' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Регистрационный номер</dt>
                    <dd class="font-medium">{{ $certificate->certificate_number }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Дата выдачи</dt>
                    <dd class="font-medium">{{ $certificate->issued_at->format('d.m.Y') }}</dd>
                </div>
                @if ($certificate->valid_until)
                    <div>
                        <dt class="text-gray-500">Действителен до</dt>
                        <dd class="font-medium">{{ $certificate->valid_until->format('d.m.Y') }}</dd>
                    </div>
                @endif
            </dl>
        @else
            <div class="text-red-600 font-semibold text-lg">✗ Документ не найден</div>
            <p class="text-sm text-gray-500 mt-2">Проверьте ссылку или обратитесь в организацию, выдавшую документ.</p>
        @endif
    </div>
</body>
</html>
