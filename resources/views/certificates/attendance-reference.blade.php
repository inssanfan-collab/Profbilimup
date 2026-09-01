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
    .title { text-align: center; font-size: 22px; font-weight: bold; margin: 24px 0; }
    .body-text { line-height: 1.8; margin: 0 20px 30px 20px; }
    .meta { margin-top: 40px; }
    .signature-line { border-bottom: 1px solid #1f2933; width: 220px; display: inline-block; margin: 0 8px; }
    .seal-note { color: #6b7280; font-size: 11px; }
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

    <div class="title">СПРАВКА О ПРОСЛУШИВАНИИ КУРСА</div>

    <div class="body-text">
        Выдана <strong>{{ $listener->listenerProfile?->full_name ?? $listener->name }}</strong> в том, что он(а)
        прослушал(а) курс на базе {{ $organization->name_ru ?: 'учебного центра' }}
        в рамках курса повышения квалификации на тему «{{ $course->title }}»
        в объёме {{ $course->academic_hours }} академических часов, без прохождения итогового оценивания.
    </div>

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
