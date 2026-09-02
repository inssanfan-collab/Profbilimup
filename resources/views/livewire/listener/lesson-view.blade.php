<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <a href="{{ route('listener.courses.show', $assignment) }}" wire:navigate class="text-sm text-blue-700 hover:text-blue-900">
            &larr; {{ __('Назад к курсу') }}
        </a>

        <x-card class="space-y-6">
            @if ($lesson->videoEmbedUrl())
                <div class="aspect-video">
                    <iframe src="{{ $lesson->videoEmbedUrl() }}" class="w-full h-full rounded-lg" allowfullscreen></iframe>
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
                                <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($file->path) }}" target="_blank" class="text-blue-700 hover:text-blue-900 text-sm">
                                    {{ $file->original_name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="pt-4 border-t border-gray-100">
                @if ($isCompleted)
                    <span class="inline-flex items-center gap-1.5 text-green-700 font-medium">
                        <x-app-icon name="check" class="h-5 w-5" />
                        {{ __('Урок завершён') }}
                    </span>
                @elseif ($lesson->test)
                    <a href="{{ route('listener.tests.show', [$assignment, $lesson]) }}" wire:navigate
                        class="inline-flex items-center px-4 py-2.5 bg-blue-700 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-blue-800">
                        {{ __('Пройти тест') }}
                    </a>
                @else
                    <x-primary-button wire:click="complete" type="button">
                        {{ __('Завершить урок') }}
                    </x-primary-button>
                @endif
            </div>
        </x-card>
    </div>
</div>
