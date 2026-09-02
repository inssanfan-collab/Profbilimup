<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <a href="{{ route('admin.lessons.edit', $lesson) }}" wire:navigate class="text-sm text-blue-700 hover:text-blue-900">
            &larr; {{ __('Назад к уроку') }}
        </a>

        @if (! $lesson->test)
            <x-card>
                <p class="text-gray-600 mb-4">{{ __('У этого урока пока нет теста.') }}</p>
                <x-primary-button wire:click="createTest" type="button">
                    {{ __('Создать тест') }}
                </x-primary-button>
            </x-card>
        @else
            <x-card>
                <form wire:submit="saveSettings" class="flex flex-wrap items-end gap-4">
                    <div>
                        <x-input-label for="time_limit_minutes" :value="__('Лимит времени (мин.)')" />
                        <x-text-input wire:model="time_limit_minutes" id="time_limit_minutes" type="number" min="1" class="block mt-1 w-32" placeholder="{{ __('без лимита') }}" />
                    </div>
                    <div>
                        <x-input-label for="max_attempts" :value="__('Макс. попыток')" />
                        <x-text-input wire:model="max_attempts" id="max_attempts" type="number" min="1" class="block mt-1 w-32" placeholder="{{ __('без лимита') }}" />
                    </div>
                    <div>
                        <x-input-label for="passing_score_percent" :value="__('Проходной балл, %')" />
                        <x-text-input wire:model="passing_score_percent" id="passing_score_percent" type="number" min="1" max="100" class="block mt-1 w-32" />
                    </div>
                    <x-primary-button>{{ __('Сохранить настройки') }}</x-primary-button>
                </form>
            </x-card>

            @foreach ($questions as $question)
                <x-card wire:key="question-{{ $question->id }}">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="font-medium text-gray-800">{{ $question->question_text }}</p>
                            <p class="text-xs text-gray-500 mt-1">
                                @switch($question->type)
                                    @case(\App\Enums\QuestionType::Single) {{ __('Один правильный ответ') }} @break
                                    @case(\App\Enums\QuestionType::Multiple) {{ __('Несколько правильных ответов') }} @break
                                    @case(\App\Enums\QuestionType::Text) {{ __('Текстовый ответ (ручная проверка)') }} @break
                                @endswitch
                                · {{ __(':points балл(ов)', ['points' => $question->points]) }}
                            </p>
                        </div>
                        <button wire:click="deleteQuestion({{ $question->id }})" wire:confirm="{{ __('Удалить вопрос?') }}" type="button" class="text-red-600 hover:text-red-900 text-sm">{{ __('Удалить') }}</button>
                    </div>

                    @if ($question->type !== \App\Enums\QuestionType::Text)
                        <ul class="mt-3 space-y-1">
                            @foreach ($question->choices as $choice)
                                <li class="flex items-center justify-between text-sm">
                                    <span class="{{ $choice->is_correct ? 'text-green-700 font-medium' : 'text-gray-700' }}">
                                        {{ $choice->is_correct ? '✓' : '—' }} {{ $choice->choice_text }}
                                    </span>
                                    <button wire:click="deleteChoice({{ $choice->id }})" type="button" class="text-red-500 hover:text-red-700 text-xs">{{ __('Удалить') }}</button>
                                </li>
                            @endforeach
                        </ul>

                        <div class="mt-3 flex items-center gap-2">
                            <x-text-input wire:model="newChoiceText.{{ $question->id }}" placeholder="{{ __('Новый вариант ответа') }}" class="block w-full" />
                            <label class="flex items-center gap-1 text-sm text-gray-600 whitespace-nowrap">
                                <input type="checkbox" wire:model="newChoiceIsCorrect.{{ $question->id }}" class="rounded border-gray-300 text-blue-700 focus:ring-blue-600">
                                {{ __('верный') }}
                            </label>
                            <button wire:click="addChoice({{ $question->id }})" type="button" class="text-sm text-blue-700 hover:text-blue-900 whitespace-nowrap">
                                + {{ __('Добавить') }}
                            </button>
                        </div>
                    @endif
                </x-card>
            @endforeach

            <x-card>
                <h3 class="text-sm font-semibold text-gray-700 mb-3">{{ __('Новый вопрос') }}</h3>
                <form wire:submit="addQuestion" class="space-y-3">
                    <div>
                        <textarea wire:model="newQuestionText" rows="2" placeholder="{{ __('Текст вопроса') }}"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-600 focus:ring-blue-600"></textarea>
                        <x-input-error :messages="$errors->get('newQuestionText')" class="mt-2" />
                    </div>
                    <div class="flex items-center gap-4">
                        <select wire:model="newQuestionType" class="rounded-lg border-gray-300 shadow-sm focus:border-blue-600 focus:ring-blue-600">
                            <option value="single">{{ __('Один правильный ответ') }}</option>
                            <option value="multiple">{{ __('Несколько правильных ответов') }}</option>
                            <option value="text">{{ __('Текстовый ответ') }}</option>
                        </select>
                        <x-text-input wire:model="newQuestionPoints" type="number" min="1" class="w-24" />
                        <x-primary-button>{{ __('Добавить вопрос') }}</x-primary-button>
                    </div>
                </form>
            </x-card>
        @endif
    </div>
</div>
