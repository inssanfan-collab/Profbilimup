@props(['icon' => 'inbox'])

<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center gap-2 py-10 text-center']) }}>
    <x-app-icon :name="$icon" class="h-8 w-8 text-gray-300" />
    <p class="text-sm text-gray-400">{{ $slot }}</p>
</div>
