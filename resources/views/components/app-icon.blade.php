@props(['name'])

@php
$map = [
    'courses' => 'book-open',
    'users' => 'users',
    'check' => 'check-circle',
    'lock' => 'lock-closed',
    'clock' => 'clock',
    'video' => 'video-camera',
    'document' => 'document-text',
    'chart' => 'chart-bar',
    'events' => 'megaphone',
    'warning' => 'exclamation-triangle',
    'inbox' => 'inbox',
    'certificate' => 'academic-cap',
    'calendar' => 'calendar-days',
    'chevron-right' => 'chevron-right',
    'play' => 'play-circle',
    'plans' => 'clipboard-document-check',
    'organization' => 'building-office',
    'bell' => 'bell',
    'shield-check' => 'shield-check',
    'qr-code' => 'qr-code',
    'arrow-right' => 'arrow-right',
    'arrow-long-right' => 'arrow-long-right',
    'sparkles' => 'sparkles',
    'user-group' => 'user-group',
    'search' => 'magnifying-glass',
    'verified' => 'check-badge',
    'identification' => 'identification',
    'language' => 'globe-alt',
];

$icon = $map[$name] ?? 'question-mark-circle';
@endphp

{{ svg('heroicon-o-'.$icon, $attributes->get('class', ''), $attributes->except('class')->getAttributes()) }}
