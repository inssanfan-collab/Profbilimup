<div class="py-12">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-x-auto rounded-xl border border-gray-100 shadow-card">
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
                                    @case(\App\Enums\PostCourseSupportStatus::Approved) <x-badge color="green">{{ __('Одобрен') }}</x-badge> @break
                                    @case(\App\Enums\PostCourseSupportStatus::Submitted) <x-badge color="amber">{{ __('На проверке') }}</x-badge> @break
                                    @case(\App\Enums\PostCourseSupportStatus::Rejected) <x-badge color="red">{{ __('На доработке') }}</x-badge> @break
                                    @default <x-badge color="gray">{{ __('Не подан') }}</x-badge>
                                @endswitch
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($assignment->postCourseReference)
                                    <x-badge color="green">{{ __('Выдана') }}</x-badge>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <a href="{{ route('admin.post-course-support.show', $assignment) }}" wire:navigate class="text-blue-700 hover:text-blue-900">{{ __('Открыть') }}</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <x-empty-state icon="events">{{ __('Пока нет завершённых курсов.') }}</x-empty-state>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
