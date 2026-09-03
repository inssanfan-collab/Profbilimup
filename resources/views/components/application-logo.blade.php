@props(['variant' => 'mark'])

@php
    $src = $variant === 'full' ? 'images/logo-profbilimup.png' : 'images/logo-mark.png';
@endphp

<img src="{{ asset($src) }}"
     alt="{{ config('app.name', 'ProfBilimUP') }}"
     {{ $attributes }}
     style="object-fit: contain; user-select: none;"
     draggable="false" />
