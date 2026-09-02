<div class="py-12">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Слушатель') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Курс') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('План') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Справка') }}</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($assignments as $assignment)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $assignment->listener->listenerProfile?->full_name ?? $assignment->listener->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $assignment->course->title }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @switch($assignment->postCoursePlan?->status)
                                    @case(\App\Enums\PostCourseSupportStatus::Approved) <span class="text-green-600">{{ __('Одобрен') }}</span> @break
                                    @case(\App\Enums\PostCourseSupportStatus::Submitted) <span class="text-amber-600">{{ __('На проверке') }}</span> @break
                                    @case(\App\Enums\PostCourseSupportStatus::Rejected) <span class="text-red-600">{{ __('На доработке') }}</span> @break
                                    @default <span class="text-gray-400">{{ __('Не подан') }}</span>
                                @endswitch
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($assignment->postCourseReference)
                                    <span class="text-green-600">{{ __('Выдана') }}</span>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <a href="{{ route('admin.post-course-support.show', $assignment) }}" wire:navigate class="text-indigo-600 hover:text-indigo-900">{{ __('Открыть') }}</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">{{ __('Пока нет завершённых курсов.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
