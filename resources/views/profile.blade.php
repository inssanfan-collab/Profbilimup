<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight tracking-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-card>
                <div class="max-w-xl">
                    <livewire:profile.update-profile-information-form />
                </div>
            </x-card>

            <x-card>
                <div class="max-w-xl">
                    <livewire:profile.update-password-form />
                </div>
            </x-card>

            <x-card>
                <div class="max-w-xl">
                    <livewire:profile.delete-user-form />
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>
