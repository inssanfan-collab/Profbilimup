<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <a href="{{ route('listener.dashboard') }}" wire:navigate class="text-sm text-indigo-600 hover:text-indigo-900">
            &larr; {{ __('Главная') }}
        </a>

        <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
            <h3 class="text-lg font-semibold text-gray-800">{{ __('Индивидуальный план посткурсового сопровождения') }}</h3>

            @if ($plan && $plan->status !== \App\Enums\PostCourseSupportStatus::Rejected)
                <p class="text-sm text-gray-500">
                    {{ __('Статус:') }}
                    @switch($plan->status)
                        @case(\App\Enums\PostCourseSupportStatus::Approved) <span class="text-green-600">{{ __('Одобрен') }}</span> @break
                        @default <span class="text-amber-600">{{ __('На проверке') }}</span>
                    @endswitch
                </p>
                <p class="whitespace-pre-line text-gray-700">{{ $plan->content }}</p>
            @else
                @if ($plan && $plan->status === \App\Enums\PostCourseSupportStatus::Rejected)
                    <div class="bg-red-50 border border-red-200 text-red-800 rounded-md p-3 text-sm">
                        {{ __('План отправлен на доработку.') }}
                        @if ($plan->review_note)
                            <p class="mt-1">{{ $plan->review_note }}</p>
                        @endif
                    </div>
                @endif

                <form wire:submit="submitPlan" class="space-y-3">
                    <textarea wire:model="planContent" rows="5" placeholder="{{ __('Опишите индивидуальный план посткурсового сопровождения') }}"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    <x-input-error :messages="$errors->get('planContent')" />
                    <x-primary-button>{{ __('Отправить план') }}</x-primary-button>
                </form>
            @endif
        </div>

        @if ($plan && $plan->status !== \App\Enums\PostCourseSupportStatus::Rejected)
            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
                <h3 class="text-lg font-semibold text-gray-800">{{ __('Планы уроков') }}</h3>

                <ul class="space-y-3">
                    @forelse ($plan->lessonPlans as $lessonPlan)
                        <li class="border border-gray-100 rounded-md p-3">
                            <p class="font-medium text-gray-800">{{ $lessonPlan->title }}</p>
                            <p class="text-sm text-gray-600 whitespace-pre-line mt-1">{{ $lessonPlan->content }}</p>
                            @if ($lessonPlan->feedback_text)
                                <div class="mt-2 bg-indigo-50 rounded-md p-2 text-sm text-indigo-800">
                                    <strong>{{ __('Обратная связь:') }}</strong> {{ $lessonPlan->feedback_text }}
                                </div>
                            @endif
                        </li>
                    @empty
                        <li class="text-sm text-gray-400">{{ __('Планов уроков пока нет.') }}</li>
                    @endforelse
                </ul>

                <form wire:submit="addLessonPlan" class="space-y-3 border-t border-gray-100 pt-4">
                    <x-text-input wire:model="newLessonPlanTitle" placeholder="{{ __('Название урока') }}" class="block w-full" />
                    <x-input-error :messages="$errors->get('newLessonPlanTitle')" />
                    <textarea wire:model="newLessonPlanContent" rows="3" placeholder="{{ __('Содержание плана урока') }}"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    <x-input-error :messages="$errors->get('newLessonPlanContent')" />
                    <x-primary-button>{{ __('Добавить план урока') }}</x-primary-button>
                </form>
            </div>
        @endif

        <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
            <h3 class="text-lg font-semibold text-gray-800">{{ __('Аналитические отчёты') }}</h3>

            <ul class="space-y-3">
                @forelse ($reports as $report)
                    <li class="border border-gray-100 rounded-md p-3 text-sm">
                        <p class="text-gray-700 whitespace-pre-line">{{ $report->content }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $report->submitted_at->format('d.m.Y') }}</p>
                    </li>
                @empty
                    <li class="text-sm text-gray-400">{{ __('Отчётов пока нет.') }}</li>
                @endforelse
            </ul>

            <form wire:submit="addReport" class="space-y-3 border-t border-gray-100 pt-4">
                <textarea wire:model="reportContent" rows="3" placeholder="{{ __('Текст аналитического отчёта') }}"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                <x-input-error :messages="$errors->get('reportContent')" />
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <textarea wire:model="diagnosticBefore" rows="2" placeholder="{{ __('Диагностика до внедрения') }}"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    <textarea wire:model="diagnosticAfter" rows="2" placeholder="{{ __('Диагностика после внедрения') }}"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                </div>
                <x-primary-button>{{ __('Добавить отчёт') }}</x-primary-button>
            </form>
        </div>

        <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
            <h3 class="text-lg font-semibold text-gray-800">{{ __('Участие в мероприятиях') }}</h3>

            <ul class="space-y-2">
                @forelse ($events as $event)
                    <li class="text-sm border border-gray-100 rounded-md p-3">
                        <p class="font-medium text-gray-800">{{ $event->title }}</p>
                        <p class="text-gray-500">{{ $event->event_date->format('d.m.Y') }}</p>
                    </li>
                @empty
                    <li class="text-sm text-gray-400">{{ __('Мероприятий пока нет.') }}</li>
                @endforelse
            </ul>

            <form wire:submit="addEvent" class="space-y-3 border-t border-gray-100 pt-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <select wire:model="eventType" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @foreach ($eventTypes as $type)
                            <option value="{{ $type->value }}">{{ $type->label() }}</option>
                        @endforeach
                    </select>
                    <x-text-input wire:model="eventDate" type="date" class="block w-full" />
                </div>
                <x-text-input wire:model="eventTitle" placeholder="{{ __('Название мероприятия') }}" class="block w-full" />
                <x-input-error :messages="$errors->get('eventTitle')" />
                <textarea wire:model="eventDescription" rows="2" placeholder="{{ __('Описание') }}"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                <x-primary-button>{{ __('Добавить участие') }}</x-primary-button>
            </form>
        </div>
    </div>
</div>
