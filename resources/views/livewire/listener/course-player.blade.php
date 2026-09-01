<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
        @if ($assignment->isOverdue())
            <div class="bg-red-50 border border-red-200 text-red-800 rounded-md p-6">
                {{ __('Срок прохождения курса истёк. Доступ к урокам закрыт. Обратитесь к администратору для продления срока.') }}
            </div>
        @elseif (! $assignment->agreement_accepted_at)
            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
                <h3 class="text-lg font-semibold text-gray-800">{{ __('Соглашение на обучение') }}</h3>
                <div class="text-sm text-gray-600 space-y-2">
                    <p>{{ __('Настоящим подтверждаю, что являюсь слушателем курса повышения квалификации ":title" и принимаю следующие обязательства:', ['title' => $assignment->course->title]) }}</p>
                    <ul class="list-disc list-inside space-y-1">
                        <li>{{ __('обучаться в соответствии с содержанием и структурой курса;') }}</li>
                        <li>{{ __('соблюдать учебную дисциплину и этические нормы;') }}</li>
                        <li>{{ __('пройти итоговое оценивание в установленной форме.') }}</li>
                    </ul>
                </div>
                <button wire:click="acceptAgreement" type="button"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    {{ __('Согласен, начать курс') }}
                </button>
            </div>
        @else
            @foreach ($modules as $module)
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">{{ $module->title }}</h3>
                    <ul class="divide-y divide-gray-100">
                        @foreach ($module->lessons as $lesson)
                            @php $progress = $progressByLessonId->get($lesson->id) @endphp
                            <li class="py-3 flex items-center justify-between">
                                @if ($progress?->status === \App\Enums\LessonProgressStatus::Completed)
                                    <a href="{{ route('listener.lessons.show', [$assignment, $lesson]) }}" wire:navigate class="text-gray-700 hover:text-indigo-600">
                                        ✓ {{ $lesson->title }}
                                    </a>
                                    <span class="text-xs text-green-600">{{ __('Завершён') }}</span>
                                @elseif ($progress?->status === \App\Enums\LessonProgressStatus::Available)
                                    <a href="{{ route('listener.lessons.show', [$assignment, $lesson]) }}" wire:navigate class="text-indigo-600 hover:text-indigo-900 font-medium">
                                        {{ $lesson->title }}
                                    </a>
                                @else
                                    <span class="text-gray-400">🔒 {{ $lesson->title }}</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        @endif
    </div>
</div>
