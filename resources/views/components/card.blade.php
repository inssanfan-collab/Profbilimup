@props(['title' => null, 'flat' => false])

<div {{ $attributes->merge(['class' => 'bg-white rounded-xl border border-gray-100 p-6 ' . ($flat ? '' : 'shadow-card')]) }}>
    @if ($title || isset($actions))
        <div class="flex items-center justify-between gap-4 mb-4">
            @if ($title)
                <h3 class="text-base font-semibold text-gray-900">{{ $title }}</h3>
            @endif

            @isset($actions)
                <div class="flex items-center gap-2">{{ $actions }}</div>
            @endisset
        </div>
    @endif

    {{ $slot }}
</div>
