<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Проверка сертификата — {{ config('app.name', 'ProfBilimUP') }}</title>
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('images/favicon-192.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/favicon-512.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=golos-text:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css'])
</head>
<body class="font-sans antialiased bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full mx-4 bg-white rounded-xl border border-gray-100 shadow-card p-8">
        <div class="flex items-center gap-2.5 mb-6">
            <x-application-logo class="h-9 w-9" />
            <span class="text-lg font-bold text-gray-900">{{ config('app.name', 'ProfBilimUP') }}</span>
        </div>

        @if ($certificate)
            <div class="flex items-center gap-2 text-green-700 font-semibold text-lg mb-4">
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-green-50">
                    <x-app-icon name="check" class="h-5 w-5" />
                </span>
                Документ подлинный
            </div>
            <dl class="space-y-3 text-sm">
                <div>
                    <dt class="text-gray-500">ФИО</dt>
                    <dd class="font-medium text-gray-900">{{ $certificate->courseAssignment->listener->listenerProfile?->full_name ?? $certificate->courseAssignment->listener->name }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Курс</dt>
                    <dd class="font-medium text-gray-900">{{ $certificate->courseAssignment->course->title }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Тип документа</dt>
                    <dd class="font-medium text-gray-900">{{ $certificate->typeLabel() }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Регистрационный номер</dt>
                    <dd class="font-medium text-gray-900">{{ $certificate->certificate_number }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Дата выдачи</dt>
                    <dd class="font-medium text-gray-900">{{ $certificate->issued_at->format('d.m.Y') }}</dd>
                </div>
                @if ($certificate->valid_until)
                    <div>
                        <dt class="text-gray-500">Действителен до</dt>
                        <dd class="font-medium text-gray-900">{{ $certificate->valid_until->format('d.m.Y') }}</dd>
                    </div>
                @endif
            </dl>
        @else
            <div class="flex items-center gap-2 text-red-700 font-semibold text-lg">
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-red-50">
                    <x-app-icon name="warning" class="h-5 w-5" />
                </span>
                Документ не найден
            </div>
            <p class="text-sm text-gray-500 mt-2">Проверьте ссылку или обратитесь в организацию, выдавшую документ.</p>
        @endif
    </div>
</body>
</html>
