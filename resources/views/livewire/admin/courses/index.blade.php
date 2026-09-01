<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
        <div class="flex items-center justify-end">
            <a href="{{ route('admin.courses.create') }}" wire:navigate
                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                {{ __('Новый курс') }}
            </a>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
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
                                        <span class="text-green-600">{{ __('Опубликован') }}</span>
                                        @break
                                    @case(\App\Enums\CourseStatus::Archived)
                                        <span class="text-gray-500">{{ __('В архиве') }}</span>
                                        @break
                                    @default
                                        <span class="text-amber-600">{{ __('Черновик') }}</span>
                                @endswitch
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right space-x-3">
                                <a href="{{ route('admin.courses.builder', $course) }}" wire:navigate class="text-indigo-600 hover:text-indigo-900">{{ __('Редактировать содержимое') }}</a>
                                <a href="{{ route('admin.courses.edit', $course) }}" wire:navigate class="text-gray-600 hover:text-gray-900">{{ __('Параметры') }}</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">{{ __('Курсов пока нет.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $courses->links() }}
    </div>
</div>
