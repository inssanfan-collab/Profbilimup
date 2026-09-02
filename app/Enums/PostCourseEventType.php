<?php

namespace App\Enums;

enum PostCourseEventType: string
{
    case MethodologicalEvent = 'methodological_event';
    case Conference = 'conference';
    case Seminar = 'seminar';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::MethodologicalEvent => __('Методическое мероприятие'),
            self::Conference => __('Конференция'),
            self::Seminar => __('Семинар'),
            self::Other => __('Другое'),
        };
    }
}
