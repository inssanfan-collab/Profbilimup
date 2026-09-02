<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <a href="{{ route('listener.lessons.show', [$assignment, $lesson]) }}" wire:navigate class="text-sm text-blue-700 hover:text-blue-900">
            &larr; {{ __('Назад к уроку') }}
        </a>

        @if (! $attempt || in_array($attempt->status->value, ['graded'], true))
            <x-card class="space-y-4">
                @if ($attempt && $attempt->status->value === 'graded')
                    <p class="font-semibold {{ $attempt->passed ? 'text-green-600' : 'text-red-600' }}">
                        {{ __('Результат последней попытки:') }} {{ $attempt->score_percent }}% —
                        {{ $attempt->passed ? __('тест пройден, урок засчитан.') : __('проходной балл не набран.') }}
                    </p>
                @endif

                @if (! $attempt || ! $attempt->passed)
                    @if ($canStart)
                        <x-primary-button wire:click="start" type="button">
                            {{ $attempt ? __('Попробовать снова') : __('Начать тест') }}
                        </x-primary-button>
                        @if ($lesson->test->max_attempts)
                            <p class="text-xs text-gray-500">{{ __('Использовано попыток: :used из :max', ['used' => $attemptsUsed, 'max' => $lesson->test->max_attempts]) }}</p>
                        @endif
                    @else
                        <p class="text-red-600 text-sm">{{ __('Попытки исчерпаны. Обратитесь к администратору.') }}</p>
                    @endif
                @endif
            </x-card>
        @elseif ($attempt->status->value === 'awaiting_review')
            <div class="bg-amber-50 border border-amber-200 text-amber-800 rounded-xl p-6">
                {{ __('Ваши текстовые ответы отправлены на проверку администратору. Урок откроется после проверки.') }}
            </div>
        @else
            <x-card class="space-y-6" x-data="{
                deadline: {{ $lesson->test->time_limit_minutes ? $attempt->started_at->addMinutes($lesson->test->time_limit_minutes)->timestamp * 1000 : 'null' }},
                remaining: '',
                tick() {
                    if (!this.deadline) return;
                    const diff = this.deadline - Date.now();
                    if (diff <= 0) { this.remaining = '{{ __('время истекло') }}'; $wire.submit(); return; }
                    const m = Math.floor(diff / 60000);
                    const s = Math.floor((diff % 60000) / 1000);
                    this.remaining = m + ':' + String(s).padStart(2, '0');
                }
            }" x-init="tick(); setInterval(() => tick(), 1000)">
                @if ($lesson->test->time_limit_minutes)
                    <p class="text-sm text-gray-500">{{ __('Осталось времени:') }} <span x-text="remaining" class="font-semibold text-gray-800"></span></p>
                @endif

                <form wire:submit="submit" class="space-y-6">
                    @foreach ($lesson->test->questions as $question)
                        <div class="border-t border-gray-100 pt-4 first:border-t-0 first:pt-0">
                            <p class="font-medium text-gray-800">{{ $loop->iteration }}. {{ $question->question_text }}</p>

                            @if ($question->type === $textQuestionType)
                                <textarea wire:model="responses.{{ $question->id }}.free_text_answer" rows="3"
                                    class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-600 focus:ring-blue-600"></textarea>
                            @elseif ($question->type === $multipleQuestionType)
                                <div class="mt-2 space-y-1">
                                    @foreach ($question->choices as $choice)
                                        <label class="flex items-center gap-2 text-sm text-gray-700">
                                            <input type="checkbox" value="{{ $choice->id }}" wire:model="responses.{{ $question->id }}.selected_choice_ids" class="rounded border-gray-300 text-blue-700 focus:ring-blue-600">
                                            {{ $choice->choice_text }}
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <div class="mt-2 space-y-1">
                                    @foreach ($question->choices as $choice)
                                        <label class="flex items-center gap-2 text-sm text-gray-700">
                                            <input type="radio" value="{{ $choice->id }}" wire:model="responses.{{ $question->id }}.selected_choice_ids.0" name="q{{ $question->id }}" class="border-gray-300 text-blue-700 focus:ring-blue-600">
                                            {{ $choice->choice_text }}
                                        </label>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach

                    <x-primary-button>{{ __('Завершить тест') }}</x-primary-button>
                </form>
            </x-card>
        @endif
    </div>
</div>
