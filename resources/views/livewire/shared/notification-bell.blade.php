<div>
    <x-dropdown align="right" width="w-80">
        <x-slot name="trigger">
            <button class="relative inline-flex items-center p-2 text-gray-500 hover:text-gray-700 focus:outline-none">
                <x-app-icon name="bell" class="h-6 w-6" />
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
                    <button wire:click="markAllAsRead" type="button" class="text-xs text-blue-700 hover:text-blue-900">{{ __('Прочитать все') }}</button>
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
                    <x-empty-state icon="bell">{{ __('Уведомлений нет.') }}</x-empty-state>
                @endforelse
            </div>
        </x-slot>
    </x-dropdown>
</div>
