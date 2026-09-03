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
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-slate-900">
            {{ __('Установка пароля') }}
        </h1>
        <p class="mt-2 text-sm text-slate-600 leading-relaxed">
            {{ __('Здравствуйте, :name! Установите пароль, чтобы войти в систему.', ['name' => $user->name]) }}
        </p>
    </div>

    <form wire:submit="acceptInvite" class="space-y-5">
        <div>
            <x-input-label for="password" :value="__('Пароль')" class="font-bold text-xs uppercase tracking-wider text-slate-700" />
            <div class="relative mt-1.5 rounded-xl shadow-sm">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                    <x-app-icon name="lock" class="h-[18px] w-[18px]" aria-hidden="true" />
                </div>
                <x-text-input wire:model="password" id="password" class="block w-full pl-11 pr-4 py-3 rounded-xl border-slate-300/80 bg-slate-50/50 text-sm text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition-all" type="password" name="password" required autofocus autocomplete="new-password" placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Повторите пароль')" class="font-bold text-xs uppercase tracking-wider text-slate-700" />
            <div class="relative mt-1.5 rounded-xl shadow-sm">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                    <x-app-icon name="lock" class="h-[18px] w-[18px]" aria-hidden="true" />
                </div>
                <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block w-full pl-11 pr-4 py-3 rounded-xl border-slate-300/80 bg-slate-50/50 text-sm text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition-all" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-xs" />
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full justify-center py-3.5 rounded-xl text-sm font-bold bg-gradient-to-r from-blue-700 via-blue-600 to-indigo-700 text-white shadow-raised hover:from-blue-800 hover:to-indigo-800 hover:shadow-glow transition-all duration-200">
                {{ __('Установить пароль и войти') }}
            </x-primary-button>
        </div>
    </form>
</div>
