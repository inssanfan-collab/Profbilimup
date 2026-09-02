<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $organization->name_ru ?: config('app.name', 'BilimUP') }}</title>
        <meta name="description" content="{{ __('Онлайн-курсы, тесты и сертификаты государственного образца для развития профессиональных компетенций педагогов Казахстана.') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=golos-text:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            /* --- Фоновые декорации ------------------------------------------------ */
            .hero-aurora {
                background:
                    radial-gradient(56rem 32rem at 78% -12%, rgb(29 78 216 / 0.13), transparent 62%),
                    radial-gradient(40rem 28rem at 2% 8%, rgb(16 185 129 / 0.10), transparent 60%);
            }
            .dot-grid {
                background-image: radial-gradient(rgb(15 23 42 / 0.07) 1px, transparent 1px);
                background-size: 22px 22px;
                mask-image: linear-gradient(to bottom, #000, transparent 70%);
                -webkit-mask-image: linear-gradient(to bottom, #000, transparent 70%);
            }

            /* --- Появление ------------------------------------------------------- */
            @keyframes rise-in {
                from { opacity: 0; transform: translateY(18px); }
                to { opacity: 1; transform: translateY(0); }
            }
            @keyframes float-in {
                from { opacity: 0; transform: translateY(26px) scale(0.96); }
                to { opacity: 1; transform: translateY(0) scale(1); }
            }
            @keyframes drift {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-10px); }
            }
            .hero-enter { animation: rise-in 0.7s cubic-bezier(0.16, 1, 0.3, 1) both; }
            .hero-enter-delay { animation: rise-in 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.12s both; }
            .hero-enter-delay-2 { animation: rise-in 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.22s both; }
            .hero-illustration { animation: float-in 0.9s cubic-bezier(0.16, 1, 0.3, 1) 0.18s both; }
            .hero-illustration .float-accent { animation: drift 6s ease-in-out infinite; }

            /* Прокрутка: элементы проявляются по мере попадания во вьюпорт */
            .reveal {
                opacity: 0;
                transform: translateY(20px);
                transition: opacity 0.55s cubic-bezier(0.16, 1, 0.3, 1), transform 0.55s cubic-bezier(0.16, 1, 0.3, 1);
                transition-delay: var(--reveal-delay, 0ms);
            }
            .reveal.is-visible { opacity: 1; transform: none; }

            /* Якорные переходы не должны прятать заголовок под липкой шапкой */
            section[id] { scroll-margin-top: 6rem; }

            @media (prefers-reduced-motion: reduce) {
                html { scroll-behavior: auto; }
                .hero-enter, .hero-enter-delay, .hero-enter-delay-2,
                .hero-illustration, .hero-illustration .float-accent { animation: none; }
                .reveal { opacity: 1; transform: none; transition: none; }
            }
        </style>
    </head>
    <body class="font-sans antialiased text-gray-900 bg-white">
        <a href="#main"
            class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-50 focus:rounded-lg focus:bg-blue-700 focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:text-white">
            {{ __('Перейти к содержимому') }}
        </a>

        <div class="min-h-screen flex flex-col">
            <header data-site-header
                class="sticky top-0 z-40 border-b border-transparent bg-white transition-colors duration-200">
                <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between gap-6">
                    <a href="{{ route('home') }}" class="flex items-center gap-2.5 rounded-lg focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2">
                        <x-application-logo class="h-9 w-9 rounded-lg" />
                        <span class="text-lg font-bold tracking-tight text-gray-900">{{ config('app.name', 'BilimUP') }}</span>
                    </a>

                    <nav class="hidden lg:flex items-center gap-8 text-sm font-medium text-gray-600" aria-label="{{ __('Разделы страницы') }}">
                        <a href="#features" class="rounded transition-colors hover:text-blue-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-4">{{ __('Возможности') }}</a>
                        <a href="#how" class="rounded transition-colors hover:text-blue-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-4">{{ __('Как проходит обучение') }}</a>
                        <a href="#verify" class="rounded transition-colors hover:text-blue-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-4">{{ __('Проверка сертификата') }}</a>
                    </nav>

                    <livewire:welcome.navigation />
                </div>
            </header>

            <main id="main" class="flex-1">
                {{-- ── Hero ──────────────────────────────────────────────────── --}}
                <section class="relative overflow-hidden hero-aurora">
                    <div class="absolute inset-0 dot-grid pointer-events-none" aria-hidden="true"></div>

                    <div class="relative max-w-6xl mx-auto px-6 pt-16 pb-20 sm:pt-24 sm:pb-28 grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
                        <div class="lg:col-span-7 hero-enter">
                            <span class="inline-flex items-center gap-2 rounded-full border border-blue-100 bg-white/70 px-3 py-1.5 text-xs sm:text-sm font-medium text-blue-700 shadow-sm backdrop-blur">
                                <x-app-icon name="shield-check" class="h-4 w-4" aria-hidden="true" />
                                {{ __('Учебный центр повышения квалификации педагогов') }}
                            </span>

                            <h1 class="mt-6 text-4xl sm:text-5xl lg:text-[3.4rem] font-extrabold leading-[1.08] tracking-tight text-gray-900">
                                {{ $organization->name_ru ?: config('app.name', 'BilimUP') }}
                            </h1>

                            @if ($organization->name_kk && $organization->name_kk !== $organization->name_ru)
                                <p class="mt-3 text-lg sm:text-xl text-gray-500">{{ $organization->name_kk }}</p>
                            @endif

                            <p class="mt-6 text-lg leading-relaxed text-gray-600 max-w-xl">
                                {{ __('Онлайн-курсы, тесты и сертификаты государственного образца для развития профессиональных компетенций педагогов Казахстана.') }}
                            </p>

                            <div class="mt-9 flex flex-col sm:flex-row sm:items-center gap-3 hero-enter-delay">
                                <a href="{{ auth()->check() ? url('/dashboard') : route('login') }}"
                                    class="group inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-blue-700 rounded-xl font-semibold text-sm text-white shadow-raised transition duration-150 hover:bg-blue-800 hover:-translate-y-0.5 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2">
                                    {{ auth()->check() ? __('Личный кабинет') : __('Войти в личный кабинет') }}
                                    <x-app-icon name="arrow-right" class="h-4 w-4 transition-transform duration-150 group-hover:translate-x-0.5" aria-hidden="true" />
                                </a>

                                <a href="#verify"
                                    class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl border border-gray-200 bg-white font-semibold text-sm text-gray-700 transition duration-150 hover:border-gray-300 hover:bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2">
                                    <x-app-icon name="qr-code" class="h-4 w-4 text-blue-700" aria-hidden="true" />
                                    {{ __('Проверить сертификат') }}
                                </a>
                            </div>

                            <p class="mt-6 flex items-start gap-2 text-sm text-gray-500 max-w-xl hero-enter-delay-2">
                                <x-app-icon name="lock" class="mt-0.5 h-4 w-4 shrink-0 text-gray-400" aria-hidden="true" />
                                <span>{{ __('Регистрация закрыта — доступ к курсам предоставляется администратором центра по приглашению.') }}</span>
                            </p>
                        </div>

                        <div class="lg:col-span-5 hero-illustration">
                            <x-hero-illustration class="w-full h-auto max-w-lg mx-auto" />
                        </div>
                    </div>
                </section>

                {{-- ── Показатели центра ─────────────────────────────────────── --}}
                @php
                    $stats = array_values(array_filter([
                        ['value' => $publishedCoursesCount, 'label' => __('Курсов в каталоге')],
                        ['value' => $academicHoursSum, 'label' => __('Академических часов')],
                        ['value' => $listenersCount, 'label' => __('Слушателей центра')],
                        ['value' => $certificatesCount, 'label' => __('Выдано документов')],
                    ], fn ($stat) => $stat['value'] > 0));
                @endphp

                @if ($stats)
                    <section class="relative -mt-8 sm:-mt-12 pb-4" aria-label="{{ __('Показатели центра') }}">
                        <div class="max-w-6xl mx-auto px-6">
                            <dl class="reveal grid grid-cols-2 gap-px overflow-hidden rounded-2xl border border-gray-100 bg-gray-100 shadow-card {{ [1 => 'md:grid-cols-1', 2 => 'md:grid-cols-2', 3 => 'md:grid-cols-3', 4 => 'md:grid-cols-4'][count($stats)] }}">
                                @foreach ($stats as $stat)
                                    <div class="bg-white px-6 py-7 text-center">
                                        <dt class="sr-only">{{ $stat['label'] }}</dt>
                                        <dd>
                                            <span class="block text-3xl font-extrabold tracking-tight text-blue-700 sm:text-4xl">
                                                {{ number_format($stat['value'], 0, ',', ' ') }}
                                            </span>
                                            <span class="mt-1.5 block text-sm text-gray-500">{{ $stat['label'] }}</span>
                                        </dd>
                                    </div>
                                @endforeach
                            </dl>
                        </div>
                    </section>
                @endif

                {{-- ── Возможности ───────────────────────────────────────────── --}}
                <section id="features" class="py-20 sm:py-24">
                    <div class="max-w-6xl mx-auto px-6">
                        <div class="reveal max-w-2xl">
                            <p class="text-sm font-semibold uppercase tracking-wider text-blue-700">{{ __('Возможности') }}</p>
                            <h2 class="mt-3 text-3xl sm:text-4xl font-bold tracking-tight text-gray-900">
                                {{ __('Всё обучение — в одной платформе') }}
                            </h2>
                            <p class="mt-4 text-lg leading-relaxed text-gray-600">
                                {{ __('От назначения курса до выдачи сертификата и посткурсового сопровождения — без бумажных журналов и разрозненных чатов.') }}
                            </p>
                        </div>

                        <div class="mt-12 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                            @php
                                $features = [
                                    ['icon' => 'courses', 'title' => __('Курсы и видеоуроки'), 'text' => __('Модули и уроки с текстом, видео и файлами для скачивания — проходите курс в удобном темпе.')],
                                    ['icon' => 'check', 'title' => __('Тесты и проверка знаний'), 'text' => __('Автоматическая и ручная проверка ответов, лимиты попыток и времени на каждый тест.')],
                                    ['icon' => 'certificate', 'title' => __('Сертификаты гос. образца'), 'text' => __('Официальный сертификат с QR-кодом для проверки подлинности после каждого пройденного курса.')],
                                    ['icon' => 'video', 'title' => __('Уроки по видеосвязи'), 'text' => __('Живое общение с преподавателем онлайн в назначенное время урока.')],
                                    ['icon' => 'plans', 'title' => __('Посткурсовое сопровождение'), 'text' => __('Планы уроков, отчёты и мероприятия после курса — с подтверждением внедрения на практике.')],
                                    ['icon' => 'chart', 'title' => __('Аналитика и отчёты'), 'text' => __('Прогресс слушателей, результаты тестов и выгрузка отчётов по курсам в Excel.')],
                                ];
                            @endphp

                            @foreach ($features as $index => $feature)
                                <div class="reveal group relative rounded-2xl border border-gray-100 bg-white p-6 shadow-card transition duration-200 hover:-translate-y-1 hover:border-blue-100 hover:shadow-raised"
                                    style="--reveal-delay: {{ $index * 60 }}ms">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-700 transition-colors duration-200 group-hover:bg-blue-700 group-hover:text-white">
                                        <x-app-icon name="{{ $feature['icon'] }}" class="h-6 w-6" aria-hidden="true" />
                                    </div>
                                    <h3 class="mt-5 text-base font-semibold text-gray-900">{{ $feature['title'] }}</h3>
                                    <p class="mt-2 text-sm leading-relaxed text-gray-500">{{ $feature['text'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>

                {{-- ── Как проходит обучение ─────────────────────────────────── --}}
                <section id="how" class="border-y border-gray-100 bg-gray-50 py-20 sm:py-24">
                    <div class="max-w-6xl mx-auto px-6">
                        <div class="reveal max-w-2xl">
                            <p class="text-sm font-semibold uppercase tracking-wider text-blue-700">{{ __('Как проходит обучение') }}</p>
                            <h2 class="mt-3 text-3xl sm:text-4xl font-bold tracking-tight text-gray-900">
                                {{ __('Четыре шага от приглашения до сертификата') }}
                            </h2>
                        </div>

                        <ol class="mt-12 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                            @php
                                $steps = [
                                    ['title' => __('Приглашение'), 'text' => __('Администратор центра создаёт учётную запись и назначает вам курс — приглашение приходит на email.')],
                                    ['title' => __('Обучение'), 'text' => __('Проходите модули и уроки: текст, видео, материалы для скачивания и уроки по видеосвязи.')],
                                    ['title' => __('Тестирование'), 'text' => __('После уроков — тест. Результат проверяется автоматически или администратором.')],
                                    ['title' => __('Документ'), 'text' => __('По итогам курса выдаётся сертификат или справка с QR-кодом для проверки подлинности.')],
                                ];
                            @endphp

                            @foreach ($steps as $index => $step)
                                <li class="reveal relative rounded-2xl border border-gray-100 bg-white p-6 shadow-card"
                                    style="--reveal-delay: {{ $index * 80 }}ms">
                                    @unless ($loop->last)
                                        <span class="pointer-events-none absolute top-11 -right-3 hidden h-px w-6 bg-gray-200 lg:block" aria-hidden="true"></span>
                                    @endunless

                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-700 text-sm font-bold text-white">
                                        {{ $index + 1 }}
                                    </div>
                                    <h3 class="mt-5 text-base font-semibold text-gray-900">{{ $step['title'] }}</h3>
                                    <p class="mt-2 text-sm leading-relaxed text-gray-500">{{ $step['text'] }}</p>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                </section>

                {{-- ── Соответствие требованиям ──────────────────────────────── --}}
                <section class="py-20 sm:py-24">
                    <div class="max-w-6xl mx-auto px-6">
                        <div class="reveal relative overflow-hidden rounded-3xl bg-gradient-to-br from-blue-900 via-blue-800 to-slate-900 px-6 py-14 sm:px-12 sm:py-16">
                            <div class="pointer-events-none absolute -right-16 -top-24 h-72 w-72 rounded-full bg-blue-500/20 blur-3xl" aria-hidden="true"></div>

                            <div class="relative grid grid-cols-1 gap-12 lg:grid-cols-12 lg:gap-8">
                                <div class="lg:col-span-5">
                                    <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1.5 text-xs font-medium text-blue-100 ring-1 ring-inset ring-white/15">
                                        <x-app-icon name="verified" class="h-4 w-4" aria-hidden="true" />
                                        {{ __('Приказ МОН РК №95') }}
                                    </span>
                                    <h2 class="mt-5 text-3xl sm:text-4xl font-bold tracking-tight text-white">
                                        {{ __('Соответствие требованиям к повышению квалификации') }}
                                    </h2>
                                    <p class="mt-4 text-base leading-relaxed text-blue-100/80">
                                        {{ __('Платформа построена по правилам организации курсов повышения квалификации педагогов: продолжительность, форма итогового документа и проверка подлинности.') }}
                                    </p>
                                </div>

                                <div class="lg:col-span-7">
                                    <dl class="grid grid-cols-1 gap-x-8 gap-y-8 sm:grid-cols-2">
                                        @php
                                            $compliance = [
                                                ['icon' => 'clock', 'term' => __('От :hours академических часов', ['hours' => \App\Models\Course::MIN_ACADEMIC_HOURS_TO_PUBLISH]), 'desc' => __('Курс нельзя опубликовать, пока не набрана минимальная продолжительность.')],
                                                ['icon' => 'certificate', 'term' => __('Документ установленной формы'), 'desc' => __('Сертификат и справка о прослушивании с номером, датой и подписью руководителя.')],
                                                ['icon' => 'qr-code', 'term' => __('Проверка по QR-коду'), 'desc' => __('Подлинность любого документа проверяется публично, без обращения в центр.')],
                                                ['icon' => 'language', 'term' => __('Два языка интерфейса'), 'desc' => __('Русский и казахский — переключение в один клик на любой странице.')],
                                            ];
                                        @endphp

                                        @foreach ($compliance as $item)
                                            <div class="flex gap-4">
                                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-white/10 text-blue-100 ring-1 ring-inset ring-white/15">
                                                    <x-app-icon name="{{ $item['icon'] }}" class="h-5 w-5" aria-hidden="true" />
                                                </div>
                                                <div>
                                                    <dt class="text-base font-semibold text-white">{{ $item['term'] }}</dt>
                                                    <dd class="mt-1.5 text-sm leading-relaxed text-blue-100/70">{{ $item['desc'] }}</dd>
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
                <section id="verify" class="border-t border-gray-100 bg-gray-50 py-20 sm:py-24">
                    <div class="max-w-3xl mx-auto px-6 text-center">
                        <div class="reveal">
                            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-white text-blue-700 shadow-card">
                                <x-app-icon name="qr-code" class="h-7 w-7" aria-hidden="true" />
                            </div>
                            <h2 class="mt-6 text-3xl sm:text-4xl font-bold tracking-tight text-gray-900">
                                {{ __('Проверка сертификата') }}
                            </h2>
                            <p class="mt-4 text-lg leading-relaxed text-gray-600">
                                {{ __('Отсканируйте QR-код на документе или введите код проверки — система покажет, кому, когда и за какой курс он выдан.') }}
                            </p>
                        </div>

                        <form class="reveal mt-8 flex flex-col gap-3 sm:flex-row"
                            style="--reveal-delay: 80ms"
                            data-verify-form
                            data-verify-url="{{ route('certificates.verify', ['qrToken' => '__TOKEN__']) }}">
                            <label for="certificate-token" class="sr-only">{{ __('Код проверки сертификата') }}</label>
                            <input id="certificate-token" name="token" type="text" required autocomplete="off" spellcheck="false"
                                placeholder="{{ __('Код проверки или ссылка с документа') }}"
                                class="w-full flex-1 rounded-xl border-gray-200 bg-white px-4 py-3.5 text-sm text-gray-900 placeholder:text-gray-400 shadow-sm transition focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20">
                            <button type="submit"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-700 px-6 py-3.5 text-sm font-semibold text-white shadow-raised transition duration-150 hover:bg-blue-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2">
                                <x-app-icon name="search" class="h-4 w-4" aria-hidden="true" />
                                {{ __('Проверить') }}
                            </button>
                        </form>

                        <p class="reveal mt-4 text-sm text-gray-400" style="--reveal-delay: 120ms">
                            {{ __('Код указан под QR-кодом в нижней части документа.') }}
                        </p>
                    </div>
                </section>

                {{-- ── Финальный призыв ──────────────────────────────────────── --}}
                <section class="py-20 sm:py-24">
                    <div class="reveal max-w-3xl mx-auto px-6 text-center">
                        <h2 class="text-3xl sm:text-4xl font-bold tracking-tight text-gray-900">
                            {{ __('Готовы продолжить обучение?') }}
                        </h2>
                        <p class="mt-4 text-lg leading-relaxed text-gray-600">
                            {{ __('Войдите в личный кабинет, чтобы увидеть назначенные курсы, результаты тестов и выданные документы.') }}
                        </p>

                        <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
                            <a href="{{ auth()->check() ? url('/dashboard') : route('login') }}"
                                class="group inline-flex w-full sm:w-auto items-center justify-center gap-2 rounded-xl bg-blue-700 px-7 py-3.5 text-sm font-semibold text-white shadow-raised transition duration-150 hover:bg-blue-800 hover:-translate-y-0.5 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2">
                                {{ auth()->check() ? __('Личный кабинет') : __('Войти в личный кабинет') }}
                                <x-app-icon name="arrow-right" class="h-4 w-4 transition-transform duration-150 group-hover:translate-x-0.5" aria-hidden="true" />
                            </a>
                        </div>

                        <p class="mt-6 text-sm text-gray-500">
                            {{ __('Нет доступа? Обратитесь к администратору учебного центра.') }}
                        </p>
                    </div>
                </section>
            </main>

            <footer class="border-t border-gray-100 bg-white">
                <div class="max-w-6xl mx-auto px-6 py-10">
                    <div class="flex flex-col gap-8 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <div class="flex items-center gap-2.5">
                                <x-application-logo class="h-8 w-8 rounded-lg" />
                                <span class="text-base font-bold tracking-tight text-gray-900">{{ config('app.name', 'BilimUP') }}</span>
                            </div>
                            <p class="mt-3 max-w-sm text-sm text-gray-500">
                                {{ $organization->name_ru ?: config('app.name', 'BilimUP') }}
                            </p>
                            @if ($organization->name_kk && $organization->name_kk !== $organization->name_ru)
                                <p class="text-sm text-gray-400">{{ $organization->name_kk }}</p>
                            @endif
                        </div>

                        <nav class="flex flex-col gap-2.5 text-sm text-gray-500" aria-label="{{ __('Ссылки в подвале') }}">
                            <a href="#features" class="transition-colors hover:text-blue-700">{{ __('Возможности') }}</a>
                            <a href="#how" class="transition-colors hover:text-blue-700">{{ __('Как проходит обучение') }}</a>
                            <a href="#verify" class="transition-colors hover:text-blue-700">{{ __('Проверка сертификата') }}</a>
                            <a href="{{ route('login') }}" class="transition-colors hover:text-blue-700">{{ __('Войти') }}</a>
                        </nav>
                    </div>

                    <div class="mt-8 flex flex-col gap-2 border-t border-gray-100 pt-6 text-sm text-gray-400 sm:flex-row sm:items-center sm:justify-between">
                        <span>&copy; {{ date('Y') }} {{ $organization->name_ru ?: config('app.name', 'BilimUP') }}</span>
                        <span>{{ __('Все права защищены') }}</span>
                    </div>
                </div>
            </footer>
        </div>

        <script>
            (() => {
                // Липкая шапка получает границу и подложку только после начала прокрутки.
                const header = document.querySelector('[data-site-header]');
                const scrolledClasses = ['border-gray-200/80', 'bg-white/85', 'backdrop-blur-md'];

                const syncHeader = () => {
                    const scrolled = window.scrollY > 8;

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

                // Проверка сертификата: принимаем как код, так и полную ссылку с документа.
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
