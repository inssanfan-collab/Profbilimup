<?php

namespace App\Enums;

enum CuratorPermission: string
{
    case VideoMeetings = 'video_meetings';
    case TestReview = 'test_review';
    case Courses = 'courses';
    case Listeners = 'listeners';
    case Analytics = 'analytics';
    case PostCourseSupport = 'post_course_support';

    public function label(): string
    {
        return match ($this) {
            self::VideoMeetings => __('Видеоуроки'),
            self::TestReview => __('Проверка тестов'),
            self::Courses => __('Управление курсами'),
            self::Listeners => __('Слушатели'),
            self::Analytics => __('Аналитика'),
            self::PostCourseSupport => __('Посткурсовое сопровождение'),
        };
    }
}
