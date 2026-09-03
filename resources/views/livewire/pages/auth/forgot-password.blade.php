<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }
}; ?>

<div>
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-slate-900">
            {{ __('Forgot your password?') }}
        </h1>
        <p class="mt-2 text-sm text-slate-500 leading-relaxed">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-5" :status="session('status')" />

    <form wire:submit="sendPasswordResetLink" class="space-y-5">
        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="font-bold text-xs uppercase tracking-wider text-slate-700" />
            <div class="relative mt-1.5 rounded-xl shadow-sm">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                    <x-app-icon name="email" class="h-[18px] w-[18px]" aria-hidden="true" />
                </div>
                <x-text-input wire:model="email" id="email" class="block w-full pl-11 pr-4 py-3 rounded-xl border-slate-300/80 bg-slate-50/50 text-sm text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition-all" type="email" name="email" required autofocus placeholder="name@example.kz" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs" />
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-2">
            <a href="{{ route('login') }}" wire:navigate class="text-xs font-semibold text-slate-500 hover:text-slate-800 transition-colors">
                &larr; {{ __('Log in') }}
            </a>

            <x-primary-button class="w-full sm:w-auto justify-center py-3 px-6 rounded-xl text-sm font-bold bg-gradient-to-r from-blue-700 via-blue-600 to-indigo-700 text-white shadow-raised hover:from-blue-800 hover:to-indigo-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-600">
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
</div>
