<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Слушатель') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Курс / урок') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Сдан') }}</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($attempts as $attempt)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $attempt->listener->listenerProfile?->full_name ?? $attempt->listener->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $attempt->test->lesson->courseModule->course->title }} — {{ $attempt->test->lesson->title }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $attempt->submitted_at?->format('d.m.Y H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <a href="{{ route('admin.test-review.show', $attempt) }}" wire:navigate class="text-indigo-600 hover:text-indigo-900">{{ __('Проверить') }}</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">{{ __('Нет ответов, ожидающих проверки.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
