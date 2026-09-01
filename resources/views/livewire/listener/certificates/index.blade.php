<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-4">
        @forelse ($certificates as $certificate)
            <div class="bg-white shadow-sm sm:rounded-lg p-6 flex items-center justify-between">
                <div>
                    <p class="font-medium text-gray-800">{{ $certificate->courseAssignment->course->title }}</p>
                    <p class="text-sm text-gray-500">
                        {{ $certificate->type === \App\Enums\CertificateType::Certificate ? __('Сертификат') : __('Справка о прослушивании') }}
                        · № {{ $certificate->certificate_number }}
                        · {{ $certificate->issued_at->format('d.m.Y') }}
                    </p>
                </div>
                <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($certificate->pdf_path) }}" target="_blank"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    {{ __('Скачать PDF') }}
                </a>
            </div>
        @empty
            <div class="bg-white shadow-sm sm:rounded-lg p-6 text-gray-500">
                {{ __('У вас пока нет сертификатов.') }}
            </div>
        @endforelse
    </div>
</div>
