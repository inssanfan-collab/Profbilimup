<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <x-card>
            <form wire:submit="save" class="space-y-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
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
                        <x-input-label for="password" :value="__('Пароль')" />
                        <x-text-input wire:model="password" id="password" type="password" class="block mt-1 w-full" />
                        @if ($user)
                            <p class="mt-1 text-xs text-gray-500">{{ __('Оставьте пустым, чтобы не менять пароль.') }}</p>
                        @endif
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="password_confirmation" :value="__('Повторите пароль')" />
                        <x-text-input wire:model="password_confirmation" id="password_confirmation" type="password" class="block mt-1 w-full" />
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-6">
                    <x-input-label :value="__('Права куратора')" />
                    <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-2">
                        @foreach ($allPermissions as $permission)
                            <label class="flex items-center gap-2 text-sm text-gray-700">
                                <input type="checkbox" wire:model="permissions" value="{{ $permission->value }}"
                                    class="rounded border-gray-300 text-blue-700 focus:ring-blue-600">
                                {{ $permission->label() }}
                            </label>
                        @endforeach
                    </div>
                    <x-input-error :messages="$errors->get('permissions')" class="mt-2" />
                </div>

                <div class="border-t border-gray-100 pt-6">
                    <x-input-label :value="__('Доступ к курсам')" />
                    <div class="mt-2 space-y-3">
                        <label class="flex items-center gap-2 text-sm text-gray-700">
                            <input type="radio" wire:model.live="courseScope" value="all" class="border-gray-300 text-blue-700 focus:ring-blue-600">
                            {{ __('Все курсы') }}
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-700">
                            <input type="radio" wire:model.live="courseScope" value="selected" class="border-gray-300 text-blue-700 focus:ring-blue-600">
                            {{ __('Выбранные курсы') }}
                        </label>

                        @if ($courseScope === 'selected')
                            <div class="ms-6 grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-64 overflow-y-auto rounded-lg border border-gray-100 p-3">
                                @forelse ($courses as $course)
                                    <label class="flex items-center gap-2 text-sm text-gray-700">
                                        <input type="checkbox" wire:model="courseIds" value="{{ $course->id }}"
                                            class="rounded border-gray-300 text-blue-700 focus:ring-blue-600">
                                        {{ $course->title }}
                                    </label>
                                @empty
                                    <p class="text-sm text-gray-400">{{ __('Курсов пока нет.') }}</p>
                                @endforelse
                            </div>
                        @endif
                    </div>
                    <x-input-error :messages="$errors->get('courseIds')" class="mt-2" />
                </div>

                <div class="flex items-center gap-4">
                    <x-primary-button>{{ __('Сохранить') }}</x-primary-button>
                    <a href="{{ route('admin.curators.index') }}" wire:navigate class="text-sm text-gray-600 hover:text-gray-900">{{ __('Отмена') }}</a>
                </div>
            </form>
        </x-card>
    </div>
</div>
