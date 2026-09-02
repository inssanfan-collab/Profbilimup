<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-4">
        @forelse ($certificates as $certificate)
            <x-card class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-3 min-w-0">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-700">
                        <x-app-icon name="certificate" class="h-5 w-5" />
                    </span>
                    <div class="min-w-0">
                        <p class="font-medium text-gray-800 truncate">{{ $certificate->courseAssignment->course->title }}</p>
                        <p class="text-sm text-gray-500">
                            {{ $certificate->typeLabel() }}
                            · № {{ $certificate->certificate_number }}
                            · {{ $certificate->issued_at->format('d.m.Y') }}
                        </p>
                    </div>
                </div>
                <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($certificate->pdf_path) }}" target="_blank"
                    class="inline-flex items-center shrink-0 px-4 py-2.5 bg-blue-700 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-blue-800">
                    {{ __('Скачать PDF') }}
                </a>
            </x-card>
        @empty
            <x-card>
                <x-empty-state icon="certificate">{{ __('У вас пока нет сертификатов.') }}</x-empty-state>
            </x-card>
        @endforelse
    </div>
</div>
