<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <a href="{{ route('admin.test-review.index') }}" wire:navigate class="text-sm text-blue-700 hover:text-blue-900">
            &larr; {{ __('Назад к очереди проверки') }}
        </a>

        <x-card>
            <p class="text-sm text-gray-600">
                {{ __('Слушатель:') }} <strong>{{ $attempt->listener->listenerProfile?->full_name ?? $attempt->listener->name }}</strong>
                · {{ __('Урок:') }} {{ $attempt->test->lesson->title }}
            </p>
            @if ($attempt->status === \App\Enums\TestAttemptStatus::Graded)
                <p class="mt-2 font-semibold {{ $attempt->passed ? 'text-green-600' : 'text-red-600' }}">
                    {{ __('Итог:') }} {{ $attempt->score_percent }}% — {{ $attempt->passed ? __('зачёт') : __('не зачёт') }}
                </p>
            @endif
        </x-card>

        @foreach ($answers as $answer)
            <x-card>
                <p class="font-medium text-gray-800">{{ $answer->question->question_text }}</p>

                @if ($answer->question->type === $textQuestionType)
                    <p class="mt-2 text-gray-700 whitespace-pre-line bg-gray-50 rounded-lg p-3">{{ $answer->free_text_answer ?: '—' }}</p>

                    @if ($answer->points_awarded === null)
                        <div class="mt-3 flex items-center gap-3">
                            <label class="text-sm text-gray-600">{{ __('Баллы (макс. :max):', ['max' => $answer->question->points]) }}</label>
                            <x-text-input wire:model="points.{{ $answer->id }}" type="number" min="0" max="{{ $answer->question->points }}" class="w-24" />
                            <x-primary-button wire:click="gradeAnswer({{ $answer->id }})" type="button">
                                {{ __('Оценить') }}
                            </x-primary-button>
                        </div>
                        <x-input-error :messages="$errors->get('points.'.$answer->id)" class="mt-2" />
                    @else
                        <p class="mt-2 text-sm text-gray-500">{{ __('Оценено:') }} {{ $answer->points_awarded }} / {{ $answer->question->points }}</p>
                    @endif
                @else
                    <p class="mt-2 text-sm {{ $answer->is_correct ? 'text-green-600' : 'text-red-600' }}">
                        {{ $answer->is_correct ? __('Верно') : __('Неверно') }} ({{ $answer->points_awarded }} / {{ $answer->question->points }})
                    </p>
                @endif
            </x-card>
        @endforeach
    </div>
</div>
