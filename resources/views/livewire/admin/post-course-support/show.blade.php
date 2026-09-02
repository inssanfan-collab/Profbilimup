<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <a href="{{ route('admin.post-course-support.index') }}" wire:navigate class="text-sm text-blue-700 hover:text-blue-900">
            &larr; {{ __('Назад к списку') }}
        </a>

        <x-card :title="__('Индивидуальный план')">
            @if (! $plan)
                <p class="text-sm text-gray-400">{{ __('Слушатель ещё не подал план.') }}</p>
            @else
                <p class="whitespace-pre-line text-gray-700">{{ $plan->content }}</p>

                @if ($plan->status === \App\Enums\PostCourseSupportStatus::Submitted)
                    <div class="flex items-center gap-3 border-t border-gray-100 pt-4 mt-4">
                        <x-primary-button wire:click="approvePlan" type="button">
                            {{ __('Одобрить') }}
                        </x-primary-button>
                    </div>
                    <form wire:submit="rejectPlan" class="flex items-center gap-3 mt-3">
                        <x-text-input wire:model="reviewNote" placeholder="{{ __('Комментарий для доработки') }}" class="block w-full" />
                        <button type="submit" class="text-red-600 hover:text-red-900 text-sm whitespace-nowrap">{{ __('На доработку') }}</button>
                    </form>
                    <x-input-error :messages="$errors->get('reviewNote')" />
                @else
                    <div class="mt-3">
                        @if ($plan->status === \App\Enums\PostCourseSupportStatus::Approved)
                            <x-badge color="green">{{ __('Одобрен') }}</x-badge>
                        @else
                            <x-badge color="red">{{ __('Отправлен на доработку') }}</x-badge>
                        @endif
                    </div>
                @endif
            @endif
        </x-card>

        @if ($plan)
            <x-card :title="__('Планы уроков')">
                <ul class="space-y-3">
                    @forelse ($plan->lessonPlans as $lessonPlan)
                        <li class="border border-gray-100 rounded-lg p-3">
                            <p class="font-medium text-gray-800">{{ $lessonPlan->title }}</p>
                            <p class="text-sm text-gray-600 whitespace-pre-line mt-1">{{ $lessonPlan->content }}</p>

                            @if ($lessonPlan->feedback_text)
                                <div class="mt-2 bg-blue-50 rounded-lg p-2 text-sm text-blue-800">
                                    <strong>{{ __('Обратная связь:') }}</strong> {{ $lessonPlan->feedback_text }}
                                </div>
                            @else
                                <div class="mt-2 flex items-center gap-2">
                                    <x-text-input wire:model="feedbackText.{{ $lessonPlan->id }}" placeholder="{{ __('Обратная связь') }}" class="block w-full text-sm" />
                                    <button wire:click="giveFeedback({{ $lessonPlan->id }})" type="button" class="text-sm text-blue-700 hover:text-blue-900 whitespace-nowrap">{{ __('Отправить') }}</button>
                                </div>
                            @endif
                        </li>
                    @empty
                        <li class="text-sm text-gray-400">{{ __('Планов уроков пока нет.') }}</li>
                    @endforelse
                </ul>
            </x-card>
        @endif

        <x-card :title="__('Аналитические отчёты')">
            <ul class="space-y-2">
                @forelse ($reports as $report)
                    <li class="text-sm border border-gray-100 rounded-lg p-3">
                        <p class="text-gray-700 whitespace-pre-line">{{ $report->content }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $report->submitted_at->format('d.m.Y') }}</p>
                    </li>
                @empty
                    <li class="text-sm text-gray-400">{{ __('Отчётов пока нет.') }}</li>
                @endforelse
            </ul>
        </x-card>

        <x-card :title="__('Участие в мероприятиях')">
            <ul class="space-y-2">
                @forelse ($events as $event)
                    <li class="text-sm border border-gray-100 rounded-lg p-3">
                        <p class="font-medium text-gray-800">{{ $event->title }} <span class="text-gray-400">— {{ $event->type->label() }}</span></p>
                        <p class="text-gray-500">{{ $event->event_date->format('d.m.Y') }}</p>
                    </li>
                @empty
                    <li class="text-sm text-gray-400">{{ __('Мероприятий пока нет.') }}</li>
                @endforelse
            </ul>
        </x-card>

        <x-card>
            @if ($reference)
                <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($reference->pdf_path) }}" target="_blank" class="text-blue-700 hover:text-blue-900">
                    {{ __('Справка выдана — скачать PDF') }}
                </a>
            @else
                <x-primary-button wire:click="issueReference" type="button" wire:confirm="{{ __('Выдать справку о прохождении посткурсового сопровождения?') }}">
                    {{ __('Выдать справку') }}
                </x-primary-button>
            @endif
        </x-card>
    </div>
</div>
