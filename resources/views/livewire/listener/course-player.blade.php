<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
        @if ($assignment->isOverdue())
            <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl p-6">
                {{ __('Срок прохождения курса истёк. Доступ к урокам закрыт. Обратитесь к администратору для продления срока.') }}
            </div>
        @elseif (! $assignment->agreement_accepted_at)
            <x-card :title="__('Соглашение на обучение')">
                <div class="text-sm text-gray-600 space-y-2">
                    <p>{{ __('Настоящим подтверждаю, что являюсь слушателем курса повышения квалификации ":title" и принимаю следующие обязательства:', ['title' => $assignment->course->title]) }}</p>
                    <ul class="list-disc list-inside space-y-1">
                        <li>{{ __('обучаться в соответствии с содержанием и структурой курса;') }}</li>
                        <li>{{ __('соблюдать учебную дисциплину и этические нормы;') }}</li>
                        <li>{{ __('пройти итоговое оценивание в установленной форме.') }}</li>
                    </ul>
                </div>
                <x-primary-button wire:click="acceptAgreement" type="button" class="mt-4">
                    {{ __('Согласен, начать курс') }}
                </x-primary-button>
            </x-card>
        @else
            @if ($videoMeetings->isNotEmpty())
                <x-card :title="__('Видеоуроки')">
                    <ul class="divide-y divide-gray-100 -mx-6">
                        @foreach ($videoMeetings as $meeting)
                            <li class="px-6 py-3 flex items-center justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-blue-50 text-blue-700">
                                        <x-app-icon name="video" class="h-4 w-4" />
                                    </span>
                                    <div>
                                        <p class="text-gray-800">{{ $meeting->name }}</p>
                                        @if ($meeting->starts_at)
                                            <p class="text-xs text-gray-500">{{ $meeting->starts_at->format('d.m.Y H:i') }}</p>
                                        @endif
                                    </div>
                                </div>
                                <x-secondary-button wire:click="joinVideoMeeting({{ $meeting->id }})" type="button">
                                    {{ __('Присоединиться') }}
                                </x-secondary-button>
                            </li>
                        @endforeach
                    </ul>
                </x-card>
            @endif

            @foreach ($modules as $module)
                <x-card :title="$module->title">
                    <ul class="divide-y divide-gray-100 -mx-6">
                        @foreach ($module->lessons as $lesson)
                            @php $progress = $progressByLessonId->get($lesson->id) @endphp
                            <li class="px-6 py-3 flex items-center justify-between gap-4">
                                @if ($progress?->status === \App\Enums\LessonProgressStatus::Completed)
                                    <a href="{{ route('listener.lessons.show', [$assignment, $lesson]) }}" wire:navigate class="flex items-center gap-3 min-w-0 text-gray-700 hover:text-blue-700">
                                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-green-50 text-green-600">
                                            <x-app-icon name="check" class="h-4 w-4" />
                                        </span>
                                        <span class="truncate">{{ $lesson->title }}</span>
                                    </a>
                                    <x-badge color="green" class="shrink-0">{{ __('Завершён') }}</x-badge>
                                @elseif ($progress?->status === \App\Enums\LessonProgressStatus::Available)
                                    <a href="{{ route('listener.lessons.show', [$assignment, $lesson]) }}" wire:navigate class="flex items-center gap-3 min-w-0 text-blue-700 hover:text-blue-900 font-medium">
                                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-blue-700 text-white text-sm font-semibold">
                                            {{ $loop->iteration }}
                                        </span>
                                        <span class="truncate">{{ $lesson->title }}</span>
                                    </a>
                                @else
                                    <span class="flex items-center gap-3 min-w-0 text-gray-400">
                                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                                            <x-app-icon name="lock" class="h-4 w-4" />
                                        </span>
                                        <span class="truncate">{{ $lesson->title }}</span>
                                    </span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </x-card>
            @endforeach
        @endif
    </div>
</div>
