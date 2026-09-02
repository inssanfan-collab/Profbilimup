<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <a href="{{ route('admin.courses.builder', $course) }}" wire:navigate class="text-sm text-blue-700 hover:text-blue-900">
            &larr; {{ __('Назад к структуре курса') }}
        </a>

        <x-card>
            <form wire:submit="assign" class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[200px]">
                    <x-input-label for="listenerId" :value="__('Слушатель')" />
                    <select wire:model="listenerId" id="listenerId" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-600 focus:ring-blue-600">
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
        </x-card>

        <div class="bg-white overflow-hidden rounded-xl border border-gray-100 shadow-card">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Слушатель') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Срок') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Статус') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Прогресс') }}</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($assignments as $assignment)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $assignment->listener->listenerProfile?->full_name ?? $assignment->listener->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $assignment->deadline?->format('d.m.Y') ?? '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($assignment->isOverdue())
                                    <x-badge color="red">{{ __('Просрочен') }}</x-badge>
                                @else
                                    @switch($assignment->status)
                                        @case(\App\Enums\AssignmentStatus::Completed)
                                            <x-badge color="green">{{ __('Завершён') }}</x-badge>
                                            @break
                                        @case(\App\Enums\AssignmentStatus::InProgress)
                                            <x-badge color="blue">{{ __('В процессе') }}</x-badge>
                                            @break
                                        @default
                                            <x-badge color="gray">{{ __('Назначен') }}</x-badge>
                                    @endswitch
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $assignment->progressPercent() }}%</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                @if ($assignment->certificate)
                                    <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($assignment->certificate->pdf_path) }}" target="_blank" class="text-blue-700 hover:text-blue-900">
                                        {{ $assignment->certificate->typeLabel() }}
                                    </a>
                                @elseif ($assignment->status !== \App\Enums\AssignmentStatus::Completed && $assignment->agreement_accepted_at)
                                    <button wire:click="closeAsAttendanceOnly({{ $assignment->id }})" wire:confirm="{{ __('Закрыть курс для слушателя и выдать справку о прослушивании вместо сертификата?') }}" type="button" class="text-amber-600 hover:text-amber-800">
                                        {{ __('Закрыть как «прослушал»') }}
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <x-empty-state icon="users">{{ __('Курс пока никому не назначен.') }}</x-empty-state>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
