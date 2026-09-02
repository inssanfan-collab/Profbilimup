<div>
    <x-dropdown align="right" width="w-80">
        <x-slot name="trigger">
            <button class="relative inline-flex items-center p-2 text-gray-500 hover:text-gray-700 focus:outline-none">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                @if ($unreadCount > 0)
                    <span class="absolute top-0 right-0 inline-flex items-center justify-center h-4 w-4 rounded-full bg-red-600 text-white text-[10px] leading-none">
                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                    </span>
                @endif
            </button>
        </x-slot>

        <x-slot name="content">
            <div class="flex items-center justify-between px-4 py-2">
                <span class="text-sm font-semibold text-gray-700">{{ __('Уведомления') }}</span>
                @if ($unreadCount > 0)
                    <button wire:click="markAllAsRead" type="button" class="text-xs text-indigo-600 hover:text-indigo-900">{{ __('Прочитать все') }}</button>
                @endif
            </div>

            <div class="max-h-80 overflow-y-auto">
                @forelse ($notifications as $notification)
                    <button wire:click="markAsRead('{{ $notification->id }}')" type="button"
                        class="w-full text-left px-4 py-3 text-sm border-t border-gray-100 hover:bg-gray-50 {{ $notification->read_at ? 'text-gray-500' : 'text-gray-900 font-medium' }}">
                        {{ $notification->data['message'] ?? '' }}
                        <div class="text-xs text-gray-400 font-normal mt-1">{{ $notification->created_at->diffForHumans() }}</div>
                    </button>
                @empty
                    <p class="px-4 py-6 text-sm text-gray-400 text-center">{{ __('Уведомлений нет.') }}</p>
                @endforelse
            </div>
        </x-slot>
    </x-dropdown>
</div>
