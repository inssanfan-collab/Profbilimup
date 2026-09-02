{{--
    Декоративная иллюстрация главной страницы: онлайн-урок, сертификат с QR-кодом
    и мотив казахского орнамента «қошқар мүйіз» на фоне.
--}}
<svg {{ $attributes->merge(['class' => 'w-full h-auto', 'viewBox' => '0 0 560 470']) }}
    xmlns="http://www.w3.org/2000/svg" role="presentation" aria-hidden="true" focusable="false">
    <defs>
        <linearGradient id="hero-sky" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0%" stop-color="#EFF6FF" />
            <stop offset="100%" stop-color="#E0EAFF" />
        </linearGradient>
        <linearGradient id="hero-screen" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0%" stop-color="#1D4ED8" stop-opacity="0.10" />
            <stop offset="100%" stop-color="#0EA5E9" stop-opacity="0.16" />
        </linearGradient>
        <filter id="hero-soft-shadow" x="-30%" y="-30%" width="160%" height="170%">
            <feDropShadow dx="0" dy="10" stdDeviation="14" flood-color="#1E293B" flood-opacity="0.10" />
        </filter>
        <filter id="hero-lift-shadow" x="-30%" y="-30%" width="160%" height="170%">
            <feDropShadow dx="0" dy="16" stdDeviation="20" flood-color="#1D4ED8" flood-opacity="0.18" />
        </filter>
    </defs>

    {{-- Фон --}}
    <ellipse cx="292" cy="238" rx="252" ry="216" fill="url(#hero-sky)" />
    <circle cx="474" cy="392" r="66" fill="#ECFDF5" />
    <circle cx="72" cy="118" r="46" fill="#DBEAFE" opacity="0.7" />

    {{-- Мотив «қошқар мүйіз»: парные рога-завитки --}}
    <g transform="translate(320 408)" stroke="#1D4ED8" stroke-opacity="0.16" stroke-width="7"
        fill="none" stroke-linecap="round">
        <path d="M0 10 C0 -14 18 -30 44 -30 C68 -30 84 -14 84 6 C84 24 70 36 54 36 C40 36 30 26 30 14 C30 4 38 -2 46 -2" />
        <path d="M0 10 C0 -14 -18 -30 -44 -30 C-68 -30 -84 -14 -84 6 C-84 24 -70 36 -54 36 C-40 36 -30 26 -30 14 C-30 4 -38 -2 -46 -2" />
        <path d="M0 10 L0 30" />
    </g>

    {{-- Академическая шапочка --}}
    <g class="float-accent" transform="translate(58 52)">
        <path d="M40 0 L80 17 L40 34 L0 17 Z" fill="#1D4ED8" />
        <path d="M16 24 L16 40 C16 48 26 53 40 53 C54 53 64 48 64 40 L64 24 L40 34 Z" fill="#2563EB" />
        <path d="M76 19 L76 42" stroke="#1D4ED8" stroke-width="3" stroke-linecap="round" />
        <circle cx="76" cy="46" r="5" fill="#F59E0B" />
    </g>

    {{-- Окно с видеоуроком --}}
    <g transform="translate(34 138)" filter="url(#hero-soft-shadow)">
        <rect width="304" height="216" rx="18" fill="#FFFFFF" stroke="#E8EDF5" />

        <circle cx="26" cy="24" r="4" fill="#E2E8F0" />
        <circle cx="40" cy="24" r="4" fill="#E2E8F0" />
        <circle cx="54" cy="24" r="4" fill="#E2E8F0" />
        <rect x="76" y="18" width="196" height="12" rx="6" fill="#F1F5F9" />
        <rect x="0" y="42" width="304" height="1" fill="#F1F5F9" />

        <rect x="22" y="62" width="260" height="112" rx="12" fill="url(#hero-screen)" />
        <circle cx="152" cy="118" r="27" fill="#1D4ED8" />
        <path d="M145 106 L167 118 L145 130 Z" fill="#FFFFFF" />

        <rect x="22" y="188" width="260" height="8" rx="4" fill="#E9EEF6" />
        <rect x="22" y="188" width="170" height="8" rx="4" fill="#1D4ED8" />
    </g>

    {{-- Карточка сертификата --}}
    <g transform="translate(296 48) rotate(-6)" filter="url(#hero-lift-shadow)">
        <rect width="232" height="180" rx="20" fill="#FFFFFF" stroke="#DBEAFE" stroke-width="1.5" />

        <circle cx="42" cy="44" r="21" fill="#16A34A" />
        <path d="M33 44 L39 50 L52 36" stroke="#FFFFFF" stroke-width="3.5" fill="none"
            stroke-linecap="round" stroke-linejoin="round" />

        <rect x="76" y="32" width="122" height="9" rx="4.5" fill="#0F172A" />
        <rect x="76" y="49" width="84" height="7" rx="3.5" fill="#94A3B8" />

        <rect x="24" y="84" width="184" height="1" fill="#F1F5F9" />

        <rect x="24" y="102" width="136" height="7" rx="3.5" fill="#CBD5E1" />
        <rect x="24" y="118" width="98" height="7" rx="3.5" fill="#E2E8F0" />
        <rect x="24" y="140" width="64" height="7" rx="3.5" fill="#E2E8F0" />

        {{-- QR-код --}}
        <g transform="translate(150 100)">
            <rect width="58" height="58" rx="8" fill="#F8FAFC" />
            <rect x="7" y="7" width="16" height="16" fill="none" stroke="#1D4ED8" stroke-width="2.5" />
            <rect x="11" y="11" width="8" height="8" fill="#1D4ED8" />
            <rect x="35" y="7" width="16" height="16" fill="none" stroke="#1D4ED8" stroke-width="2.5" />
            <rect x="39" y="11" width="8" height="8" fill="#1D4ED8" />
            <rect x="7" y="35" width="16" height="16" fill="none" stroke="#1D4ED8" stroke-width="2.5" />
            <rect x="11" y="39" width="8" height="8" fill="#1D4ED8" />
            <rect x="33" y="33" width="6" height="6" fill="#1D4ED8" />
            <rect x="43" y="33" width="6" height="6" fill="#1D4ED8" />
            <rect x="33" y="43" width="6" height="6" fill="#1D4ED8" />
            <rect x="45" y="45" width="6" height="6" fill="#1D4ED8" />
        </g>

        {{-- Знак подтверждения подлинности --}}
        <g transform="translate(206 -12)">
            <circle r="21" fill="#1D4ED8" />
            <path d="M-8 0 L-2 6 L9 -7" stroke="#FFFFFF" stroke-width="3.5" fill="none"
                stroke-linecap="round" stroke-linejoin="round" />
        </g>
    </g>

    {{-- Карточка прогресса курса --}}
    <g transform="translate(346 288)">
        <g class="float-accent" style="animation-delay: -3s">
            <rect width="168" height="84" rx="16" fill="#FFFFFF" stroke="#E8EDF5" filter="url(#hero-soft-shadow)" />
            <circle cx="44" cy="42" r="20" fill="none" stroke="#EDF2F8" stroke-width="7" />
            <circle cx="44" cy="42" r="20" fill="none" stroke="#16A34A" stroke-width="7" stroke-linecap="round"
                stroke-dasharray="126" stroke-dashoffset="32" transform="rotate(-90 44 42)" />
            <rect x="80" y="30" width="62" height="9" rx="4.5" fill="#CBD5E1" />
            <rect x="80" y="47" width="40" height="7" rx="3.5" fill="#E2E8F0" />
        </g>
    </g>

    {{-- Слушатели курса --}}
    <g transform="translate(54 384)">
        <g class="float-accent" style="animation-delay: -1.5s">
            <circle cx="20" cy="20" r="20" fill="#BFDBFE" stroke="#FFFFFF" stroke-width="3" />
            <circle cx="20" cy="15" r="7" fill="#1D4ED8" opacity="0.55" />
            <path d="M8 32 C10 24 30 24 32 32 Z" fill="#1D4ED8" opacity="0.55" />

            <circle cx="52" cy="20" r="20" fill="#A7F3D0" stroke="#FFFFFF" stroke-width="3" />
            <circle cx="52" cy="15" r="7" fill="#15803D" opacity="0.5" />
            <path d="M40 32 C42 24 62 24 64 32 Z" fill="#15803D" opacity="0.5" />

            <circle cx="84" cy="20" r="20" fill="#DBEAFE" stroke="#FFFFFF" stroke-width="3" />
            <circle cx="84" cy="15" r="7" fill="#1D4ED8" opacity="0.35" />
            <path d="M72 32 C74 24 94 24 96 32 Z" fill="#1D4ED8" opacity="0.35" />
        </g>
    </g>

    {{-- Мелкие акценты --}}
    <circle class="float-accent" cx="252" cy="64" r="11" fill="#BFDBFE" style="animation-delay: -2s" />
    <circle class="float-accent" cx="22" cy="252" r="8" fill="#A7F3D0" style="animation-delay: -4s" />
    <circle class="float-accent" cx="418" cy="428" r="7" fill="#BFDBFE" style="animation-delay: -5s" />
</svg>
