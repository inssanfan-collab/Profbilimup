<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center gap-2.5">
                    <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-2.5">
                        <x-application-logo class="block h-9 w-9 rounded-lg" />
                        <span class="hidden md:inline font-bold text-gray-900">{{ config('app.name', 'BilimUP') }}</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-6 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                        <x-app-icon name="courses" class="h-4 w-4" />
                        {{ __('Главная') }}
                    </x-nav-link>

                    @if (auth()->user()->isAdmin())
                        <x-nav-link :href="route('admin.courses.index')" :active="request()->routeIs('admin.courses.*') || request()->routeIs('admin.lessons.*')" wire:navigate>
                            <x-app-icon name="courses" class="h-4 w-4" />
                            {{ __('Курсы') }}
                        </x-nav-link>

                        <x-nav-link :href="route('admin.listeners.index')" :active="request()->routeIs('admin.listeners.*')" wire:navigate>
                            <x-app-icon name="users" class="h-4 w-4" />
                            {{ __('Слушатели') }}
                        </x-nav-link>

                        <x-nav-link :href="route('admin.test-review.index')" :active="request()->routeIs('admin.test-review.*')" wire:navigate>
                            <x-app-icon name="plans" class="h-4 w-4" />
                            {{ __('Проверка тестов') }}
                        </x-nav-link>

                        <x-nav-link :href="route('admin.analytics.index')" :active="request()->routeIs('admin.analytics.*')" wire:navigate>
                            <x-app-icon name="chart" class="h-4 w-4" />
                            {{ __('Аналитика') }}
                        </x-nav-link>

                        <x-nav-link :href="route('admin.post-course-support.index')" :active="request()->routeIs('admin.post-course-support.*')" wire:navigate>
                            <x-app-icon name="events" class="h-4 w-4" />
                            {{ __('Посткурсовое сопровождение') }}
                        </x-nav-link>
                    @else
                        <x-nav-link :href="route('listener.certificates.index')" :active="request()->routeIs('listener.certificates.*')" wire:navigate>
                            <x-app-icon name="certificate" class="h-4 w-4" />
                            {{ __('Мои сертификаты') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-3">
                <div class="flex items-center text-xs text-gray-400 gap-1">
                    <a href="{{ route('locale.update', 'ru') }}" class="{{ app()->getLocale() === 'ru' ? 'font-semibold text-gray-700' : 'hover:text-gray-600' }}">RU</a>
                    <span>/</span>
                    <a href="{{ route('locale.update', 'kk') }}" class="{{ app()->getLocale() === 'kk' ? 'font-semibold text-gray-700' : 'hover:text-gray-600' }}">KK</a>
                </div>

                <livewire:shared.notification-bell />

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-lg text-gray-600 bg-white hover:bg-gray-50 hover:text-gray-800 focus:outline-none transition ease-in-out duration-150">
                            <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile')" wire:navigate>
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        @if (auth()->user()->isAdmin())
                            <x-dropdown-link :href="route('admin.settings.organization')" wire:navigate>
                                {{ __('Настройки организации') }}
                            </x-dropdown-link>
                        @endif

                        <!-- Authentication -->
                        <button wire:click="logout" class="w-full text-start">
                            <x-dropdown-link>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                {{ __('Главная') }}
            </x-responsive-nav-link>

            @if (auth()->user()->isAdmin())
                <x-responsive-nav-link :href="route('admin.courses.index')" :active="request()->routeIs('admin.courses.*') || request()->routeIs('admin.lessons.*')" wire:navigate>
                    {{ __('Курсы') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('admin.listeners.index')" :active="request()->routeIs('admin.listeners.*')" wire:navigate>
                    {{ __('Слушатели') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('admin.test-review.index')" :active="request()->routeIs('admin.test-review.*')" wire:navigate>
                    {{ __('Проверка тестов') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('admin.analytics.index')" :active="request()->routeIs('admin.analytics.*')" wire:navigate>
                    {{ __('Аналитика') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('admin.post-course-support.index')" :active="request()->routeIs('admin.post-course-support.*')" wire:navigate>
                    {{ __('Посткурсовое сопровождение') }}
                </x-responsive-nav-link>
            @else
                <x-responsive-nav-link :href="route('listener.certificates.index')" :active="request()->routeIs('listener.certificates.*')" wire:navigate>
                    {{ __('Мои сертификаты') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                <div class="font-medium text-sm text-gray-500">{{ auth()->user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile')" wire:navigate>
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                @if (auth()->user()->isAdmin())
                    <x-responsive-nav-link :href="route('admin.settings.organization')" wire:navigate>
                        {{ __('Настройки организации') }}
                    </x-responsive-nav-link>
                @endif

                <!-- Authentication -->
                <button wire:click="logout" class="w-full text-start">
                    <x-responsive-nav-link>
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </button>
            </div>
        </div>
    </div>
</nav>
