<div class="py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
            <x-card flat class="border-0 shadow-card">
                <p class="text-sm text-gray-500">{{ __('Слушателей') }}</p>
                <p class="mt-1 text-3xl font-bold text-gray-900">{{ $totalListeners }}</p>
            </x-card>
            <x-card flat class="border-0 shadow-card">
                <p class="text-sm text-gray-500">{{ __('Опубликовано курсов') }}</p>
                <p class="mt-1 text-3xl font-bold text-gray-900">{{ $totalCourses }}</p>
            </x-card>
            <x-card flat class="border-0 shadow-card">
                <p class="text-sm text-gray-500">{{ __('В процессе') }}</p>
                <p class="mt-1 text-3xl font-bold text-gray-900">{{ $inProgressAssignments }}</p>
            </x-card>
            <x-card flat class="border-0 shadow-card">
                <p class="text-sm text-gray-500">{{ __('Просрочено') }}</p>
                <p class="mt-1 text-3xl font-bold {{ $overdueAssignments > 0 ? 'text-red-600' : 'text-gray-900' }}">{{ $overdueAssignments }}</p>
            </x-card>
            <x-card flat class="border-0 shadow-card">
                <p class="text-sm text-gray-500">{{ __('На проверке') }}</p>
                <p class="mt-1 text-3xl font-bold {{ $pendingReviewCount > 0 ? 'text-amber-600' : 'text-gray-900' }}">{{ $pendingReviewCount }}</p>
            </x-card>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('admin.courses.create') }}" wire:navigate
                class="flex items-center gap-3 rounded-xl border border-gray-100 bg-white p-4 shadow-card hover:border-blue-200 hover:bg-blue-50/40 transition">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-700">
                    <x-app-icon name="courses" class="h-5 w-5" />
                </span>
                <span class="text-sm font-semibold text-gray-800">{{ __('Создать курс') }}</span>
            </a>

            <a href="{{ route('admin.listeners.create') }}" wire:navigate
                class="flex items-center gap-3 rounded-xl border border-gray-100 bg-white p-4 shadow-card hover:border-blue-200 hover:bg-blue-50/40 transition">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-700">
                    <x-app-icon name="users" class="h-5 w-5" />
                </span>
                <span class="text-sm font-semibold text-gray-800">{{ __('Пригласить слушателя') }}</span>
            </a>

            <a href="{{ route('admin.test-review.index') }}" wire:navigate
                class="flex items-center gap-3 rounded-xl border border-gray-100 bg-white p-4 shadow-card hover:border-blue-200 hover:bg-blue-50/40 transition">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-700">
                    <x-app-icon name="plans" class="h-5 w-5" />
                </span>
                <span class="text-sm font-semibold text-gray-800">{{ __('Очередь проверки тестов') }}</span>
                @if ($pendingReviewCount > 0)
                    <x-badge color="amber" class="ms-auto">{{ $pendingReviewCount }}</x-badge>
                @endif
            </a>

            <a href="{{ route('admin.post-course-support.index') }}" wire:navigate
                class="flex items-center gap-3 rounded-xl border border-gray-100 bg-white p-4 shadow-card hover:border-blue-200 hover:bg-blue-50/40 transition">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-700">
                    <x-app-icon name="events" class="h-5 w-5" />
                </span>
                <span class="text-sm font-semibold text-gray-800">{{ __('Посткурсовое сопровождение') }}</span>
            </a>
        </div>

        <x-card :title="__('Недавние назначения')">
            <ul class="divide-y divide-gray-100 -mx-6">
                @forelse ($recentAssignments as $assignment)
                    <li class="flex items-center justify-between gap-4 px-6 py-3">
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-gray-800 truncate">{{ $assignment->course->title }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ $assignment->listener->listenerProfile?->full_name ?? $assignment->listener->name }}</p>
                        </div>

                        @switch($assignment->status)
                            @case(\App\Enums\AssignmentStatus::Completed)
                                <x-badge color="green">{{ __('Завершён') }}</x-badge>
                                @break
                            @case(\App\Enums\AssignmentStatus::Overdue)
                                <x-badge color="red">{{ __('Просрочен') }}</x-badge>
                                @break
                            @case(\App\Enums\AssignmentStatus::InProgress)
                                <x-badge color="blue">{{ __('В процессе') }}</x-badge>
                                @break
                            @default
                                <x-badge color="gray">{{ __('Назначен') }}</x-badge>
                        @endswitch
                    </li>
                @empty
                    <x-empty-state icon="courses">{{ __('Пока нет назначений.') }}</x-empty-state>
                @endforelse
            </ul>
        </x-card>
    </div>
</div>
