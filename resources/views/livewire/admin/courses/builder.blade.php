<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <x-card class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 flex items-center gap-2">
                    <span>{{ __('Часов:') }} {{ $course->academic_hours }}</span>
                    @if ($course->status === \App\Enums\CourseStatus::Published)
                        <x-badge color="green">{{ __('Опубликован') }}</x-badge>
                    @else
                        <x-badge color="amber">{{ __('Черновик') }}</x-badge>
                    @endif
                </p>
                <div class="mt-1 flex items-center gap-3">
                    <a href="{{ route('admin.courses.edit', $course) }}" wire:navigate class="text-sm text-blue-700 hover:text-blue-900">{{ __('Изменить параметры курса') }}</a>
                    <a href="{{ route('admin.courses.assign', $course) }}" wire:navigate class="text-sm text-blue-700 hover:text-blue-900">{{ __('Назначить слушателям') }}</a>
                    <a href="{{ route('admin.video-meetings.index', $course) }}" wire:navigate class="text-sm text-blue-700 hover:text-blue-900">{{ __('Видеоуроки') }}</a>
                </div>
            </div>

            <div>
                @if ($course->status === \App\Enums\CourseStatus::Published)
                    <x-secondary-button wire:click="unpublish" type="button">
                        {{ __('Снять с публикации') }}
                    </x-secondary-button>
                @else
                    <x-primary-button wire:click="publish" type="button">
                        {{ __('Опубликовать') }}
                    </x-primary-button>
                @endif
                @error('publish') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </x-card>

        @foreach ($modules as $module)
            <x-card wire:key="module-{{ $module->id }}">
                <div class="flex items-center justify-between gap-4">
                    @if ($editingModuleId === $module->id)
                        <form wire:submit="saveModuleTitle" class="flex-1 flex items-center gap-2">
                            <x-text-input wire:model="editingModuleTitle" class="block w-full" autofocus />
                            <x-primary-button>{{ __('Сохранить') }}</x-primary-button>
                            <button type="button" wire:click="$set('editingModuleId', null)" class="text-sm text-gray-500">{{ __('Отмена') }}</button>
                        </form>
                    @else
                        <h3 class="text-lg font-semibold text-gray-800">{{ $module->title }}</h3>
                        <div class="flex items-center gap-3 text-sm">
                            <button wire:click="moveModuleUp({{ $module->id }})" type="button" class="text-gray-400 hover:text-gray-700" title="{{ __('Выше') }}">&uarr;</button>
                            <button wire:click="moveModuleDown({{ $module->id }})" type="button" class="text-gray-400 hover:text-gray-700" title="{{ __('Ниже') }}">&darr;</button>
                            <button wire:click="startEditingModule({{ $module->id }})" type="button" class="text-blue-700 hover:text-blue-900">{{ __('Переименовать') }}</button>
                            <button wire:click="deleteModule({{ $module->id }})" wire:confirm="{{ __('Удалить модуль вместе со всеми уроками?') }}" type="button" class="text-red-600 hover:text-red-900">{{ __('Удалить') }}</button>
                        </div>
                    @endif
                </div>

                <ul class="mt-4 divide-y divide-gray-100 border-t border-gray-100">
                    @forelse ($module->lessons as $lesson)
                        <li class="py-3 flex items-center justify-between gap-4" wire:key="lesson-{{ $lesson->id }}">
                            @if ($editingLessonId === $lesson->id)
                                <form wire:submit="saveLessonTitle" class="flex-1 flex items-center gap-2">
                                    <x-text-input wire:model="editingLessonTitle" class="block w-full" autofocus />
                                    <x-primary-button>{{ __('Сохранить') }}</x-primary-button>
                                    <button type="button" wire:click="$set('editingLessonId', null)" class="text-sm text-gray-500">{{ __('Отмена') }}</button>
                                </form>
                            @else
                                <span class="text-gray-700">{{ $lesson->title }}</span>
                                <div class="flex items-center gap-3 text-sm">
                                    <button wire:click="moveLessonUp({{ $lesson->id }})" type="button" class="text-gray-400 hover:text-gray-700">&uarr;</button>
                                    <button wire:click="moveLessonDown({{ $lesson->id }})" type="button" class="text-gray-400 hover:text-gray-700">&darr;</button>
                                    <a href="{{ route('admin.lessons.edit', $lesson) }}" wire:navigate class="text-blue-700 hover:text-blue-900">{{ __('Содержимое') }}</a>
                                    <button wire:click="startEditingLesson({{ $lesson->id }})" type="button" class="text-gray-600 hover:text-gray-900">{{ __('Переименовать') }}</button>
                                    <button wire:click="deleteLesson({{ $lesson->id }})" wire:confirm="{{ __('Удалить урок?') }}" type="button" class="text-red-600 hover:text-red-900">{{ __('Удалить') }}</button>
                                </div>
                            @endif
                        </li>
                    @empty
                        <li class="py-3 text-sm text-gray-400">{{ __('Уроков пока нет.') }}</li>
                    @endforelse
                </ul>

                @if ($addingLessonToModuleId === $module->id)
                    <form wire:submit="addLesson" class="mt-4 flex items-center gap-2">
                        <x-text-input wire:model="newLessonTitle" placeholder="{{ __('Название урока') }}" class="block w-full" autofocus />
                        <x-primary-button>{{ __('Добавить') }}</x-primary-button>
                        <button type="button" wire:click="$set('addingLessonToModuleId', null)" class="text-sm text-gray-500">{{ __('Отмена') }}</button>
                    </form>
                    <x-input-error :messages="$errors->get('newLessonTitle')" class="mt-2" />
                @else
                    <button wire:click="startAddingLesson({{ $module->id }})" type="button" class="mt-4 text-sm text-blue-700 hover:text-blue-900">
                        + {{ __('Добавить урок') }}
                    </button>
                @endif
            </x-card>
        @endforeach

        <x-card>
            <form wire:submit="addModule" class="flex items-center gap-2">
                <x-text-input wire:model="newModuleTitle" placeholder="{{ __('Название нового модуля') }}" class="block w-full" />
                <x-primary-button>{{ __('Добавить модуль') }}</x-primary-button>
            </form>
            <x-input-error :messages="$errors->get('newModuleTitle')" class="mt-2" />
        </x-card>
    </div>
</div>
