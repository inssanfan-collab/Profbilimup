<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    #[Locked]
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Mount the component.
     */
    public function mount(string $token): void
    {
        $this->token = $token;

        $this->email = request()->string('email');
    }

    /**
     * Reset the password for the given user.
     */
    public function resetPassword(): void
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        if ($status != Password::PASSWORD_RESET) {
            $this->addError('email', __($status));

            return;
        }

        Session::flash('status', __($status));

        $this->redirectRoute('login', navigate: true);
    }
}; ?>

<div>
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-slate-900">
            {{ __('Reset Password') }}
        </h1>
        <p class="mt-2 text-sm text-slate-500 leading-relaxed">
            {{ __('New Password') }}
        </p>
    </div>

    <form wire:submit="resetPassword" class="space-y-5">
        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="font-bold text-xs uppercase tracking-wider text-slate-700" />
            <div class="relative mt-1.5 rounded-xl shadow-sm">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                    <x-app-icon name="email" class="h-[18px] w-[18px]" aria-hidden="true" />
                </div>
                <x-text-input wire:model="email" id="email" class="block w-full pl-11 pr-4 py-3 rounded-xl border-slate-300/80 bg-slate-50/50 text-sm text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition-all" type="email" name="email" required autofocus autocomplete="username" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="font-bold text-xs uppercase tracking-wider text-slate-700" />
            <div class="relative mt-1.5 rounded-xl shadow-sm">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                    <x-app-icon name="lock" class="h-[18px] w-[18px]" aria-hidden="true" />
                </div>
                <x-text-input wire:model="password" id="password" class="block w-full pl-11 pr-4 py-3 rounded-xl border-slate-300/80 bg-slate-50/50 text-sm text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition-all" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="font-bold text-xs uppercase tracking-wider text-slate-700" />
            <div class="relative mt-1.5 rounded-xl shadow-sm">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                    <x-app-icon name="lock" class="h-[18px] w-[18px]" aria-hidden="true" />
                </div>
                <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block w-full pl-11 pr-4 py-3 rounded-xl border-slate-300/80 bg-slate-50/50 text-sm text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition-all"
                              type="password"
                              name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-xs" />
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full justify-center py-3.5 rounded-xl text-sm font-bold bg-gradient-to-r from-blue-700 via-blue-600 to-indigo-700 text-white shadow-raised hover:from-blue-800 hover:to-indigo-800 hover:shadow-glow transition-all duration-200">
                {{ __('Reset Password') }}
            </x-primary-button>
        </div>
    </form>
</div>
