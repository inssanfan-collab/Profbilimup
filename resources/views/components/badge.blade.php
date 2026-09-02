@props(['color' => 'gray'])

@php
$colors = [
    'gray' => 'bg-gray-100 text-gray-700',
    'blue' => 'bg-blue-50 text-blue-700',
    'green' => 'bg-green-50 text-green-700',
    'red' => 'bg-red-50 text-red-700',
    'amber' => 'bg-amber-50 text-amber-700',
];
$classes = $colors[$color] ?? $colors['gray'];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {$classes}"]) }}>
    {{ $slot }}
</span>
