<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    body { font-family: 'DejaVu Sans', sans-serif; color: #1f2933; font-size: 13px; }
    .page { padding: 20px; }
    .header { text-align: center; margin-bottom: 24px; }
    .header img { max-height: 70px; margin-bottom: 8px; }
    .org-name { font-size: 13px; font-weight: bold; }
    .title { text-align: center; font-size: 26px; font-weight: bold; letter-spacing: 4px; margin: 24px 0; }
    .body-text { text-align: center; line-height: 1.6; margin: 0 40px 30px 40px; }
    .body-text strong { font-size: 16px; }
    .meta { margin-top: 50px; }
    .meta table { width: 100%; border-collapse: collapse; }
    .meta td { padding: 4px 10px 4px 0; vertical-align: top; }
    .signature { display: block; }
    .signature-line { border-bottom: 1px solid #1f2933; width: 220px; display: inline-block; margin: 0 8px; }
    .footer { margin-top: 40px; }
    .qr { text-align: center; }
    .qr img { width: 90px; height: 90px; }
    .qr p { font-size: 9px; color: #6b7280; width: 90px; margin: 4px auto 0; }
    .seal-note { color: #6b7280; font-size: 11px; }
    .page-break { page-break-before: always; }
    table.grades { width: 100%; border-collapse: collapse; margin-top: 20px; }
    table.grades th, table.grades td { border: 1px solid #d1d5db; padding: 6px 10px; text-align: left; font-size: 12px; }
    table.grades th { background: #f3f4f6; }
</style>
</head>
<body>
<div class="page">
    <div class="header">
        @if ($organization->logo_path)
            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->path($organization->logo_path) }}">
        @endif
        <div class="org-name">{{ $organization->name_ru ?: 'Учебный центр' }}</div>
        @if ($organization->name_kk)
            <div class="org-name">{{ $organization->name_kk }}</div>
        @endif
    </div>

    <div class="title">СЕРТИФИКАТ</div>

    <div class="body-text">
        Настоящим подтверждает, что<br>
        <strong>{{ $listener->listenerProfile?->full_name ?? $listener->name }}</strong><br>
        прошёл(ла) курс повышения квалификации на тему «{{ $course->title }}»<br>
        в объёме {{ $course->academic_hours }} академических часов.
    </div>

    <div class="meta">
        <table>
            <tr>
                <td style="width: 45%;">
                    <span class="signature">
                        Руководитель организации
                        <span class="signature-line">&nbsp;</span>
                        {{ $certificate->director_full_name_snapshot ?? '' }}
                    </span>
                    <p class="seal-note">Место печати (при наличии)</p>
                </td>
                <td style="width: 35%;">
                    <p>Рег. номер: <strong>{{ $certificate->certificate_number }}</strong></p>
                    <p>Дата выдачи: {{ $certificate->issued_at->format('d.m.Y') }}</p>
                    <p>Действителен до: {{ $certificate->valid_until?->format('d.m.Y') }}</p>
                </td>
                <td style="width: 20%;">
                    <div class="qr">
                        <img src="{{ $qrDataUri }}">
                        <p>Проверка подлинности по QR-коду</p>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>

<div class="page-break"></div>

<div class="page">
    <div class="header">
        <div class="org-name">{{ $organization->name_ru ?: 'Учебный центр' }}</div>
    </div>

    <div class="title" style="font-size: 18px; letter-spacing: 1px;">ПРИЛОЖЕНИЕ К СЕРТИФИКАТУ</div>

    <p style="text-align: center;">
        <strong>{{ $listener->listenerProfile?->full_name ?? $listener->name }}</strong><br>
        за время обучения на курсах повышения квалификации показал(а) соответствующие знания и навыки по следующим модулям:
    </p>

    <table class="grades">
        <thead>
            <tr>
                <th>№</th>
                <th>Наименование модуля</th>
                <th>Оценка, %</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($moduleGrades as $index => $grade)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $grade->module_title_snapshot }}</td>
                    <td>{{ $grade->score_percent !== null ? $grade->score_percent.'%' : '—' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="meta">
        <p>
            Руководитель организации
            <span class="signature-line">&nbsp;</span>
            {{ $certificate->director_full_name_snapshot ?? '' }}
        </p>
        <p class="seal-note">Место печати (при наличии)</p>
        <p>Регистрационный номер: {{ $certificate->certificate_number }}</p>
        <p>Дата выдачи: {{ $certificate->issued_at->format('d.m.Y') }}</p>
    </div>
</div>
</body>
</html>
