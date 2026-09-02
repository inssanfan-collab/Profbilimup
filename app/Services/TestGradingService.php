<?php

namespace App\Services;

use App\Enums\AssignmentStatus;
use App\Enums\QuestionType;
use App\Enums\TestAttemptStatus;
use App\Enums\UserRole;
use App\Models\CourseAssignment;
use App\Models\Test;
use App\Models\TestAnswer;
use App\Models\TestAttempt;
use App\Models\User;
use App\Notifications\TestAwaitingReviewNotification;
use App\Notifications\TestGradedNotification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use RuntimeException;

class TestGradingService
{
    public function attemptsUsed(Test $test, User $listener): int
    {
        return TestAttempt::where('test_id', $test->id)->where('listener_id', $listener->id)->count();
    }

    public function canStartAttempt(Test $test, User $listener): bool
    {
        return $test->max_attempts === null || $this->attemptsUsed($test, $listener) < $test->max_attempts;
    }

    public function startAttempt(Test $test, User $listener): TestAttempt
    {
        if (! $this->canStartAttempt($test, $listener)) {
            throw new RuntimeException('No attempts left for this test.');
        }

        return TestAttempt::create([
            'test_id' => $test->id,
            'listener_id' => $listener->id,
            'attempt_number' => $this->attemptsUsed($test, $listener) + 1,
            'status' => TestAttemptStatus::InProgress,
            'started_at' => now(),
        ]);
    }

    /**
     * @param  array<int, array{selected_choice_ids?: array<int>, free_text_answer?: string}>  $responses  keyed by question_id
     */
    public function submitAttempt(TestAttempt $attempt, array $responses): void
    {
        DB::transaction(function () use ($attempt, $responses) {
            foreach ($attempt->test->questions as $question) {
                $response = $responses[$question->id] ?? [];

                if ($question->type === QuestionType::Text) {
                    TestAnswer::updateOrCreate(
                        ['test_attempt_id' => $attempt->id, 'question_id' => $question->id],
                        [
                            'free_text_answer' => $response['free_text_answer'] ?? null,
                            'selected_choice_ids' => null,
                            'is_correct' => null,
                            'points_awarded' => null,
                        ]
                    );

                    continue;
                }

                $selected = collect($response['selected_choice_ids'] ?? [])
                    ->map(fn ($id) => (int) $id)->sort()->values();
                $correct = $question->choices()->where('is_correct', true)->pluck('id')->sort()->values();
                $isCorrect = $selected->all() === $correct->all();

                TestAnswer::updateOrCreate(
                    ['test_attempt_id' => $attempt->id, 'question_id' => $question->id],
                    [
                        'selected_choice_ids' => $selected->all(),
                        'free_text_answer' => null,
                        'is_correct' => $isCorrect,
                        'points_awarded' => $isCorrect ? $question->points : 0,
                    ]
                );
            }

            $attempt->update(['submitted_at' => now()]);

            $this->finalizeIfPossible($attempt->fresh());
        });
    }

    public function finalizeIfPossible(TestAttempt $attempt): void
    {
        $hasUngraded = $attempt->answers()->whereNull('points_awarded')->exists();

        if ($hasUngraded) {
            $wasAlreadyAwaitingReview = $attempt->status === TestAttemptStatus::AwaitingReview;

            $attempt->update(['status' => TestAttemptStatus::AwaitingReview]);

            if (! $wasAlreadyAwaitingReview) {
                Notification::send(User::where('role', UserRole::Admin)->get(), new TestAwaitingReviewNotification($attempt));
            }

            return;
        }

        $this->finalizeAttempt($attempt);
    }

    /**
     * Записывает баллы за текстовый ответ (ручная проверка админом) и, если это была
     * последняя неоценённая часть попытки, завершает её проверку.
     */
    public function gradeTextAnswer(TestAnswer $answer, int $pointsAwarded, User $reviewer): void
    {
        $pointsAwarded = max(0, min($pointsAwarded, $answer->question->points));

        $answer->update([
            'points_awarded' => $pointsAwarded,
            'is_correct' => $pointsAwarded >= $answer->question->points,
            'reviewed_by' => $reviewer->id,
            'reviewed_at' => now(),
        ]);

        $this->finalizeIfPossible($answer->testAttempt->fresh());
    }

    private function finalizeAttempt(TestAttempt $attempt): void
    {
        $totalPoints = $attempt->test->totalPoints();
        $earnedPoints = (int) $attempt->answers()->sum('points_awarded');
        $scorePercent = $totalPoints > 0 ? (int) round($earnedPoints / $totalPoints * 100) : 0;
        $passed = $scorePercent >= $attempt->test->passing_score_percent;

        $attempt->update([
            'status' => TestAttemptStatus::Graded,
            'score_percent' => $scorePercent,
            'passed' => $passed,
        ]);

        $attempt->listener->notify(new TestGradedNotification($attempt->fresh()));

        if ($passed) {
            $this->unlockLessonForPassedAttempt($attempt);
        }
    }

    private function unlockLessonForPassedAttempt(TestAttempt $attempt): void
    {
        $lesson = $attempt->test->lesson;
        $courseId = $lesson->courseModule->course_id;

        $assignment = CourseAssignment::where('listener_id', $attempt->listener_id)
            ->where('course_id', $courseId)
            ->whereNot('status', AssignmentStatus::Overdue)
            ->first();

        if ($assignment) {
            app(ProgressService::class)->completeLesson($assignment, $lesson);
        }
    }

    /**
     * @return Collection<int, TestAttempt>
     */
    public function pendingReview(): Collection
    {
        return TestAttempt::query()
            ->where('status', TestAttemptStatus::AwaitingReview)
            ->with(['listener.listenerProfile', 'test.lesson.courseModule.course'])
            ->oldest('submitted_at')
            ->get();
    }
}
