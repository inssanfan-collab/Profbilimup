<div class="py-12" x-data>
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.courses.builder', $lesson->courseModule->course_id) }}" wire:navigate class="text-sm text-blue-700 hover:text-blue-900">
                &larr; {{ __('Назад к структуре курса') }}
            </a>
            <a href="{{ route('admin.lessons.test', $lesson) }}" wire:navigate class="inline-flex items-center gap-1 text-sm text-blue-700 hover:text-blue-900">
                {{ $lesson->test ? __('Редактировать тест') : __('Добавить тест') }}
                <x-app-icon name="chevron-right" class="h-4 w-4" />
            </a>
        </div>

        <x-card class="space-y-6">
            <form wire:submit="save" class="space-y-6">
                <div>
                    <x-input-label for="title" :value="__('Название урока')" />
                    <x-text-input wire:model="title" id="title" class="block mt-1 w-full" />
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="video_url" :value="__('Ссылка на видео (YouTube, доступ по ссылке)')" />
                    <x-text-input wire:model.live="video_url" id="video_url" class="block mt-1 w-full" placeholder="https://youtu.be/..." />
                    <x-input-error :messages="$errors->get('video_url')" class="mt-2" />

                    @if ($this->videoEmbedUrl())
                        <div class="mt-3 aspect-video max-w-md">
                            <iframe src="{{ $this->videoEmbedUrl() }}" class="w-full h-full rounded-lg" allowfullscreen></iframe>
                        </div>
                    @endif
                </div>

                <div>
                    <x-input-label for="lesson-content" :value="__('Текстовый материал')" />
                    <div wire:ignore>
                        <input id="lesson-content" type="hidden" value="{{ $content_html }}">
                        <trix-editor input="lesson-content" x-on:trix-change="$wire.set('content_html', $event.target.value, false)" class="mt-1"></trix-editor>
                    </div>
                    <x-input-error :messages="$errors->get('content_html')" class="mt-2" />
                </div>

                <div class="flex items-center gap-4">
                    <x-primary-button>{{ __('Сохранить') }}</x-primary-button>
                    <span x-data="{ shown: false }" x-on:lesson-saved.window="shown = true; setTimeout(() => shown = false, 2000)" x-show="shown" x-cloak class="text-sm text-green-600">{{ __('Сохранено') }}</span>
                </div>
            </form>

            <div class="border-t border-gray-100 pt-6">
                <x-input-label :value="__('Прикреплённые файлы (PDF, презентации, doc)')" />

                <ul class="mt-2 divide-y divide-gray-100">
                    @forelse ($files as $file)
                        <li class="py-2 flex items-center justify-between text-sm">
                            <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($file->path) }}" target="_blank" class="text-blue-700 hover:text-blue-900">{{ $file->original_name }}</a>
                            <button wire:click="deleteFile({{ $file->id }})" wire:confirm="{{ __('Удалить файл?') }}" type="button" class="text-red-600 hover:text-red-900">{{ __('Удалить') }}</button>
                        </li>
                    @empty
                        <li class="py-2 text-sm text-gray-400">{{ __('Файлов пока нет.') }}</li>
                    @endforelse
                </ul>

                <form wire:submit="uploadFiles" class="mt-4 flex items-center gap-3">
                    <input type="file" wire:model="newFiles" multiple class="text-sm">
                    <x-primary-button type="submit">{{ __('Загрузить') }}</x-primary-button>
                    <span wire:loading wire:target="newFiles" class="text-sm text-gray-500">{{ __('Загрузка...') }}</span>
                </form>
                <x-input-error :messages="$errors->get('newFiles.*')" class="mt-2" />
            </div>
        </x-card>
    </div>
</div>
