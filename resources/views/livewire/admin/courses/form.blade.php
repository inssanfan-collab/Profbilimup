<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <form wire:submit="save" class="space-y-6">
                <div>
                    <x-input-label for="title" :value="__('Название курса')" />
                    <x-text-input wire:model="title" id="title" class="block mt-1 w-full" />
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="description" :value="__('Описание')" />
                    <textarea wire:model="description" id="description" rows="4"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="academic_hours" :value="__('Количество академических часов')" />
                    <x-text-input wire:model="academic_hours" id="academic_hours" type="number" min="1" class="block mt-1 w-full max-w-xs" />
                    <p class="mt-1 text-sm text-gray-500">
                        {{ __('Для публикации курса требуется не менее :min часов (п.25 Приказа МОН РК №95).', ['min' => \App\Models\Course::MIN_ACADEMIC_HOURS_TO_PUBLISH]) }}
                    </p>
                    <x-input-error :messages="$errors->get('academic_hours')" class="mt-2" />
                </div>

                <div class="flex items-center gap-4">
                    <x-primary-button>{{ __('Сохранить и перейти к содержимому') }}</x-primary-button>
                    <a href="{{ route('admin.courses.index') }}" wire:navigate class="text-sm text-gray-600 hover:text-gray-900">{{ __('Отмена') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>
