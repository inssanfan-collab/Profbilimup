<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <a href="{{ route('admin.post-course-support.index') }}" wire:navigate class="text-sm text-indigo-600 hover:text-indigo-900">
            &larr; {{ __('Назад к списку') }}
        </a>

        <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
            <h3 class="text-lg font-semibold text-gray-800">{{ __('Индивидуальный план') }}</h3>

            @if (! $plan)
                <p class="text-sm text-gray-400">{{ __('Слушатель ещё не подал план.') }}</p>
            @else
                <p class="whitespace-pre-line text-gray-700">{{ $plan->content }}</p>

                @if ($plan->status === \App\Enums\PostCourseSupportStatus::Submitted)
                    <div class="flex items-center gap-3 border-t border-gray-100 pt-4">
                        <button wire:click="approvePlan" type="button"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            {{ __('Одобрить') }}
                        </button>
                    </div>
                    <form wire:submit="rejectPlan" class="flex items-center gap-3">
                        <x-text-input wire:model="reviewNote" placeholder="{{ __('Комментарий для доработки') }}" class="block w-full" />
                        <button type="submit" class="text-red-600 hover:text-red-900 text-sm whitespace-nowrap">{{ __('На доработку') }}</button>
                    </form>
                    <x-input-error :messages="$errors->get('reviewNote')" />
                @else
                    <p class="text-sm {{ $plan->status === \App\Enums\PostCourseSupportStatus::Approved ? 'text-green-600' : 'text-red-600' }}">
                        {{ $plan->status === \App\Enums\PostCourseSupportStatus::Approved ? __('Одобрен') : __('Отправлен на доработку') }}
                    </p>
                @endif
            @endif
        </div>

        @if ($plan)
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
                            @else
                                <div class="mt-2 flex items-center gap-2">
                                    <x-text-input wire:model="feedbackText.{{ $lessonPlan->id }}" placeholder="{{ __('Обратная связь') }}" class="block w-full text-sm" />
                                    <button wire:click="giveFeedback({{ $lessonPlan->id }})" type="button" class="text-sm text-indigo-600 hover:text-indigo-900 whitespace-nowrap">{{ __('Отправить') }}</button>
                                </div>
                            @endif
                        </li>
                    @empty
                        <li class="text-sm text-gray-400">{{ __('Планов уроков пока нет.') }}</li>
                    @endforelse
                </ul>
            </div>
        @endif

        <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-3">
            <h3 class="text-lg font-semibold text-gray-800">{{ __('Аналитические отчёты') }}</h3>
            <ul class="space-y-2">
                @forelse ($reports as $report)
                    <li class="text-sm border border-gray-100 rounded-md p-3">
                        <p class="text-gray-700 whitespace-pre-line">{{ $report->content }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $report->submitted_at->format('d.m.Y') }}</p>
                    </li>
                @empty
                    <li class="text-sm text-gray-400">{{ __('Отчётов пока нет.') }}</li>
                @endforelse
            </ul>
        </div>

        <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-3">
            <h3 class="text-lg font-semibold text-gray-800">{{ __('Участие в мероприятиях') }}</h3>
            <ul class="space-y-2">
                @forelse ($events as $event)
                    <li class="text-sm border border-gray-100 rounded-md p-3">
                        <p class="font-medium text-gray-800">{{ $event->title }} <span class="text-gray-400">— {{ $event->type->label() }}</span></p>
                        <p class="text-gray-500">{{ $event->event_date->format('d.m.Y') }}</p>
                    </li>
                @empty
                    <li class="text-sm text-gray-400">{{ __('Мероприятий пока нет.') }}</li>
                @endforelse
            </ul>
        </div>

        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            @if ($reference)
                <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($reference->pdf_path) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">
                    {{ __('Справка выдана — скачать PDF') }}
                </a>
            @else
                <button wire:click="issueReference" type="button" wire:confirm="{{ __('Выдать справку о прохождении посткурсового сопровождения?') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    {{ __('Выдать справку') }}
                </button>
            @endif
        </div>
    </div>
</div>
