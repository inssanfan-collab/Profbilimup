<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">
        @forelse ($assignments as $assignment)
            <a href="{{ route('listener.courses.show', $assignment) }}" wire:navigate
                class="block bg-white shadow-sm sm:rounded-lg p-6 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">{{ $assignment->course->title }}</h3>
                        <p class="text-sm text-gray-500 mt-1">
                            {{ __('Часов:') }} {{ $assignment->course->academic_hours }}
                            @if ($assignment->deadline)
                                · {{ __('Срок до') }} {{ $assignment->deadline->format('d.m.Y') }}
                            @endif
                        </p>
                    </div>
                    <div class="text-right">
                        @if ($assignment->isOverdue())
                            <span class="text-red-600 text-sm font-semibold">{{ __('Просрочен') }}</span>
                        @elseif ($assignment->status === \App\Enums\AssignmentStatus::Completed)
                            <span class="text-green-600 text-sm font-semibold">{{ __('Завершён') }}</span>
                        @else
                            <span class="text-sm font-semibold text-gray-700">{{ $assignment->progressPercent() }}%</span>
                        @endif
                    </div>
                </div>

                <div class="mt-3 w-full bg-gray-100 rounded-full h-2">
                    <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $assignment->progressPercent() }}%"></div>
                </div>
            </a>
        @empty
            <div class="bg-white shadow-sm sm:rounded-lg p-6 text-gray-500">
                {{ __('Вам пока не назначено ни одного курса.') }}
            </div>
        @endforelse
    </div>
</div>
