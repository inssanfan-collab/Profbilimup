<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    #[Locked]
    public User $user;

    public string $password = '';

    public string $password_confirmation = '';

    public function mount(User $user): void
    {
        abort_unless($user->must_set_password, 404);

        $this->user = $user;
    }

    public function acceptInvite(): void
    {
        $this->validate([
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $this->user->forceFill([
            'password' => $this->password,
            'must_set_password' => false,
            'email_verified_at' => now(),
        ])->save();

        Auth::login($this->user);

        $this->redirectRoute('dashboard', navigate: true);
    }
}; ?>

<div>
    <p class="mb-4 text-sm text-gray-600">
        {{ __('Здравствуйте, :name! Установите пароль, чтобы войти в систему.', ['name' => $user->name]) }}
    </p>

    <form wire:submit="acceptInvite">
        <div>
            <x-input-label for="password" :value="__('Пароль')" />
            <x-text-input wire:model="password" id="password" class="block mt-1 w-full" type="password" name="password" required autofocus autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Повторите пароль')" />
            <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>{{ __('Установить пароль и войти') }}</x-primary-button>
        </div>
    </form>
</div>
