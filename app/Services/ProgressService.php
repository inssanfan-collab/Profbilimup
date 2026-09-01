<?php

namespace App\Services;

use App\Enums\AssignmentStatus;
use App\Enums\FinalOutcome;
use App\Enums\LessonProgressStatus;
use App\Models\Course;
use App\Models\CourseAssignment;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ProgressService
{
    public function assignCourse(Course $course, User $listener, User $assignedBy, ?\DateTimeInterface $deadline): CourseAssignment
    {
        return DB::transaction(function () use ($course, $listener, $assignedBy, $deadline) {
            $assignment = CourseAssignment::create([
                'course_id' => $course->id,
                'listener_id' => $listener->id,
                'assigned_by' => $assignedBy->id,
                'deadline' => $deadline,
                'status' => AssignmentStatus::Assigned,
                'assigned_at' => now(),
            ]);

            foreach ($course->orderedLessons() as $lesson) {
                $assignment->lessonProgress()->create([
                    'lesson_id' => $lesson->id,
                    'status' => LessonProgressStatus::Locked,
                ]);
            }

            return $assignment;
        });
    }

    /**
     * Слушатель принимает «Соглашение на обучение» — это открывает первый урок курса.
     */
    public function acceptAgreement(CourseAssignment $assignment): void
    {
        if ($assignment->agreement_accepted_at) {
            return;
        }

        DB::transaction(function () use ($assignment) {
            $assignment->update([
                'agreement_accepted_at' => now(),
                'status' => $assignment->status === AssignmentStatus::Assigned
                    ? AssignmentStatus::InProgress
                    : $assignment->status,
            ]);

            $firstLessonId = $assignment->course->orderedLessons()->pluck('id')->first();

            $this->unlockLesson($assignment, $firstLessonId);
        });
    }

    /**
     * Отмечает урок завершённым и открывает следующий по порядку урок курса,
     * либо завершает назначение целиком, если это был последний урок.
     */
    public function completeLesson(CourseAssignment $assignment, Lesson $lesson): void
    {
        DB::transaction(function () use ($assignment, $lesson) {
            $progress = $assignment->lessonProgress()->where('lesson_id', $lesson->id)->first();

            if (! $progress || $progress->status !== LessonProgressStatus::Available) {
                return;
            }

            $progress->update([
                'status' => LessonProgressStatus::Completed,
                'completed_at' => now(),
            ]);

            $orderedIds = $assignment->course->orderedLessons()->pluck('id')->values();
            $nextId = $orderedIds->get($orderedIds->search($lesson->id) + 1);

            if ($nextId) {
                $this->unlockLesson($assignment, $nextId);
            } else {
                $this->completeCourse($assignment);
            }
        });
    }

    private function unlockLesson(CourseAssignment $assignment, ?int $lessonId): void
    {
        if (! $lessonId) {
            return;
        }

        $assignment->lessonProgress()
            ->where('lesson_id', $lessonId)
            ->where('status', LessonProgressStatus::Locked)
            ->update(['status' => LessonProgressStatus::Available]);
    }

    private function completeCourse(CourseAssignment $assignment): void
    {
        // Каждый урок с тестом уже требовал прохождения теста, чтобы засчитаться
        // (см. TestGradingService), поэтому дойти до последнего урока означает
        // успешно пройти итоговое оценивание курса.
        $assignment->update([
            'status' => AssignmentStatus::Completed,
            'completed_at' => now(),
            'final_outcome' => FinalOutcome::Passed,
        ]);

        app(CertificateService::class)->generate($assignment->fresh());
    }

    /**
     * Админ закрывает назначение как «прослушал» — для слушателя, который не смог
     * пройти итоговое оценивание (например, исчерпал попытки теста). Выдаётся
     * справка о прослушивании вместо сертификата.
     */
    public function closeAsAttendanceOnly(CourseAssignment $assignment): void
    {
        if ($assignment->status === AssignmentStatus::Completed) {
            return;
        }

        $assignment->update([
            'status' => AssignmentStatus::Completed,
            'completed_at' => now(),
            'final_outcome' => FinalOutcome::AttendanceOnly,
            'retake_available_at' => now()->addYear(),
        ]);

        app(CertificateService::class)->generate($assignment->fresh());
    }
}
