<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $organization->name_ru ?: config('app.name', 'BilimUP') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-900">
        <div class="min-h-screen bg-gray-50">
            <header class="border-b border-gray-100 bg-white">
                <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-indigo-600 text-white font-bold">
                            {{ mb_substr($organization->name_ru ?: config('app.name', 'B'), 0, 1) }}
                        </div>
                        <span class="text-lg font-semibold text-gray-800">{{ config('app.name', 'BilimUP') }}</span>
                    </div>

                    <livewire:welcome.navigation />
                </div>
            </header>

            <main>
                <section class="max-w-6xl mx-auto px-6 pt-16 pb-20 sm:pt-24 sm:pb-28">
                    <div class="max-w-3xl">
                        <span class="inline-flex items-center rounded-full bg-indigo-50 px-3 py-1 text-sm font-medium text-indigo-700">
                            {{ __('Учебный центр повышения квалификации педагогов') }}
                        </span>

                        <h1 class="mt-6 text-4xl sm:text-5xl font-extrabold tracking-tight text-gray-900">
                            {{ $organization->name_ru ?: config('app.name', 'BilimUP') }}
                        </h1>

                        @if ($organization->name_kk && $organization->name_kk !== $organization->name_ru)
                            <p class="mt-2 text-xl text-gray-500">{{ $organization->name_kk }}</p>
                        @endif

                        <p class="mt-6 text-lg leading-relaxed text-gray-600">
                            {{ __('Онлайн-курсы, тесты и сертификаты государственного образца для развития профессиональных компетенций педагогов Казахстана.') }}
                        </p>

                        <div class="mt-10 flex flex-wrap items-center gap-4">
                            <a href="{{ route('login') }}"
                                class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
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
                </section>

                <section class="border-t border-gray-100 bg-white">
                    <div class="max-w-6xl mx-auto px-6 py-16">
                        <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
                            <div>
                                <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                                    </svg>
                                </div>
                                <h3 class="mt-4 text-base font-semibold text-gray-800">{{ __('Курсы и видеоуроки') }}</h3>
                                <p class="mt-2 text-sm leading-relaxed text-gray-500">
                                    {{ __('Модули и уроки с текстом, видео и файлами для скачивания — проходите курс в удобном темпе.') }}
                                </p>
                            </div>

                            <div>
                                <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </div>
                                <h3 class="mt-4 text-base font-semibold text-gray-800">{{ __('Тесты и проверка знаний') }}</h3>
                                <p class="mt-2 text-sm leading-relaxed text-gray-500">
                                    {{ __('Автоматическая и ручная проверка ответов, лимиты попыток и времени на каждый тест.') }}
                                </p>
                            </div>

                            <div>
                                <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.26 10.147 1.246-.618M9.75 4.5v3.086m5.502 2.561 1.245.618M12 10.5v3m-6.75 3v3.75m13.5-3.75v3.75M6 21h12M9.75 4.5H14.25a2.25 2.25 0 0 1 2.244 2.077L18.75 21H5.25l2.256-14.423A2.25 2.25 0 0 1 9.75 4.5Z" />
                                    </svg>
                                </div>
                                <h3 class="mt-4 text-base font-semibold text-gray-800">{{ __('Сертификаты гос. образца') }}</h3>
                                <p class="mt-2 text-sm leading-relaxed text-gray-500">
                                    {{ __('Официальный сертификат с QR-кодом для проверки подлинности после каждого пройденного курса.') }}
                                </p>
                            </div>

                            <div>
                                <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z" />
                                    </svg>
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
