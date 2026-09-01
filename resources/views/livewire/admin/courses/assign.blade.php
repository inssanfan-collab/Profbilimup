<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <a href="{{ route('admin.courses.builder', $course) }}" wire:navigate class="text-sm text-indigo-600 hover:text-indigo-900">
            &larr; {{ __('Назад к структуре курса') }}
        </a>

        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <form wire:submit="assign" class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[200px]">
                    <x-input-label for="listenerId" :value="__('Слушатель')" />
                    <select wire:model="listenerId" id="listenerId" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">{{ __('Выберите слушателя') }}</option>
                        @foreach ($availableListeners as $listener)
                            <option value="{{ $listener->id }}">{{ $listener->listenerProfile?->full_name ?? $listener->name }} ({{ $listener->email }})</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('listenerId')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="deadline" :value="__('Срок прохождения (необязательно)')" />
                    <x-text-input wire:model="deadline" id="deadline" type="date" class="block mt-1" />
                    <x-input-error :messages="$errors->get('deadline')" class="mt-2" />
                </div>

                <x-primary-button>{{ __('Назначить') }}</x-primary-button>
            </form>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Слушатель') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Срок') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Статус') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Прогресс') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($assignments as $assignment)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $assignment->listener->listenerProfile?->full_name ?? $assignment->listener->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $assignment->deadline?->format('d.m.Y') ?? '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($assignment->isOverdue())
                                    <span class="text-red-600">{{ __('Просрочен') }}</span>
                                @else
                                    <span>
                                        @switch($assignment->status)
                                            @case(\App\Enums\AssignmentStatus::Completed) {{ __('Завершён') }} @break
                                            @case(\App\Enums\AssignmentStatus::InProgress) {{ __('В процессе') }} @break
                                            @default {{ __('Назначен') }}
                                        @endswitch
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $assignment->progressPercent() }}%</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">{{ __('Курс пока никому не назначен.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
