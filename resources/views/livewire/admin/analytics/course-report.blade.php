<div class="py-12">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.analytics.index') }}" wire:navigate class="text-sm text-indigo-600 hover:text-indigo-900">
                &larr; {{ __('Назад к аналитике') }}
            </a>
            <a href="{{ route('admin.analytics.course.export', $course) }}" class="text-sm text-indigo-600 hover:text-indigo-900">
                {{ __('Экспорт в Excel') }}
            </a>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Слушатель') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Место работы') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Срок') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Статус') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Прогресс') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Документ') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($assignments as $assignment)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $assignment->listener->listenerProfile?->full_name ?? $assignment->listener->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $assignment->listener->listenerProfile?->workplace ?? '—' }}</td>
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
                                        {{ $assignment->certificate->type === \App\Enums\CertificateType::Certificate ? __('Сертификат') : __('Справка') }}
                                    </a>
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">{{ __('Курс пока никому не назначен.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
