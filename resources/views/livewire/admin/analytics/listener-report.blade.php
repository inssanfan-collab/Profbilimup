<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <a href="{{ route('admin.listeners.index') }}" wire:navigate class="text-sm text-indigo-600 hover:text-indigo-900">
            &larr; {{ __('Назад к слушателям') }}
        </a>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Курс') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Срок') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Статус') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Прогресс') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Документ') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($assignments as $assignment)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $assignment->course->title }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $assignment->deadline?->format('d.m.Y') ?? '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($assignment->isOverdue())
                                    <span class="text-red-600">{{ __('Просрочен') }}</span>
                                @else
                                    @switch($assignment->status)
                                        @case(\App\Enums\AssignmentStatus::Completed) {{ __('Завершён') }} @break
                                        @case(\App\Enums\AssignmentStatus::InProgress) {{ __('В процессе') }} @break
                                        @default {{ __('Назначен') }}
                                    @endswitch
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $assignment->progressPercent() }}%</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($assignment->certificate)
                                    <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($assignment->certificate->pdf_path) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">
                                        {{ $assignment->certificate->typeLabel() }}
                                    </a>
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">{{ __('Слушателю пока не назначено ни одного курса.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
