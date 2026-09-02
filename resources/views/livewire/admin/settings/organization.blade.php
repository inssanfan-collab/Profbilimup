<div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <x-card>
            <form wire:submit="save" class="space-y-6">
                <div>
                    <x-input-label for="name_ru" :value="__('Название организации (русский)')" />
                    <x-text-input wire:model="name_ru" id="name_ru" class="block mt-1 w-full" />
                    <x-input-error :messages="$errors->get('name_ru')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="name_kk" :value="__('Название организации (қазақша)')" />
                    <x-text-input wire:model="name_kk" id="name_kk" class="block mt-1 w-full" />
                    <x-input-error :messages="$errors->get('name_kk')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="director_full_name" :value="__('ФИО руководителя (для подписи на сертификатах)')" />
                    <x-text-input wire:model="director_full_name" id="director_full_name" class="block mt-1 w-full" />
                    <x-input-error :messages="$errors->get('director_full_name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="logo" :value="__('Логотип')" />
                    @if ($settings->logo_path)
                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($settings->logo_path) }}" class="h-16 my-2 rounded-lg">
                    @endif
                    <input type="file" wire:model="logo" id="logo" accept="image/*" class="block mt-1 text-sm">
                    <span wire:loading wire:target="logo" class="text-sm text-gray-500">{{ __('Загрузка...') }}</span>
                    <x-input-error :messages="$errors->get('logo')" class="mt-2" />
                </div>

                <div class="flex items-center gap-4">
                    <x-primary-button>{{ __('Сохранить') }}</x-primary-button>
                    <span x-data="{ shown: false }" x-on:settings-saved.window="shown = true; setTimeout(() => shown = false, 2000)" x-show="shown" x-cloak class="text-sm text-green-600">{{ __('Сохранено') }}</span>
                </div>
            </form>
        </x-card>
    </div>
</div>
