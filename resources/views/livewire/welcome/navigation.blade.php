<nav class="flex items-center gap-3">
    <div class="flex items-center rounded-xl border border-slate-200 bg-slate-100/90 p-1 text-xs font-semibold shadow-sm" role="group" aria-label="{{ __('Язык интерфейса') }}">
        <a href="{{ route('locale.update', 'ru') }}"
            @if (app()->getLocale() === 'ru') aria-current="true" @endif
            class="rounded-lg px-3 py-1.5 transition-all duration-150 {{ app()->getLocale() === 'ru' ? 'bg-white text-blue-700 shadow-sm font-bold' : 'text-slate-600 hover:text-slate-900' }}">RU</a>
        <a href="{{ route('locale.update', 'kk') }}"
            @if (app()->getLocale() === 'kk') aria-current="true" @endif
            class="rounded-lg px-3 py-1.5 transition-all duration-150 {{ app()->getLocale() === 'kk' ? 'bg-white text-blue-700 shadow-sm font-bold' : 'text-slate-600 hover:text-slate-900' }}">KK</a>
    </div>

    @auth
        <a href="{{ url('/dashboard') }}"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-700 px-5 py-2.5 text-sm font-bold text-white shadow-raised transition-all duration-150 hover:bg-blue-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2">
            {{ __('Личный кабинет') }}
        </a>
    @else
        <a href="{{ route('login') }}"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-700 px-5 py-2.5 text-sm font-bold text-white shadow-raised transition-all duration-150 hover:bg-blue-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2">
            {{ __('Войти') }}
        </a>
    @endauth
</nav>
