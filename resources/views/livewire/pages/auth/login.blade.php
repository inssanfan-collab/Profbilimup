<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    {{-- Заголовок формы --}}
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-slate-900">
            {{ __('Log in') }}
        </h1>
        <p class="mt-2 text-sm text-slate-500 leading-relaxed">
            {{ __('Войдите в личный кабинет, чтобы увидеть назначенные курсы, результаты тестов и выданные документы.') }}
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-5" :status="session('status')" />

    <form wire:submit="login" class="space-y-5">
        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="font-bold text-xs uppercase tracking-wider text-slate-700" />
            <div class="relative mt-1.5 rounded-xl shadow-sm">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                    <x-app-icon name="email" class="h-[18px] w-[18px]" aria-hidden="true" />
                </div>
                <x-text-input wire:model="form.email" id="email" class="block w-full pl-11 pr-4 py-3 rounded-xl border-slate-300/80 bg-slate-50/50 text-sm text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition-all" type="email" name="email" required autofocus autocomplete="username" placeholder="name@example.kz" />
            </div>
            <x-input-error :messages="$errors->get('form.email')" class="mt-2 text-xs" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between">
                <x-input-label for="password" :value="__('Password')" class="font-bold text-xs uppercase tracking-wider text-slate-700" />
                @if (Route::has('password.request'))
                    <a class="text-xs font-semibold text-blue-700 hover:text-blue-800 transition-colors focus:outline-none focus:underline" href="{{ route('password.request') }}" wire:navigate>
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div>

            <div class="relative mt-1.5 rounded-xl shadow-sm">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                    <x-app-icon name="lock" class="h-[18px] w-[18px]" aria-hidden="true" />
                </div>
                <x-text-input wire:model="form.password" id="password" class="block w-full pl-11 pr-4 py-3 rounded-xl border-slate-300/80 bg-slate-50/50 text-sm text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition-all"
                                type="password"
                                name="password"
                                required autocomplete="current-password"
                                placeholder="••••••••" />
            </div>

            <x-input-error :messages="$errors->get('form.password')" class="mt-2 text-xs" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between pt-1">
            <label for="remember" class="inline-flex items-center cursor-pointer">
                <input wire:model="form.remember" id="remember" type="checkbox" class="rounded-md border-slate-300 text-blue-700 shadow-sm focus:ring-2 focus:ring-blue-600/20" name="remember">
                <span class="ms-2.5 text-sm font-medium text-slate-600 select-none">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full justify-center py-3.5 rounded-xl text-sm font-bold bg-gradient-to-r from-blue-700 via-blue-600 to-indigo-700 text-white shadow-raised hover:from-blue-800 hover:to-indigo-800 hover:shadow-glow transition-all duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-600">
                <span wire:loading.remove wire:target="login">{{ __('Log in') }}</span>
                <span wire:loading wire:target="login" class="inline-flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>{{ __('Log in') }}...</span>
                </span>
            </x-primary-button>
        </div>
    </form>
</div>
