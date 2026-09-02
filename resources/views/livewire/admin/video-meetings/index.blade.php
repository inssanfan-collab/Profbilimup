<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
        @if (auth()->user()->hasPermission(\App\Enums\CuratorPermission::Courses))
            <a href="{{ route('admin.courses.builder', $course) }}" wire:navigate class="text-sm text-blue-700 hover:text-blue-900">
                &larr; {{ __('Назад к структуре курса') }}
            </a>
        @else
            <a href="{{ route('admin.courses.index') }}" wire:navigate class="text-sm text-blue-700 hover:text-blue-900">
                &larr; {{ __('Назад к курсам') }}
            </a>
        @endif

        <x-card>
            <form wire:submit="schedule" class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[220px]">
                    <x-input-label for="name" :value="__('Название встречи')" />
                    <x-text-input wire:model="name" id="name" class="block mt-1 w-full" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="startsAt" :value="__('Дата и время (необязательно)')" />
                    <input type="datetime-local" wire:model="startsAt" id="startsAt"
                        class="mt-1 block rounded-lg border-gray-300 shadow-sm focus:border-blue-600 focus:ring-blue-600">
                </div>

                <x-primary-button>{{ __('Запланировать видеоурок') }}</x-primary-button>
            </form>
        </x-card>

        <div class="bg-white overflow-hidden rounded-xl border border-gray-100 shadow-card">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Название') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Дата и время') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Статус') }}</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($meetings as $meeting)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $meeting->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $meeting->starts_at?->format('d.m.Y H:i') ?? '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($meeting->status === \App\Enums\VideoMeetingStatus::Ended)
                                    <x-badge color="gray">{{ __('Завершён') }}</x-badge>
                                @else
                                    <x-badge color="green">{{ __('Запланирован') }}</x-badge>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right space-x-3">
                                @if ($meeting->status !== \App\Enums\VideoMeetingStatus::Ended)
                                    <button wire:click="joinAsModerator({{ $meeting->id }})" type="button" class="text-blue-700 hover:text-blue-900">{{ __('Войти') }}</button>
                                    <button wire:click="end({{ $meeting->id }})" wire:confirm="{{ __('Завершить видеоурок?') }}" type="button" class="text-red-600 hover:text-red-900">{{ __('Завершить') }}</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <x-empty-state icon="video">{{ __('Видеоуроки пока не запланированы.') }}</x-empty-state>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
