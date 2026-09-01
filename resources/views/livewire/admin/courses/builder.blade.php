<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white shadow-sm sm:rounded-lg p-6 flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">
                    {{ __('Часов:') }} {{ $course->academic_hours }}
                    ·
                    @if ($course->status === \App\Enums\CourseStatus::Published)
                        <span class="text-green-600">{{ __('Опубликован') }}</span>
                    @else
                        <span class="text-amber-600">{{ __('Черновик') }}</span>
                    @endif
                </p>
                <a href="{{ route('admin.courses.edit', $course) }}" wire:navigate class="text-sm text-indigo-600 hover:text-indigo-900">{{ __('Изменить параметры курса') }}</a>
            </div>

            <div>
                @if ($course->status === \App\Enums\CourseStatus::Published)
                    <button wire:click="unpublish" type="button" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50">
                        {{ __('Снять с публикации') }}
                    </button>
                @else
                    <button wire:click="publish" type="button" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                        {{ __('Опубликовать') }}
                    </button>
                @endif
                @error('publish') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        @foreach ($modules as $module)
            <div class="bg-white shadow-sm sm:rounded-lg p-6" wire:key="module-{{ $module->id }}">
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
                            <button wire:click="startEditingModule({{ $module->id }})" type="button" class="text-indigo-600 hover:text-indigo-900">{{ __('Переименовать') }}</button>
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
                                    <a href="{{ route('admin.lessons.edit', $lesson) }}" wire:navigate class="text-indigo-600 hover:text-indigo-900">{{ __('Содержимое') }}</a>
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
                    <button wire:click="startAddingLesson({{ $module->id }})" type="button" class="mt-4 text-sm text-indigo-600 hover:text-indigo-900">
                        + {{ __('Добавить урок') }}
                    </button>
                @endif
            </div>
        @endforeach

        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <form wire:submit="addModule" class="flex items-center gap-2">
                <x-text-input wire:model="newModuleTitle" placeholder="{{ __('Название нового модуля') }}" class="block w-full" />
                <x-primary-button>{{ __('Добавить модуль') }}</x-primary-button>
            </form>
            <x-input-error :messages="$errors->get('newModuleTitle')" class="mt-2" />
        </div>
    </div>
</div>
