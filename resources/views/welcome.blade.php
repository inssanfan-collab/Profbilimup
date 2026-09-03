<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $organization->name_ru ?: config('app.name', 'ProfBilimUP') }}</title>
        <meta name="description" content="{{ __('Онлайн-курсы, тесты и сертификаты государственного образца для развития профессиональных компетенций педагогов Казахстана.') }}">

        <!-- Favicon / App Icon -->
        <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('images/favicon-192.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('images/favicon-512.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=golos-text:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            /* --- Глобальные фоновые градиенты и свечения --- */
            .page-bg-mesh {
                background-color: #f8fafc;
                background-image:
                    radial-gradient(circle at 85% 5%, rgba(37, 99, 235, 0.13) 0%, transparent 50%),
                    radial-gradient(circle at 10% 25%, rgba(14, 165, 233, 0.10) 0%, transparent 45%),
                    radial-gradient(circle at 90% 55%, rgba(99, 102, 241, 0.08) 0%, transparent 40%),
                    radial-gradient(circle at 15% 80%, rgba(16, 185, 129, 0.08) 0%, transparent 45%);
                background-attachment: fixed;
            }

            .hero-mesh {
                background:
                    radial-gradient(65rem 40rem at 80% -10%, rgba(37, 99, 235, 0.16), transparent 70%),
                    radial-gradient(55rem 35rem at 5% 20%, rgba(14, 165, 233, 0.14), transparent 65%),
                    radial-gradient(45rem 30rem at 50% 60%, rgba(99, 102, 241, 0.09), transparent 60%);
            }

            .dot-grid {
                background-image: radial-gradient(rgba(15, 23, 42, 0.07) 1.25px, transparent 1.25px);
                background-size: 24px 24px;
                mask-image: linear-gradient(to bottom, #000 30%, transparent 95%);
                -webkit-mask-image: linear-gradient(to bottom, #000 30%, transparent 95%);
            }

            /* --- Анимации --- */
            @keyframes float-subtle {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-8px); }
            }
            @keyframes pulse-glow {
                0%, 100% { opacity: 0.4; transform: scale(1); }
                50% { opacity: 0.85; transform: scale(1.05); }
            }

            .anim-float { animation: float-subtle 5s ease-in-out infinite; }
            .anim-float-delayed { animation: float-subtle 6s ease-in-out 1.5s infinite; }
            .anim-pulse { animation: pulse-glow 6s ease-in-out infinite; }

            /* Прокрутка: плавное появление */
            .reveal {
                opacity: 0;
                transform: translateY(20px);
                transition: opacity 0.6s cubic-bezier(0.16, 1, 0.3, 1), transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
                transition-delay: var(--reveal-delay, 0ms);
            }
            .reveal.is-visible {
                opacity: 1;
                transform: none;
            }

            section[id] { scroll-margin-top: 5rem; }

            @media (prefers-reduced-motion: reduce) {
                html { scroll-behavior: auto; }
                .anim-float, .anim-float-delayed, .anim-pulse { animation: none; }
                .reveal { opacity: 1; transform: none; transition: none; }
            }
        </style>
    </head>
    <body class="page-bg-mesh font-sans antialiased text-slate-900 selection:bg-blue-600 selection:text-white min-h-screen">
        <a href="#main"
            class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-50 focus:rounded-xl focus:bg-blue-700 focus:px-4 focus:py-2.5 focus:text-sm focus:font-semibold focus:text-white focus:shadow-lg">
            {{ __('Перейти к содержимому') }}
        </a>

        <div class="min-h-screen flex flex-col">
            {{-- ── Шапка сайта ────────────────────────────────────────── --}}
            <header data-site-header
                class="sticky top-0 z-40 border-b border-transparent bg-white/80 backdrop-blur-md transition-all duration-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-6">
                    <a href="{{ route('home') }}" class="group flex items-center gap-3 rounded-xl focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-600">
                        <x-application-logo class="h-9 w-9 transition-transform duration-200 group-hover:scale-105" />
                        <span class="text-xl font-black tracking-tight text-slate-900">ProfBilim<span class="text-blue-700">UP</span></span>
                    </a>

                    <nav class="hidden md:flex items-center gap-1 text-sm font-semibold text-slate-600" aria-label="{{ __('Разделы страницы') }}">
                        <a href="#features" class="rounded-lg px-3.5 py-2 transition-colors hover:text-blue-700 hover:bg-slate-100/80 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600">{{ __('Возможности') }}</a>
                        <a href="#how" class="rounded-lg px-3.5 py-2 transition-colors hover:text-blue-700 hover:bg-slate-100/80 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600">{{ __('Как проходит обучение') }}</a>
                        <a href="#verify" class="rounded-lg px-3.5 py-2 transition-colors hover:text-blue-700 hover:bg-slate-100/80 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600">{{ __('Проверка сертификата') }}</a>
                    </nav>

                    <livewire:welcome.navigation />
                </div>
            </header>

            <main id="main" class="flex-1">
                {{-- ── Hero Секция ─────────────────────────────────────────── --}}
                <section class="relative overflow-hidden hero-mesh pt-8 sm:pt-14 pb-16 lg:pb-24">
                    <div class="absolute inset-0 dot-grid pointer-events-none" aria-hidden="true"></div>

                    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-10 items-center">
                        {{-- Текстовая колонка --}}
                        <div class="lg:col-span-6">
                            <div class="inline-flex items-center gap-2 rounded-full border border-blue-200/90 bg-white/90 px-4 py-1.5 text-xs sm:text-sm font-semibold text-blue-800 shadow-sm backdrop-blur-md">
                                <span class="flex h-2 w-2 rounded-full bg-emerald-500 ring-4 ring-emerald-100"></span>
                                <x-app-icon name="shield-check" class="h-4 w-4 text-blue-700" aria-hidden="true" />
                                <span>{{ __('Учебный центр повышения квалификации педагогов') }}</span>
                            </div>

                            <h1 class="mt-6 text-3xl sm:text-5xl lg:text-[3.25rem] font-black leading-[1.12] tracking-tight text-slate-900">
                                {{ $organization->name_ru ?: config('app.name', 'ProfBilimUP') }}
                            </h1>

                            @if ($organization->name_kk && $organization->name_kk !== $organization->name_ru)
                                <p class="mt-3 text-lg sm:text-xl font-medium text-slate-500">{{ $organization->name_kk }}</p>
                            @endif

                            <p class="mt-5 text-base sm:text-lg leading-relaxed text-slate-600 max-w-xl">
                                {{ __('Онлайн-курсы, тесты и сертификаты государственного образца для развития профессиональных компетенций педагогов Казахстана.') }}
                            </p>

                            <div class="mt-8 flex flex-col sm:flex-row sm:items-center gap-3">
                                <a href="{{ auth()->check() ? url('/dashboard') : route('login') }}"
                                    class="group inline-flex items-center justify-center gap-2 px-7 py-3.5 rounded-xl bg-gradient-to-r from-blue-700 via-blue-600 to-indigo-700 text-sm font-bold text-white shadow-raised transition-all duration-200 hover:from-blue-800 hover:to-indigo-800 hover:-translate-y-0.5 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2">
                                    {{ auth()->check() ? __('Личный кабинет') : __('Войти в личный кабинет') }}
                                    <x-app-icon name="arrow-right" class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-1" aria-hidden="true" />
                                </a>

                                <a href="#verify"
                                    class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl border border-slate-300 bg-white/95 text-sm font-bold text-slate-700 shadow-sm backdrop-blur-md transition-all duration-200 hover:border-slate-400 hover:bg-white hover:text-slate-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2">
                                    <x-app-icon name="qr-code" class="h-4 w-4 text-blue-700" aria-hidden="true" />
                                    {{ __('Проверить сертификат') }}
                                </a>
                            </div>

                            <div class="mt-7 flex items-start gap-3 rounded-2xl border border-amber-200/90 bg-amber-50/90 p-4 text-xs sm:text-sm text-amber-950 max-w-xl backdrop-blur-sm shadow-sm">
                                <x-app-icon name="lock" class="mt-0.5 h-4 w-4 shrink-0 text-amber-700" aria-hidden="true" />
                                <span class="leading-relaxed font-medium">{{ __('Регистрация закрыта — доступ к курсам предоставляется администратором центра по приглашению.') }}</span>
                            </div>
                        </div>

                        {{-- Визуальная колонка (3D-визуал с плавающими карточками) --}}
                        <div class="lg:col-span-6 relative">
                            <div class="relative mx-auto max-w-lg lg:max-w-none">
                                {{-- Фоновое мягкое свечение --}}
                                <div class="absolute -inset-4 bg-gradient-to-tr from-blue-500/25 via-emerald-500/20 to-indigo-500/25 rounded-3xl blur-2xl anim-pulse pointer-events-none" aria-hidden="true"></div>

                                {{-- Основной фрейм --}}
                                <div class="relative overflow-hidden rounded-2xl lg:rounded-3xl border border-white/80 bg-slate-900 shadow-raised ring-1 ring-slate-900/10">
                                    <img src="{{ asset('images/hero_masterpiece.jpg') }}"
                                        alt="{{ config('app.name', 'ProfBilimUP') }}"
                                        class="w-full h-auto object-cover transform transition-transform duration-700 hover:scale-[1.02]"
                                        loading="eager" />
                                    <div class="absolute inset-x-0 bottom-0 h-14 bg-gradient-to-t from-slate-950/40 to-transparent pointer-events-none"></div>
                                </div>

                                {{-- Плавающая плашка 1: Государственный сертификат с QR --}}
                                <div class="absolute -top-4 -left-4 sm:-left-6 anim-float hidden sm:flex items-center gap-3 rounded-2xl border border-white/95 bg-white/95 px-4 py-3 shadow-raised backdrop-blur-xl ring-1 ring-black/5">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-tr from-amber-500 to-yellow-400 text-white shadow-sm">
                                        <x-app-icon name="certificate" class="h-5 w-5" aria-hidden="true" />
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-slate-900">{{ __('Сертификаты гос. образца') }}</div>
                                        <div class="text-[11px] font-semibold text-emerald-600 flex items-center gap-1">
                                            <x-app-icon name="check" class="h-3 w-3" aria-hidden="true" />
                                            <span>{{ __('QR-код верификации') }}</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Плавающая плашка 2: Онлайн видеоуроки --}}
                                <div class="absolute -bottom-5 -right-4 sm:-right-6 anim-float-delayed hidden sm:flex items-center gap-3 rounded-2xl border border-white/95 bg-white/95 px-4 py-3 shadow-raised backdrop-blur-xl ring-1 ring-black/5">
                                    <div class="relative flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-700">
                                        <x-app-icon name="video" class="h-5 w-5" aria-hidden="true" />
                                        <span class="absolute top-1.5 right-1.5 flex h-2 w-2">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                        </span>
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-slate-900">{{ __('Уроки по видеосвязи') }}</div>
                                        <div class="text-[11px] font-medium text-slate-500">{{ __('Живое общение с лектором') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- ── Показатели центра (Статистика) ───────────────────────── --}}
                @php
                    $stats = array_values(array_filter([
                        ['value' => $publishedCoursesCount, 'label' => __('Курсов в каталоге')],
                        ['value' => $academicHoursSum, 'label' => __('Академических часов')],
                        ['value' => $listenersCount, 'label' => __('Слушателей центра')],
                        ['value' => $certificatesCount, 'label' => __('Выдано документов')],
                    ], fn ($stat) => $stat['value'] > 0));
                @endphp

                @if (count($stats) >= 2)
                    <section class="relative -mt-6 sm:-mt-10 pb-8" aria-label="{{ __('Показатели центра') }}">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                            <dl class="reveal grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                                @foreach ($stats as $index => $stat)
                                    <div class="relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white/95 p-6 sm:p-7 shadow-raised backdrop-blur-sm transition-all duration-300 hover:-translate-y-1 hover:border-blue-300 hover:shadow-lg"
                                        style="--reveal-delay: {{ $index * 60 }}ms">
                                        <div class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-blue-700 via-indigo-600 to-emerald-500"></div>
                                        
                                        <dt class="sr-only">{{ $stat['label'] }}</dt>
                                        <dd class="flex flex-col">
                                            <span class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-blue-700 via-indigo-700 to-blue-900">
                                                {{ number_format($stat['value'], 0, ',', ' ') }}
                                            </span>
                                            <span class="mt-2 text-xs sm:text-sm font-bold text-slate-600">{{ $stat['label'] }}</span>
                                        </dd>
                                    </div>
                                @endforeach
                            </dl>
                        </div>
                    </section>
                @endif

                {{-- ── Возможности платформы (Bento Grid) ───────────────────── --}}
                <section id="features" class="py-20 sm:py-28 relative">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="reveal max-w-3xl">
                            <div class="inline-flex items-center gap-2 rounded-lg bg-blue-100/70 border border-blue-200/60 px-3.5 py-1 text-xs font-bold uppercase tracking-wider text-blue-800">
                                {{ __('Возможности') }}
                            </div>
                            <h2 class="mt-4 text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-slate-900 leading-tight">
                                {{ __('Всё обучение — в одной платформе') }}
                            </h2>
                            <p class="mt-4 text-base sm:text-lg leading-relaxed text-slate-600">
                                {{ __('От назначения курса до выдачи сертификата и посткурсового сопровождения — без бумажных журналов и разрозненных чатов.') }}
                            </p>
                        </div>

                        {{-- Bento Сетка 3x3 --}}
                        <div class="mt-14 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            {{-- Карточка 1: Курсы и видеоуроки (Широкая 2 колонки) --}}
                            <div class="reveal lg:col-span-2 relative overflow-hidden rounded-3xl border border-slate-200/90 bg-white/95 shadow-raised p-8 flex flex-col justify-between group backdrop-blur-sm transition-all duration-300 hover:border-blue-300 hover:shadow-lg">
                                <div class="grid grid-cols-1 sm:grid-cols-12 gap-6 items-center">
                                    <div class="sm:col-span-7">
                                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-blue-700 shadow-sm">
                                            <x-app-icon name="courses" class="h-6 w-6" aria-hidden="true" />
                                        </div>
                                        <h3 class="mt-6 text-xl font-bold text-slate-900 tracking-tight">{{ __('Курсы и видеоуроки') }}</h3>
                                        <p class="mt-3 text-sm leading-relaxed text-slate-600">{{ __('Модули и уроки с текстом, видео и файлами для скачивания — проходите курс в удобном темпе.') }}</p>
                                    </div>
                                    <div class="sm:col-span-5 relative">
                                        <div class="overflow-hidden rounded-2xl border border-slate-200 shadow-md">
                                            <img src="{{ asset('images/feature_webinar.jpg') }}" alt="{{ __('Курсы и видеоуроки') }}" class="w-full h-auto object-cover transform transition-transform duration-500 group-hover:scale-105" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Карточка 2: Сертификаты гос. образца (С фото-превью) --}}
                            <div class="reveal relative overflow-hidden rounded-3xl border border-slate-200/90 bg-white/95 shadow-raised p-8 flex flex-col justify-between group backdrop-blur-sm transition-all duration-300 hover:border-amber-300 hover:shadow-lg">
                                <div>
                                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-50 text-amber-700 shadow-sm">
                                        <x-app-icon name="certificate" class="h-6 w-6" aria-hidden="true" />
                                    </div>
                                    <h3 class="mt-6 text-xl font-bold text-slate-900 tracking-tight">{{ __('Сертификаты гос. образца') }}</h3>
                                    <p class="mt-3 text-sm leading-relaxed text-slate-600">{{ __('Официальный сертификат с QR-кодом для проверки подлинности после каждого пройденного курса.') }}</p>
                                </div>
                                <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 shadow-md">
                                    <img src="{{ asset('images/feature_certificate.jpg') }}" alt="{{ __('Сертификаты гос. образца') }}" class="w-full h-36 object-cover transform transition-transform duration-500 group-hover:scale-105" />
                                </div>
                            </div>

                            {{-- Карточка 3: Тесты и проверка знаний --}}
                            <div class="reveal rounded-3xl border border-slate-200/90 bg-white/95 shadow-raised p-8 backdrop-blur-sm transition-all duration-300 hover:border-emerald-300 hover:shadow-lg">
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-700 shadow-sm">
                                    <x-app-icon name="check" class="h-6 w-6" aria-hidden="true" />
                                </div>
                                <h3 class="mt-6 text-xl font-bold text-slate-900 tracking-tight">{{ __('Тесты и проверка знаний') }}</h3>
                                <p class="mt-3 text-sm leading-relaxed text-slate-600">{{ __('Автоматическая и ручная проверка ответов, лимиты попыток и времени на каждый тест.') }}</p>
                            </div>

                            {{-- Карточка 4: Уроки по видеосвязи --}}
                            <div class="reveal rounded-3xl border border-slate-200/90 bg-white/95 shadow-raised p-8 backdrop-blur-sm transition-all duration-300 hover:border-cyan-300 hover:shadow-lg">
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-cyan-50 text-cyan-700 shadow-sm">
                                    <x-app-icon name="video" class="h-6 w-6" aria-hidden="true" />
                                </div>
                                <h3 class="mt-6 text-xl font-bold text-slate-900 tracking-tight">{{ __('Уроки по видеосвязи') }}</h3>
                                <p class="mt-3 text-sm leading-relaxed text-slate-600">{{ __('Живое общение с преподавателем онлайн в назначенное время урока.') }}</p>
                            </div>

                            {{-- Карточка 5: Посткурсовое сопровождение --}}
                            <div class="reveal rounded-3xl border border-slate-200/90 bg-white/95 shadow-raised p-8 backdrop-blur-sm transition-all duration-300 hover:border-purple-300 hover:shadow-lg">
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-purple-50 text-purple-700 shadow-sm">
                                    <x-app-icon name="plans" class="h-6 w-6" aria-hidden="true" />
                                </div>
                                <h3 class="mt-6 text-xl font-bold text-slate-900 tracking-tight">{{ __('Посткурсовое сопровождение') }}</h3>
                                <p class="mt-3 text-sm leading-relaxed text-slate-600">{{ __('Планы уроков, отчёты и мероприятия после курса — с подтверждением внедрения на практике.') }}</p>
                            </div>

                            {{-- Карточка 6: Аналитика и отчёты (Широкая на всю строку) --}}
                            <div class="reveal lg:col-span-3 rounded-3xl border border-slate-900 bg-gradient-to-r from-blue-950 via-slate-950 to-indigo-950 p-8 text-white shadow-raised flex flex-col md:flex-row items-center justify-between gap-6">
                                <div class="flex items-center gap-5">
                                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white/10 text-white ring-1 ring-white/20">
                                        <x-app-icon name="chart" class="h-7 w-7" aria-hidden="true" />
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-white tracking-tight">{{ __('Аналитика и отчёты') }}</h3>
                                        <p class="mt-1 text-sm text-slate-300 leading-relaxed max-w-xl">{{ __('Прогресс слушателей, результаты тестов и выгрузка отчётов по курсам в Excel.') }}</p>
                                    </div>
                                </div>
                                <div class="shrink-0">
                                    <a href="{{ auth()->check() ? url('/dashboard') : route('login') }}" class="inline-flex items-center gap-2 rounded-xl bg-white/10 hover:bg-white/20 px-5 py-3 text-xs font-bold text-white ring-1 ring-white/20 backdrop-blur-md transition-all">
                                        <span>{{ __('Личный кабинет') }}</span>
                                        <x-app-icon name="arrow-right" class="h-4 w-4" aria-hidden="true" />
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- ── Как проходит обучение (Шаги 1-4) ─────────────────────── --}}
                <section id="how" class="border-y border-slate-200/80 bg-gradient-to-b from-slate-100/80 via-slate-50/60 to-slate-100/80 py-20 sm:py-28 relative">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="reveal max-w-3xl">
                            <div class="inline-flex items-center gap-2 rounded-lg bg-blue-100/70 border border-blue-200/60 px-3.5 py-1 text-xs font-bold uppercase tracking-wider text-blue-800">
                                {{ __('Как проходит обучение') }}
                            </div>
                            <h2 class="mt-4 text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-slate-900 leading-tight">
                                {{ __('Четыре шага от приглашения до сертификата') }}
                            </h2>
                        </div>

                        <ol class="mt-14 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                            @php
                                $steps = [
                                    ['title' => __('Приглашение'), 'text' => __('Администратор центра создаёт учётную запись и назначает вам курс — приглашение приходит на email.')],
                                    ['title' => __('Обучение'), 'text' => __('Проходите модули и уроки: текст, видео, материалы для скачивания и уроки по видеосвязи.')],
                                    ['title' => __('Тестирование'), 'text' => __('После уроков — тест. Результат проверяется автоматически или администратором.')],
                                    ['title' => __('Документ'), 'text' => __('По итогам курса выдаётся сертификат или справка с QR-кодом для проверки подлинности.')],
                                ];
                            @endphp

                            @foreach ($steps as $index => $step)
                                <li class="reveal relative flex flex-col justify-between rounded-3xl border border-slate-200/80 bg-white p-8 shadow-raised transition-all duration-300 hover:-translate-y-1 hover:border-blue-300 hover:shadow-lg"
                                    style="--reveal-delay: {{ $index * 80 }}ms">
                                    <div>
                                        <div class="flex items-center justify-between">
                                            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-tr from-blue-700 to-indigo-600 text-base font-black text-white shadow-md">
                                                0{{ $index + 1 }}
                                            </div>
                                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Шаг {{ $index + 1 }}</span>
                                        </div>
                                        <h3 class="mt-6 text-xl font-bold text-slate-900 tracking-tight">{{ $step['title'] }}</h3>
                                        <p class="mt-3 text-sm leading-relaxed text-slate-600">{{ $step['text'] }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                </section>

                {{-- ── Соответствие Приказу МОН РК №95 ───────────────────────── --}}
                <section class="py-20 sm:py-28 relative">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="reveal relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-950 via-blue-950 to-indigo-950 px-6 py-14 sm:px-12 sm:py-18 shadow-2xl border border-blue-900/40">
                            {{-- Фоновые световые сферы --}}
                            <div class="pointer-events-none absolute -right-20 -top-24 h-96 w-96 rounded-full bg-blue-500/20 blur-3xl" aria-hidden="true"></div>
                            <div class="pointer-events-none absolute -left-20 -bottom-24 h-96 w-96 rounded-full bg-emerald-500/15 blur-3xl" aria-hidden="true"></div>

                            <div class="relative grid grid-cols-1 gap-12 lg:grid-cols-12 lg:gap-10 items-center">
                                <div class="lg:col-span-5">
                                    <span class="inline-flex items-center gap-2 rounded-full bg-blue-500/20 px-3.5 py-1.5 text-xs font-bold text-blue-300 ring-1 ring-inset ring-blue-400/30">
                                        <x-app-icon name="verified" class="h-4 w-4 text-blue-400" aria-hidden="true" />
                                        {{ __('Приказ МОН РК №95') }}
                                    </span>
                                    <h2 class="mt-5 text-3xl sm:text-4xl font-black tracking-tight text-white leading-tight">
                                        {{ __('Соответствие требованиям к повышению квалификации') }}
                                    </h2>
                                    <p class="mt-4 text-base leading-relaxed text-slate-300">
                                        {{ __('Платформа построена по правилам организации курсов повышения квалификации педагогов: продолжительность, форма итогового документа и проверка подлинности.') }}
                                    </p>
                                </div>

                                <div class="lg:col-span-7">
                                    <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                        @php
                                            $compliance = [
                                                ['icon' => 'clock', 'term' => __('От :hours академических часов', ['hours' => \App\Models\Course::MIN_ACADEMIC_HOURS_TO_PUBLISH]), 'desc' => __('Курс нельзя опубликовать, пока не набрана минимальная продолжительность.')],
                                                ['icon' => 'certificate', 'term' => __('Документ установленной формы'), 'desc' => __('Сертификат и справка о прослушивании с номером, датой и подписью руководителя.')],
                                                ['icon' => 'qr-code', 'term' => __('Проверка по QR-коду'), 'desc' => __('Подлинность любого документа проверяется публично, без обращения в центр.')],
                                                ['icon' => 'language', 'term' => __('Два языка интерфейса'), 'desc' => __('Русский и казахский — переключение в один клик на любой странице.')],
                                            ];
                                        @endphp

                                        @foreach ($compliance as $item)
                                            <div class="rounded-2xl border border-white/10 bg-white/5 p-5 backdrop-blur-md transition-all hover:bg-white/10">
                                                <div class="flex items-start gap-4">
                                                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-500/20 text-blue-300 ring-1 ring-white/15">
                                                        <x-app-icon name="{{ $item['icon'] }}" class="h-5 w-5" aria-hidden="true" />
                                                    </div>
                                                    <div>
                                                        <dt class="text-base font-bold text-white tracking-tight">{{ $item['term'] }}</dt>
                                                        <dd class="mt-1.5 text-xs sm:text-sm leading-relaxed text-slate-300/80">{{ $item['desc'] }}</dd>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- ── Проверка сертификата ──────────────────────────────────── --}}
                <section id="verify" class="border-t border-slate-200/80 bg-gradient-to-b from-slate-100/70 to-slate-50/90 py-20 sm:py-28 relative">
                    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                        <div class="reveal">
                            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-white text-blue-700 shadow-raised ring-1 ring-black/5">
                                <x-app-icon name="qr-code" class="h-8 w-8" aria-hidden="true" />
                            </div>
                            <h2 class="mt-6 text-3xl sm:text-4xl font-black tracking-tight text-slate-900 leading-tight">
                                {{ __('Проверка сертификата') }}
                            </h2>
                            <p class="mt-4 text-base sm:text-lg leading-relaxed text-slate-600">
                                {{ __('Отсканируйте QR-код на документе или введите код проверки — система покажет, кому, когда и за какой курс он выдан.') }}
                            </p>
                        </div>

                        <form class="reveal mt-8 flex flex-col gap-3 sm:flex-row shadow-raised rounded-2xl bg-white p-2.5 border border-slate-200/80"
                            style="--reveal-delay: 80ms"
                            data-verify-form
                            data-verify-url="{{ route('certificates.verify', ['qrToken' => '__TOKEN__']) }}">
                            <label for="certificate-token" class="sr-only">{{ __('Код проверки сертификата') }}</label>
                            <input id="certificate-token" name="token" type="text" required autocomplete="off" spellcheck="false"
                                placeholder="{{ __('Код проверки или ссылка с документа') }}"
                                class="w-full flex-1 rounded-xl border-0 bg-transparent px-4 py-3.5 text-sm text-slate-900 placeholder:text-slate-400 focus:ring-2 focus:ring-blue-600 font-medium">
                            <button type="submit"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-blue-700 via-blue-600 to-indigo-700 px-7 py-3.5 text-sm font-bold text-white shadow-raised transition-all duration-200 hover:from-blue-800 hover:to-indigo-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-600">
                                <x-app-icon name="search" class="h-4 w-4" aria-hidden="true" />
                                <span>{{ __('Проверить') }}</span>
                            </button>
                        </form>

                        <p class="reveal mt-4 text-xs sm:text-sm text-slate-500 font-medium" style="--reveal-delay: 120ms">
                            {{ __('Код указан под QR-кодом в нижней части документа.') }}
                        </p>
                    </div>
                </section>

                {{-- ── Финальный призыв к действию ───────────────────────────── --}}
                <section class="py-20 sm:py-28 relative">
                    <div class="reveal max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                        <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-8 sm:p-14 shadow-raised backdrop-blur-sm">
                            <h2 class="text-3xl sm:text-4xl font-black tracking-tight text-slate-900">
                                {{ __('Готовы продолжить обучение?') }}
                            </h2>
                            <p class="mt-4 text-base sm:text-lg leading-relaxed text-slate-600 max-w-2xl mx-auto">
                                {{ __('Войдите в личный кабинет, чтобы увидеть назначенные курсы, результаты тестов и выданные документы.') }}
                            </p>

                            <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
                                <a href="{{ auth()->check() ? url('/dashboard') : route('login') }}"
                                    class="group inline-flex w-full sm:w-auto items-center justify-center gap-2.5 rounded-xl bg-gradient-to-r from-blue-700 via-blue-600 to-indigo-700 px-8 py-4 text-sm font-bold text-white shadow-raised transition-all duration-200 hover:from-blue-800 hover:to-indigo-800 hover:-translate-y-0.5 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2">
                                    {{ auth()->check() ? __('Личный кабинет') : __('Войти в личный кабинет') }}
                                    <x-app-icon name="arrow-right" class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-1" aria-hidden="true" />
                                </a>
                            </div>

                            <p class="mt-6 text-xs sm:text-sm text-slate-500 font-medium">
                                {{ __('Нет доступа? Обратитесь к администратору учебного центра.') }}
                            </p>
                        </div>
                    </div>
                </section>
            </main>

            {{-- ── Подвал сайта ─────────────────────────────────────────── --}}
            <footer class="border-t border-slate-200/80 bg-white/95 backdrop-blur-sm">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                    <div class="flex flex-col gap-8 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <div class="flex items-center gap-3">
                                <x-application-logo class="h-8 w-8" />
                                <span class="text-lg font-black tracking-tight text-slate-900">ProfBilim<span class="text-blue-700">UP</span></span>
                            </div>
                            <p class="mt-3 max-w-sm text-sm font-medium text-slate-600">
                                {{ $organization->name_ru ?: config('app.name', 'ProfBilimUP') }}
                            </p>
                            @if ($organization->name_kk && $organization->name_kk !== $organization->name_ru)
                                <p class="text-xs text-slate-400 mt-1 font-medium">{{ $organization->name_kk }}</p>
                            @endif
                        </div>

                        <nav class="flex flex-col gap-3 text-sm font-semibold text-slate-600" aria-label="{{ __('Ссылки в подвале') }}">
                            <a href="#features" class="transition-colors hover:text-blue-700">{{ __('Возможности') }}</a>
                            <a href="#how" class="transition-colors hover:text-blue-700">{{ __('Как проходит обучение') }}</a>
                            <a href="#verify" class="transition-colors hover:text-blue-700">{{ __('Проверка сертификата') }}</a>
                            <a href="{{ route('login') }}" class="transition-colors hover:text-blue-700">{{ __('Войти') }}</a>
                        </nav>
                    </div>

                    <div class="mt-10 flex flex-col gap-3 border-t border-slate-100 pt-6 text-xs sm:text-sm font-medium text-slate-500 sm:flex-row sm:items-center sm:justify-between">
                        <span>&copy; {{ date('Y') }} {{ $organization->name_ru ?: config('app.name', 'ProfBilimUP') }}</span>
                        <span>{{ __('Все права защищены') }}</span>
                    </div>
                </div>
            </footer>
        </div>

        <script>
            (() => {
                const header = document.querySelector('[data-site-header]');
                const scrolledClasses = ['border-slate-200/80', 'bg-white/95', 'shadow-sm'];

                const syncHeader = () => {
                    const scrolled = window.scrollY > 12;

                    header?.classList.toggle('border-transparent', !scrolled);
                    scrolledClasses.forEach((className) => header?.classList.toggle(className, scrolled));
                };

                syncHeader();
                window.addEventListener('scroll', syncHeader, { passive: true });

                const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
                const revealables = document.querySelectorAll('.reveal');

                if (reduceMotion || !('IntersectionObserver' in window)) {
                    revealables.forEach((el) => el.classList.add('is-visible'));
                } else {
                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach((entry) => {
                            if (entry.isIntersecting) {
                                entry.target.classList.add('is-visible');
                                observer.unobserve(entry.target);
                            }
                        });
                    }, { rootMargin: '0px 0px -10% 0px', threshold: 0.1 });

                    revealables.forEach((el) => observer.observe(el));
                }

                // Проверка сертификата
                const form = document.querySelector('[data-verify-form]');

                form?.addEventListener('submit', (event) => {
                    event.preventDefault();

                    const raw = form.elements.token.value.trim().replace(/\/+$/, '');
                    const token = raw.split(/[\/?#]/).filter(Boolean).pop() ?? '';

                    if (token === '') {
                        form.elements.token.focus();

                        return;
                    }

                    window.location.href = form.dataset.verifyUrl.replace('__TOKEN__', encodeURIComponent(token));
                });
            })();
        </script>
    </body>
</html>
