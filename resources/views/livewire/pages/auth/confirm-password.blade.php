<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $password = '';

    /**
     * Confirm the current user's password.
     */
    public function confirmPassword(): void
    {
        $this->validate([
            'password' => ['required', 'string'],
        ]);

        if (! Auth::guard('web')->validate([
            'email' => Auth::user()->email,
            'password' => $this->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        session(['auth.password_confirmed_at' => time()]);

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-slate-900">
            {{ __('Confirm Password') }}
        </h1>
        <p class="mt-2 text-sm text-slate-500 leading-relaxed">
            {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
        </p>
    </div>

    <form wire:submit="confirmPassword" class="space-y-5">
        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="font-bold text-xs uppercase tracking-wider text-slate-700" />
            <div class="relative mt-1.5 rounded-xl shadow-sm">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                    <x-app-icon name="lock" class="h-[18px] w-[18px]" aria-hidden="true" />
                </div>
                <x-text-input wire:model="password"
                              id="password"
                              class="block w-full pl-11 pr-4 py-3 rounded-xl border-slate-300/80 bg-slate-50/50 text-sm text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition-all"
                              type="password"
                              name="password"
                              required autocomplete="current-password"
                              placeholder="••••••••" />
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs" />
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full justify-center py-3.5 rounded-xl text-sm font-bold bg-gradient-to-r from-blue-700 via-blue-600 to-indigo-700 text-white shadow-raised hover:from-blue-800 hover:to-indigo-800 hover:shadow-glow transition-all duration-200">
                {{ __('Confirm') }}
            </x-primary-button>
        </div>
    </form>
</div>
