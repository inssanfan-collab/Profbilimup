<div class="py-12">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
            <x-card flat class="border-0 shadow-card !p-4">
                <p class="text-xs text-gray-500 uppercase">{{ __('Слушателей') }}</p>
                <p class="text-2xl font-semibold text-gray-800">{{ $totalListeners }}</p>
            </x-card>
            <x-card flat class="border-0 shadow-card !p-4">
                <p class="text-xs text-gray-500 uppercase">{{ __('Курсов') }}</p>
                <p class="text-2xl font-semibold text-gray-800">{{ $totalCourses }}</p>
            </x-card>
            <x-card flat class="border-0 shadow-card !p-4">
                <p class="text-xs text-gray-500 uppercase">{{ __('Назначений') }}</p>
                <p class="text-2xl font-semibold text-gray-800">{{ $totalAssignments }}</p>
            </x-card>
            <x-card flat class="border-0 shadow-card !p-4">
                <p class="text-xs text-gray-500 uppercase">{{ __('Завершено') }}</p>
                <p class="text-2xl font-semibold text-green-600">{{ $completionRate }}%</p>
            </x-card>
            <x-card flat class="border-0 shadow-card !p-4">
                <p class="text-xs text-gray-500 uppercase">{{ __('Просрочено') }}</p>
                <p class="text-2xl font-semibold text-red-600">{{ $overdueAssignments }}</p>
            </x-card>
        </div>

        <div class="bg-white overflow-x-auto rounded-xl border border-gray-100 shadow-card">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Курс') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Назначено') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Завершено') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Просрочено') }}</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($courses as $course)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $course->title }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $course->assignments_count }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $course->completed_assignments_count }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($course->overdue_assignments_count > 0)
                                    <span class="text-red-600">{{ $course->overdue_assignments_count }}</span>
                                @else
                                    0
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <a href="{{ route('admin.analytics.course', $course) }}" wire:navigate class="text-blue-700 hover:text-blue-900">{{ __('Подробнее') }}</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <x-empty-state icon="chart">{{ __('Курсов пока нет.') }}</x-empty-state>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
