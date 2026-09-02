<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $organization->name_ru ?: config('app.name', 'BilimUP') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=golos-text:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            @keyframes rise-in {
                from { opacity: 0; transform: translateY(16px); }
                to { opacity: 1; transform: translateY(0); }
            }
            @keyframes float-in {
                from { opacity: 0; transform: translateY(24px) scale(0.97); }
                to { opacity: 1; transform: translateY(0) scale(1); }
            }
            @keyframes drift {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-10px); }
            }
            .hero-enter {
                animation: rise-in 0.7s cubic-bezier(0.16, 1, 0.3, 1) both;
            }
            .hero-enter-delay {
                animation: rise-in 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.12s both;
            }
            .hero-illustration {
                animation: float-in 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.2s both;
            }
            .hero-illustration .float-accent {
                animation: drift 6s ease-in-out infinite;
            }
            @media (prefers-reduced-motion: reduce) {
                .hero-enter, .hero-enter-delay, .hero-illustration, .hero-illustration .float-accent {
                    animation: none;
                }
            }
        </style>
    </head>
    <body class="font-sans antialiased text-gray-900">
        <div class="min-h-screen bg-gray-50">
            <header class="border-b border-gray-100 bg-white">
                <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <x-application-logo class="h-9 w-9 rounded-lg" />
                        <span class="text-lg font-bold text-gray-900">{{ config('app.name', 'BilimUP') }}</span>
                    </div>

                    <livewire:welcome.navigation />
                </div>
            </header>

            <main>
                <section class="relative overflow-hidden">
                    <div class="max-w-6xl mx-auto px-6 pt-16 pb-20 sm:pt-24 sm:pb-28 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                        <div class="lg:col-span-7 hero-enter">
                            <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-sm font-medium text-blue-700">
                                {{ __('Учебный центр повышения квалификации педагогов') }}
                            </span>

                            <h1 class="mt-6 text-4xl sm:text-5xl font-extrabold tracking-tight text-gray-900">
                                {{ $organization->name_ru ?: config('app.name', 'BilimUP') }}
                            </h1>

                            @if ($organization->name_kk && $organization->name_kk !== $organization->name_ru)
                                <p class="mt-2 text-xl text-gray-500">{{ $organization->name_kk }}</p>
                            @endif

                            <p class="mt-6 text-lg leading-relaxed text-gray-600 max-w-xl">
                                {{ __('Онлайн-курсы, тесты и сертификаты государственного образца для развития профессиональных компетенций педагогов Казахстана.') }}
                            </p>

                            <div class="mt-10 flex flex-wrap items-center gap-4 hero-enter-delay">
                                <a href="{{ route('login') }}"
                                    class="inline-flex items-center px-6 py-3 bg-blue-700 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 transition ease-in-out duration-150">
                                    {{ __('Войти в личный кабинет') }}
                                </a>

                                @if ($publishedCoursesCount > 0)
                                    <span class="text-sm text-gray-500">
                                        {{ __('Курсов в каталоге:') }} {{ $publishedCoursesCount }}
                                    </span>
                                @endif
                            </div>

                            <p class="mt-6 text-sm text-gray-400">
                                {{ __('Регистрация закрыта — доступ к курсам предоставляется администратором центра по приглашению.') }}
                            </p>
                        </div>

                        <div class="lg:col-span-5 hero-illustration" aria-hidden="true">
                            <svg viewBox="0 0 480 400" class="w-full h-auto max-w-md mx-auto">
                                <ellipse cx="250" cy="200" rx="220" ry="180" fill="#EFF6FF" />
                                <circle class="float-accent" cx="82" cy="70" r="22" fill="#DBEAFE" />
                                <circle class="float-accent" cx="424" cy="308" r="16" fill="#D1FAE5" style="animation-delay: -2s" />
                                <circle class="float-accent" cx="404" cy="66" r="9" fill="#BFDBFE" style="animation-delay: -4s" />

                                <!-- secondary card: video lesson -->
                                <g transform="translate(52 208) rotate(-8)">
                                    <rect width="172" height="112" rx="14" fill="#ffffff" stroke="#E5E7EB" />
                                    <rect x="16" y="16" width="140" height="58" rx="8" fill="#1D4ED8" opacity="0.07" />
                                    <circle cx="86" cy="45" r="20" fill="#1D4ED8" />
                                    <path d="M80 36 L98 45 L80 54 Z" fill="#ffffff" />
                                    <rect x="16" y="86" width="92" height="8" rx="4" fill="#E5E7EB" />
                                    <rect x="16" y="98" width="60" height="6" rx="3" fill="#EEF2F7" />
                                </g>

                                <!-- main card: certificate -->
                                <g transform="translate(150 56)" filter="url(#cardShadow)">
                                    <rect width="248" height="184" rx="18" fill="#ffffff" stroke="#DBEAFE" stroke-width="1.5" />

                                    <circle cx="42" cy="42" r="20" fill="#16A34A" />
                                    <path d="M33 42 L39 48 L52 34" stroke="#ffffff" stroke-width="3.5" fill="none" stroke-linecap="round" stroke-linejoin="round" />

                                    <rect x="74" y="30" width="132" height="9" rx="4.5" fill="#111827" />
                                    <rect x="74" y="47" width="92" height="7" rx="3.5" fill="#9CA3AF" />

                                    <rect x="24" y="82" width="200" height="1" fill="#F1F5F9" />

                                    <rect x="24" y="100" width="152" height="7" rx="3.5" fill="#CBD5E1" />
                                    <rect x="24" y="114" width="112" height="7" rx="3.5" fill="#CBD5E1" />

                                    <!-- QR block -->
                                    <g transform="translate(166 138)">
                                        <rect width="56" height="56" rx="6" fill="#F8FAFC" />
                                        <rect x="6" y="6" width="16" height="16" fill="none" stroke="#1D4ED8" stroke-width="2.5" />
                                        <rect x="10" y="10" width="8" height="8" fill="#1D4ED8" />
                                        <rect x="34" y="6" width="16" height="16" fill="none" stroke="#1D4ED8" stroke-width="2.5" />
                                        <rect x="38" y="10" width="8" height="8" fill="#1D4ED8" />
                                        <rect x="6" y="34" width="16" height="16" fill="none" stroke="#1D4ED8" stroke-width="2.5" />
                                        <rect x="10" y="38" width="8" height="8" fill="#1D4ED8" />
                                        <rect x="32" y="32" width="6" height="6" fill="#1D4ED8" />
                                        <rect x="42" y="32" width="6" height="6" fill="#1D4ED8" />
                                        <rect x="32" y="42" width="6" height="6" fill="#1D4ED8" />
                                        <rect x="44" y="44" width="6" height="6" fill="#1D4ED8" />
                                    </g>

                                    <!-- verified badge -->
                                    <g transform="translate(218 -12)">
                                        <circle r="20" fill="#1D4ED8" />
                                        <path d="M-8 0 L-2 6 L9 -7" stroke="#ffffff" stroke-width="3.5" fill="none" stroke-linecap="round" stroke-linejoin="round" />
                                    </g>
                                </g>

                                <defs>
                                    <filter id="cardShadow" x="-30%" y="-30%" width="160%" height="160%">
                                        <feDropShadow dx="0" dy="14" stdDeviation="18" flood-color="#1D4ED8" flood-opacity="0.14" />
                                    </filter>
                                </defs>
                            </svg>
                        </div>
                    </div>
                </section>

                <section class="border-t border-gray-100 bg-white">
                    <div class="max-w-6xl mx-auto px-6 py-16">
                        <div class="grid grid-cols-1 gap-10 sm:grid-cols-2 lg:grid-cols-4">
                            <div class="group">
                                <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-blue-50 text-blue-700 transition-colors group-hover:bg-blue-700 group-hover:text-white">
                                    <x-app-icon name="courses" class="h-6 w-6" />
                                </div>
                                <h3 class="mt-4 text-base font-semibold text-gray-800">{{ __('Курсы и видеоуроки') }}</h3>
                                <p class="mt-2 text-sm leading-relaxed text-gray-500">
                                    {{ __('Модули и уроки с текстом, видео и файлами для скачивания — проходите курс в удобном темпе.') }}
                                </p>
                            </div>

                            <div class="group">
                                <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-blue-50 text-blue-700 transition-colors group-hover:bg-blue-700 group-hover:text-white">
                                    <x-app-icon name="check" class="h-6 w-6" />
                                </div>
                                <h3 class="mt-4 text-base font-semibold text-gray-800">{{ __('Тесты и проверка знаний') }}</h3>
                                <p class="mt-2 text-sm leading-relaxed text-gray-500">
                                    {{ __('Автоматическая и ручная проверка ответов, лимиты попыток и времени на каждый тест.') }}
                                </p>
                            </div>

                            <div class="group">
                                <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-blue-50 text-blue-700 transition-colors group-hover:bg-blue-700 group-hover:text-white">
                                    <x-app-icon name="certificate" class="h-6 w-6" />
                                </div>
                                <h3 class="mt-4 text-base font-semibold text-gray-800">{{ __('Сертификаты гос. образца') }}</h3>
                                <p class="mt-2 text-sm leading-relaxed text-gray-500">
                                    {{ __('Официальный сертификат с QR-кодом для проверки подлинности после каждого пройденного курса.') }}
                                </p>
                            </div>

                            <div class="group">
                                <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-blue-50 text-blue-700 transition-colors group-hover:bg-blue-700 group-hover:text-white">
                                    <x-app-icon name="video" class="h-6 w-6" />
                                </div>
                                <h3 class="mt-4 text-base font-semibold text-gray-800">{{ __('Уроки по видеосвязи') }}</h3>
                                <p class="mt-2 text-sm leading-relaxed text-gray-500">
                                    {{ __('Живое общение с преподавателем онлайн в назначенное время урока.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </section>
            </main>

            <footer class="border-t border-gray-100">
                <div class="max-w-6xl mx-auto px-6 py-8 flex flex-col sm:flex-row items-center justify-between gap-2 text-sm text-gray-400">
                    <span>&copy; {{ date('Y') }} {{ $organization->name_ru ?: config('app.name', 'BilimUP') }}</span>
                    <span>{{ config('app.name', 'BilimUP') }}</span>
                </div>
            </footer>
        </div>
    </body>
</html>
