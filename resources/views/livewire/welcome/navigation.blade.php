<nav class="flex items-center gap-4">
    <div class="flex items-center text-xs text-gray-400 gap-1">
        <a href="{{ route('locale.update', 'ru') }}" class="{{ app()->getLocale() === 'ru' ? 'font-semibold text-gray-700' : 'hover:text-gray-600' }}">RU</a>
        <span>/</span>
        <a href="{{ route('locale.update', 'kk') }}" class="{{ app()->getLocale() === 'kk' ? 'font-semibold text-gray-700' : 'hover:text-gray-600' }}">KK</a>
    </div>

    @auth
        <a href="{{ url('/dashboard') }}"
            class="inline-flex items-center px-4 py-2.5 bg-blue-700 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 transition ease-in-out duration-150">
            {{ __('Личный кабинет') }}
        </a>
    @else
        <a href="{{ route('login') }}"
            class="inline-flex items-center px-4 py-2.5 bg-blue-700 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 transition ease-in-out duration-150">
            {{ __('Войти') }}
        </a>
    @endauth
</nav>
