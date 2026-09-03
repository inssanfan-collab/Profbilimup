<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ProfBilimUP') }}</title>

        <!-- Favicon / App Icon -->
        <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('images/favicon-192.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('images/favicon-512.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=golos-text:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="h-full font-sans text-slate-900 antialiased bg-slate-50 selection:bg-blue-600 selection:text-white">
        <div class="min-h-full flex flex-col lg:flex-row">
            {{-- ── Левая колонка (Брендированная 3D-иллюстрация + Преимущества) ── --}}
            <div class="relative hidden lg:flex lg:w-1/2 xl:w-5/12 flex-col justify-between overflow-hidden bg-slate-950 p-12 text-white">
                {{-- Фоновое изображение 3D Мастерписа --}}
                <img src="{{ asset('images/auth_masterpiece.jpg') }}"
                    alt="{{ config('app.name', 'ProfBilimUP') }}"
                    class="absolute inset-0 h-full w-full object-cover object-center opacity-60" />
                
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/40 to-slate-950/25" aria-hidden="true"></div>
                <div class="absolute -top-24 -left-24 h-96 w-96 rounded-full bg-blue-600/20 blur-3xl" aria-hidden="true"></div>
                <div class="absolute -bottom-24 -right-24 h-96 w-96 rounded-full bg-emerald-500/15 blur-3xl" aria-hidden="true"></div>

                {{-- Верхняя часть: Логотип и возврат --}}
                <div class="relative z-10 flex items-center justify-between">
                    <a href="/" wire:navigate class="group flex items-center gap-3.5">
                        <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white shadow-xl transition-transform duration-200 group-hover:scale-105">
                            <x-application-logo class="h-8 w-8" />
                        </span>
                        <span class="text-2xl font-black tracking-tight text-white">ProfBilim<span class="text-amber-400">UP</span></span>
                    </a>

                    <a href="/" wire:navigate class="inline-flex items-center gap-2 rounded-xl bg-white/10 px-4 py-2 text-xs font-bold text-slate-200 backdrop-blur-md transition-all hover:bg-white/20 hover:text-white ring-1 ring-white/15">
                        <x-app-icon name="arrow-left" class="h-4 w-4" aria-hidden="true" />
                        <span>{{ __('На главную') }}</span>
                    </a>
                </div>

                {{-- Нижняя часть: Описание платформы и знаки отличия --}}
                <div class="relative z-10 mt-auto pt-16">
                    <div class="inline-flex items-center gap-2 rounded-full bg-blue-500/20 px-4 py-1.5 text-xs font-bold text-blue-300 ring-1 ring-inset ring-blue-400/40 backdrop-blur-md">
                        <x-app-icon name="shield-check" class="h-4 w-4 text-blue-400" aria-hidden="true" />
                        <span>{{ __('Учебный центр повышения квалификации педагогов') }}</span>
                    </div>

                    <h2 class="mt-5 text-2xl xl:text-3xl font-black text-white leading-snug">
                        {{ __('Платформа профессионального развития педагогов') }}
                    </h2>

                    <p class="mt-3 text-sm text-slate-300/90 leading-relaxed max-w-md">
                        {{ __('Онлайн-курсы, тесты и сертификаты государственного образца для развития профессиональных компетенций педагогов Казахстана.') }}
                    </p>

                    <div class="mt-8 grid grid-cols-1 gap-3.5 pt-6 border-t border-white/10 text-xs font-bold text-slate-200">
                        <div class="flex items-center gap-3">
                            <div class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-500/20 text-emerald-400 ring-1 ring-emerald-400/30">
                                <x-app-icon name="check" class="h-3.5 w-3.5" aria-hidden="true" />
                            </div>
                            <span>{{ __('Сертификаты гос. образца') }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="flex h-6 w-6 items-center justify-center rounded-full bg-blue-500/20 text-blue-400 ring-1 ring-blue-400/30">
                                <x-app-icon name="check" class="h-3.5 w-3.5" aria-hidden="true" />
                            </div>
                            <span>{{ __('Тесты и проверка знаний') }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="flex h-6 w-6 items-center justify-center rounded-full bg-indigo-500/20 text-indigo-400 ring-1 ring-indigo-400/30">
                                <x-app-icon name="check" class="h-3.5 w-3.5" aria-hidden="true" />
                            </div>
                            <span>{{ __('Уроки по видеосвязи') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Правая колонка (Контейнер авторизации) ───────────────── --}}
            <div class="flex-1 flex flex-col justify-between p-6 sm:p-10 lg:p-16 bg-white">
                {{-- Верхняя панель: Логотип (для мобильных) + Переключатель языка --}}
                <div class="flex items-center justify-between gap-4">
                    <div class="flex lg:hidden items-center gap-3">
                        <a href="/" wire:navigate class="flex items-center gap-2.5">
                            <x-application-logo class="h-9 w-9" />
                            <span class="text-xl font-black tracking-tight text-slate-900">ProfBilim<span class="text-blue-700">UP</span></span>
                        </a>
                    </div>

                    <div class="hidden lg:block"></div>

                    {{-- Переключатель языка RU / KK --}}
                    <div class="flex items-center rounded-xl border border-slate-200 bg-slate-100 p-1 text-xs font-bold shadow-sm" role="group" aria-label="{{ __('Язык интерфейса') }}">
                        <a href="{{ route('locale.update', 'ru') }}"
                            @if (app()->getLocale() === 'ru') aria-current="true" @endif
                            class="rounded-lg px-3.5 py-1.5 transition-all duration-150 {{ app()->getLocale() === 'ru' ? 'bg-white text-blue-700 shadow-sm font-black' : 'text-slate-500 hover:text-slate-900' }}">RU</a>
                        <a href="{{ route('locale.update', 'kk') }}"
                            @if (app()->getLocale() === 'kk') aria-current="true" @endif
                            class="rounded-lg px-3.5 py-1.5 transition-all duration-150 {{ app()->getLocale() === 'kk' ? 'bg-white text-blue-700 shadow-sm font-black' : 'text-slate-500 hover:text-slate-900' }}">KK</a>
                    </div>
                </div>

                {{-- Форма --}}
                <div class="my-auto w-full max-w-md mx-auto py-8">
                    {{ $slot }}
                </div>

                {{-- Подвал --}}
                <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-400 gap-2 font-medium">
                    <span>&copy; {{ date('Y') }} {{ config('app.name', 'ProfBilimUP') }}</span>
                    <a href="{{ route('home') }}#verify" wire:navigate class="hover:text-blue-600 transition-colors">
                        {{ __('Проверка сертификата') }}
                    </a>
                </div>
            </div>
        </div>
    </body>
</html>
