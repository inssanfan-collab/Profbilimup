<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <a href="{{ route('admin.courses.builder', $course) }}" wire:navigate class="text-sm text-indigo-600 hover:text-indigo-900">
            &larr; {{ __('Назад к структуре курса') }}
        </a>

        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <form wire:submit="schedule" class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[220px]">
                    <x-input-label for="name" :value="__('Название встречи')" />
                    <x-text-input wire:model="name" id="name" class="block mt-1 w-full" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="startsAt" :value="__('Дата и время (необязательно)')" />
                    <input type="datetime-local" wire:model="startsAt" id="startsAt"
                        class="mt-1 block rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <x-primary-button>{{ __('Запланировать видеоурок') }}</x-primary-button>
            </form>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
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
                                    <span class="text-gray-500">{{ __('Завершён') }}</span>
                                @else
                                    <span class="text-green-600">{{ __('Запланирован') }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right space-x-3">
                                @if ($meeting->status !== \App\Enums\VideoMeetingStatus::Ended)
                                    <button wire:click="joinAsModerator({{ $meeting->id }})" type="button" class="text-indigo-600 hover:text-indigo-900">{{ __('Войти') }}</button>
                                    <button wire:click="end({{ $meeting->id }})" wire:confirm="{{ __('Завершить видеоурок?') }}" type="button" class="text-red-600 hover:text-red-900">{{ __('Завершить') }}</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">{{ __('Видеоуроки пока не запланированы.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
