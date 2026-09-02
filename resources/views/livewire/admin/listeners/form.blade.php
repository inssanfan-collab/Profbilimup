<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <x-card>
            <form wire:submit="save" class="space-y-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="sm:col-span-2">
                        <x-input-label for="full_name" :value="__('ФИО')" />
                        <x-text-input wire:model="full_name" id="full_name" class="block mt-1 w-full" />
                        <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="email" value="Email" />
                        <x-text-input wire:model="email" id="email" type="email" class="block mt-1 w-full" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="phone" :value="__('Телефон')" />
                        <x-text-input wire:model="phone" id="phone" class="block mt-1 w-full" />
                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="locale" :value="__('Язык интерфейса')" />
                        <select wire:model="locale" id="locale" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-600 focus:ring-blue-600">
                            <option value="ru">{{ __('Русский') }}</option>
                            <option value="kk">{{ __('Қазақша') }}</option>
                        </select>
                        <x-input-error :messages="$errors->get('locale')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="workplace" :value="__('Место работы / школа')" />
                        <x-text-input wire:model="workplace" id="workplace" class="block mt-1 w-full" />
                        <x-input-error :messages="$errors->get('workplace')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="position" :value="__('Должность')" />
                        <x-text-input wire:model="position" id="position" class="block mt-1 w-full" />
                        <x-input-error :messages="$errors->get('position')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="subject" :value="__('Предмет')" />
                        <x-text-input wire:model="subject" id="subject" class="block mt-1 w-full" />
                        <x-input-error :messages="$errors->get('subject')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="qualification_category" :value="__('Квалификационная категория')" />
                        <x-text-input wire:model="qualification_category" id="qualification_category" class="block mt-1 w-full" />
                        <x-input-error :messages="$errors->get('qualification_category')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="experience_years" :value="__('Стаж (лет)')" />
                        <x-text-input wire:model="experience_years" id="experience_years" type="number" min="0" max="80" class="block mt-1 w-full" />
                        <x-input-error :messages="$errors->get('experience_years')" class="mt-2" />
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <x-primary-button>{{ __('Сохранить') }}</x-primary-button>
                    <a href="{{ route('admin.listeners.index') }}" wire:navigate class="text-sm text-gray-600 hover:text-gray-900">{{ __('Отмена') }}</a>
                </div>
            </form>
        </x-card>
    </div>
</div>
