<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <a href="{{ route('listener.courses.show', $assignment) }}" wire:navigate class="text-sm text-indigo-600 hover:text-indigo-900">
            &larr; {{ __('Назад к курсу') }}
        </a>

        <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-6">
            @if ($lesson->videoEmbedUrl())
                <div class="aspect-video">
                    <iframe src="{{ $lesson->videoEmbedUrl() }}" class="w-full h-full rounded-md" allowfullscreen></iframe>
                </div>
            @endif

            @if ($lesson->content_html)
                <div class="prose max-w-none">{!! $lesson->content_html !!}</div>
            @endif

            @if ($files->isNotEmpty())
                <div>
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">{{ __('Материалы') }}</h4>
                    <ul class="space-y-1">
                        @foreach ($files as $file)
                            <li>
                                <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($file->path) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 text-sm">
                                    {{ $file->original_name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="pt-4 border-t border-gray-100">
                @if ($isCompleted)
                    <span class="text-green-600 font-medium">✓ {{ __('Урок завершён') }}</span>
                @else
                    <button wire:click="complete" type="button"
                        class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                        {{ __('Завершить урок') }}
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
