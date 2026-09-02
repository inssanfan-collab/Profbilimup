<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
        <div class="flex items-center justify-between gap-4">
            <input
                type="search"
                wire:model.live.debounce.300ms="search"
                placeholder="{{ __('Поиск по имени или email') }}"
                class="w-full max-w-sm rounded-lg border-gray-300 shadow-sm focus:border-blue-600 focus:ring-blue-600"
            >

            <a href="{{ route('admin.listeners.create') }}" wire:navigate
                class="inline-flex items-center px-4 py-2.5 bg-blue-700 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-blue-800">
                {{ __('Добавить слушателя') }}
            </a>
        </div>

        @if (session('inviteLink'))
            <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl p-4">
                <p class="font-semibold">{{ __('Слушатель создан. Передайте ему ссылку для установки пароля:') }}</p>
                <input
                    type="text"
                    readonly
                    value="{{ session('inviteLink') }}"
                    onclick="this.select(); document.execCommand('copy');"
                    class="mt-2 w-full rounded-lg border-gray-300 text-sm bg-white"
                >
            </div>
        @endif

        <div class="bg-white overflow-hidden rounded-xl border border-gray-100 shadow-card">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('ФИО') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Место работы') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Статус') }}</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($listeners as $listener)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $listener->listenerProfile?->full_name ?? $listener->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $listener->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $listener->listenerProfile?->workplace ?? '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($listener->must_set_password)
                                    <x-badge color="amber">{{ __('Ждёт установки пароля') }}</x-badge>
                                @else
                                    <x-badge color="green">{{ __('Активен') }}</x-badge>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right space-x-3">
                                @if ($listener->must_set_password)
                                    <button
                                        type="button"
                                        onclick="navigator.clipboard.writeText('{{ $this->inviteLink($listener) }}'); this.textContent = '{{ __('Скопировано') }}';"
                                        class="text-blue-700 hover:text-blue-900"
                                    >{{ __('Копировать ссылку') }}</button>
                                @endif
                                <a href="{{ route('admin.analytics.listener', $listener) }}" wire:navigate class="text-blue-700 hover:text-blue-900">{{ __('Прогресс') }}</a>
                                <a href="{{ route('admin.listeners.edit', $listener) }}" wire:navigate class="text-gray-600 hover:text-gray-900">{{ __('Редактировать') }}</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <x-empty-state icon="users">{{ __('Слушателей пока нет.') }}</x-empty-state>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $listeners->links() }}
    </div>
</div>
