<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
        @if (auth()->user()->hasPermission(\App\Enums\CuratorPermission::Courses) && (auth()->user()->isAdmin() || auth()->user()->has_all_courses_access))
            <div class="flex items-center justify-end">
                <a href="{{ route('admin.courses.create') }}" wire:navigate
                    class="inline-flex items-center px-4 py-2.5 bg-blue-700 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-blue-800">
                    {{ __('Новый курс') }}
                </a>
            </div>
        @endif

        <div class="bg-white overflow-x-auto rounded-xl border border-gray-100 shadow-card">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Название') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Часы') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Модулей') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Статус') }}</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($courses as $course)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $course->title }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $course->academic_hours }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $course->modules_count }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @switch($course->status)
                                    @case(\App\Enums\CourseStatus::Published)
                                        <x-badge color="green">{{ __('Опубликован') }}</x-badge>
                                        @break
                                    @case(\App\Enums\CourseStatus::Archived)
                                        <x-badge color="gray">{{ __('В архиве') }}</x-badge>
                                        @break
                                    @default
                                        <x-badge color="amber">{{ __('Черновик') }}</x-badge>
                                @endswitch
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right space-x-3">
                                @if (auth()->user()->hasPermission(\App\Enums\CuratorPermission::Courses))
                                    <a href="{{ route('admin.courses.builder', $course) }}" wire:navigate class="text-blue-700 hover:text-blue-900">{{ __('Редактировать содержимое') }}</a>
                                    <a href="{{ route('admin.courses.edit', $course) }}" wire:navigate class="text-gray-600 hover:text-gray-900">{{ __('Параметры') }}</a>
                                @endif
                                @if (auth()->user()->hasPermission(\App\Enums\CuratorPermission::VideoMeetings))
                                    <a href="{{ route('admin.video-meetings.index', $course) }}" wire:navigate class="text-blue-700 hover:text-blue-900">{{ __('Видеоуроки') }}</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <x-empty-state icon="courses">{{ __('Курсов пока нет.') }}</x-empty-state>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $courses->links() }}
    </div>
</div>
