<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">
        @forelse ($assignments as $assignment)
            <a href="{{ route('listener.courses.show', $assignment) }}" wire:navigate class="block group">
                <x-card class="transition group-hover:shadow-raised group-hover:border-blue-100">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">{{ $assignment->course->title }}</h3>
                            <p class="text-sm text-gray-500 mt-1">
                                {{ __('Часов:') }} {{ $assignment->course->academic_hours }}
                                @if ($assignment->deadline)
                                    · {{ __('Срок до') }} {{ $assignment->deadline->format('d.m.Y') }}
                                @endif
                            </p>
                        </div>

                        @if ($assignment->isOverdue())
                            <x-badge color="red">{{ __('Просрочен') }}</x-badge>
                        @elseif ($assignment->status === \App\Enums\AssignmentStatus::Completed)
                            <x-badge color="green">{{ __('Завершён') }}</x-badge>
                        @else
                            <span class="text-sm font-semibold text-gray-700 shrink-0">{{ $assignment->progressPercent() }}%</span>
                        @endif
                    </div>

                    <div class="mt-3 w-full bg-gray-100 rounded-full h-2">
                        <div class="bg-blue-700 h-2 rounded-full" style="width: {{ $assignment->progressPercent() }}%"></div>
                    </div>
                </x-card>
            </a>

            @if ($assignment->status === \App\Enums\AssignmentStatus::Completed)
                <a href="{{ route('listener.post-course-support.show', $assignment) }}" wire:navigate
                    class="flex items-center justify-between bg-blue-50 rounded-xl px-6 py-3 hover:bg-blue-100 transition -mt-2 text-sm font-medium text-blue-700">
                    <span>{{ __('Посткурсовое сопровождение') }}</span>
                    <x-app-icon name="chevron-right" class="h-4 w-4" />
                </a>
            @endif
        @empty
            <x-card>
                <x-empty-state icon="courses">{{ __('Вам пока не назначено ни одного курса.') }}</x-empty-state>
            </x-card>
        @endforelse
    </div>
</div>
