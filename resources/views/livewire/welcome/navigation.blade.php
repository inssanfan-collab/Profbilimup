<nav class="flex items-center gap-3">
    <div class="flex items-center rounded-lg border border-gray-200 bg-white p-0.5 text-xs font-semibold" role="group" aria-label="{{ __('Язык интерфейса') }}">
        <a href="{{ route('locale.update', 'ru') }}"
            @if (app()->getLocale() === 'ru') aria-current="true" @endif
            class="rounded-md px-2.5 py-1.5 transition-colors {{ app()->getLocale() === 'ru' ? 'bg-blue-50 text-blue-700' : 'text-gray-500 hover:text-gray-900' }}">RU</a>
        <a href="{{ route('locale.update', 'kk') }}"
            @if (app()->getLocale() === 'kk') aria-current="true" @endif
            class="rounded-md px-2.5 py-1.5 transition-colors {{ app()->getLocale() === 'kk' ? 'bg-blue-50 text-blue-700' : 'text-gray-500 hover:text-gray-900' }}">KK</a>
    </div>

    @auth
        <a href="{{ url('/dashboard') }}"
            class="inline-flex items-center rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white transition duration-150 hover:bg-blue-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2">
            {{ __('Личный кабинет') }}
        </a>
    @else
        <a href="{{ route('login') }}"
            class="inline-flex items-center rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white transition duration-150 hover:bg-blue-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2">
            {{ __('Войти') }}
        </a>
    @endauth
</nav>
