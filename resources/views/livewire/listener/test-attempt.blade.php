<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <a href="{{ route('listener.lessons.show', [$assignment, $lesson]) }}" wire:navigate class="text-sm text-indigo-600 hover:text-indigo-900">
            &larr; {{ __('Назад к уроку') }}
        </a>

        @if (! $attempt || in_array($attempt->status->value, ['graded'], true))
            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
                @if ($attempt && $attempt->status->value === 'graded')
                    <p class="font-semibold {{ $attempt->passed ? 'text-green-600' : 'text-red-600' }}">
                        {{ __('Результат последней попытки:') }} {{ $attempt->score_percent }}% —
                        {{ $attempt->passed ? __('тест пройден, урок засчитан.') : __('проходной балл не набран.') }}
                    </p>
                @endif

                @if (! $attempt || ! $attempt->passed)
                    @if ($canStart)
                        <button wire:click="start" type="button"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            {{ $attempt ? __('Попробовать снова') : __('Начать тест') }}
                        </button>
                        @if ($lesson->test->max_attempts)
                            <p class="text-xs text-gray-500">{{ __('Использовано попыток: :used из :max', ['used' => $attemptsUsed, 'max' => $lesson->test->max_attempts]) }}</p>
                        @endif
                    @else
                        <p class="text-red-600 text-sm">{{ __('Попытки исчерпаны. Обратитесь к администратору.') }}</p>
                    @endif
                @endif
            </div>
        @elseif ($attempt->status->value === 'awaiting_review')
            <div class="bg-amber-50 border border-amber-200 text-amber-800 rounded-md p-6">
                {{ __('Ваши текстовые ответы отправлены на проверку администратору. Урок откроется после проверки.') }}
            </div>
        @else
            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-6" x-data="{
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
                    <p class="text-sm text-gray-500">{{ __('Осталось времени:') }} <span x-text="remaining"></span></p>
                @endif

                <form wire:submit="submit" class="space-y-6">
                    @foreach ($lesson->test->questions as $question)
                        <div class="border-t border-gray-100 pt-4 first:border-t-0 first:pt-0">
                            <p class="font-medium text-gray-800">{{ $loop->iteration }}. {{ $question->question_text }}</p>

                            @if ($question->type === $textQuestionType)
                                <textarea wire:model="responses.{{ $question->id }}.free_text_answer" rows="3"
                                    class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                            @elseif ($question->type === $multipleQuestionType)
                                <div class="mt-2 space-y-1">
                                    @foreach ($question->choices as $choice)
                                        <label class="flex items-center gap-2 text-sm text-gray-700">
                                            <input type="checkbox" value="{{ $choice->id }}" wire:model="responses.{{ $question->id }}.selected_choice_ids">
                                            {{ $choice->choice_text }}
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <div class="mt-2 space-y-1">
                                    @foreach ($question->choices as $choice)
                                        <label class="flex items-center gap-2 text-sm text-gray-700">
                                            <input type="radio" value="{{ $choice->id }}" wire:model="responses.{{ $question->id }}.selected_choice_ids.0" name="q{{ $question->id }}">
                                            {{ $choice->choice_text }}
                                        </label>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach

                    <x-primary-button>{{ __('Завершить тест') }}</x-primary-button>
                </form>
            </div>
        @endif
    </div>
</div>
