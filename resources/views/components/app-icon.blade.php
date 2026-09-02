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
];

$icon = $map[$name] ?? 'question-mark-circle';
@endphp

{{ svg('heroicon-o-'.$icon, $attributes->get('class', ''), $attributes->except('class')->getAttributes()) }}
