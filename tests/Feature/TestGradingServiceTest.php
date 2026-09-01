<?php

namespace Tests\Feature;

use App\Enums\AssignmentStatus;
use App\Enums\LessonProgressStatus;
use App\Enums\TestAttemptStatus;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\Lesson;
use App\Models\User;
use App\Services\ProgressService;
use App\Services\TestGradingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestGradingServiceTest extends TestCase
{
    use RefreshDatabase;

    private function makeLessonWithTest(array $testAttributes = []): Lesson
    {
        $course = Course::factory()->create();
        $module = CourseModule::factory()->for($course)->create();
        $lesson = Lesson::factory()->for($module, 'courseModule')->create();

        $lesson->test()->create(array_merge([
            'title' => 'Итоговый тест',
            'passing_score_percent' => 70,
        ], $testAttributes));

        return $lesson->fresh();
    }

    public function test_single_choice_question_is_auto_graded(): void
    {
        $lesson = $this->makeLessonWithTest();
        $question = $lesson->test->questions()->create(['type' => 'single', 'question_text' => 'Q1', 'order' => 1, 'points' => 1]);
        $correct = $question->choices()->create(['choice_text' => 'A', 'is_correct' => true, 'order' => 1]);
        $question->choices()->create(['choice_text' => 'B', 'is_correct' => false, 'order' => 2]);

        $listener = User::factory()->create();
        $service = app(TestGradingService::class);
        $attempt = $service->startAttempt($lesson->test, $listener);

        $service->submitAttempt($attempt, [
            $question->id => ['selected_choice_ids' => [$correct->id]],
        ]);

        $attempt->refresh();
        $this->assertSame(TestAttemptStatus::Graded, $attempt->status);
        $this->assertTrue($attempt->passed);
        $this->assertSame(100, $attempt->score_percent);
    }

    public function test_text_answer_puts_attempt_in_awaiting_review_and_blocks_completion_until_graded(): void
    {
        $lesson = $this->makeLessonWithTest();
        $question = $lesson->test->questions()->create(['type' => 'text', 'question_text' => 'Опишите...', 'order' => 1, 'points' => 5]);

        $admin = User::factory()->admin()->create();
        $listener = User::factory()->create();
        $assignment = app(ProgressService::class)->assignCourse($lesson->courseModule->course, $listener, $admin, null);
        app(ProgressService::class)->acceptAgreement($assignment);

        $service = app(TestGradingService::class);
        $attempt = $service->startAttempt($lesson->test, $listener);

        $service->submitAttempt($attempt, [
            $question->id => ['free_text_answer' => 'Мой развёрнутый ответ.'],
        ]);

        $attempt->refresh();
        $this->assertSame(TestAttemptStatus::AwaitingReview, $attempt->status);

        // Lesson must still be locked/available-but-not-completed until the review finishes.
        $progress = $assignment->lessonProgress()->where('lesson_id', $lesson->id)->first();
        $this->assertSame(LessonProgressStatus::Available, $progress->status);

        $answer = $attempt->answers()->firstOrFail();
        $service->gradeTextAnswer($answer, 5, $admin);

        $attempt->refresh();
        $this->assertSame(TestAttemptStatus::Graded, $attempt->status);
        $this->assertTrue($attempt->passed);

        $progress->refresh();
        $this->assertSame(LessonProgressStatus::Completed, $progress->status);
    }

    public function test_max_attempts_is_enforced(): void
    {
        $lesson = $this->makeLessonWithTest(['max_attempts' => 1]);
        $lesson->test->questions()->create(['type' => 'single', 'question_text' => 'Q1', 'order' => 1, 'points' => 1]);

        $listener = User::factory()->create();
        $service = app(TestGradingService::class);

        $this->assertTrue($service->canStartAttempt($lesson->test, $listener));
        $service->startAttempt($lesson->test, $listener);
        $this->assertFalse($service->canStartAttempt($lesson->test, $listener));
    }

    public function test_failing_score_does_not_unlock_next_lesson(): void
    {
        $lesson = $this->makeLessonWithTest(['passing_score_percent' => 100]);
        $question = $lesson->test->questions()->create(['type' => 'single', 'question_text' => 'Q1', 'order' => 1, 'points' => 1]);
        $correct = $question->choices()->create(['choice_text' => 'A', 'is_correct' => true, 'order' => 1]);
        $wrong = $question->choices()->create(['choice_text' => 'B', 'is_correct' => false, 'order' => 2]);

        $admin = User::factory()->admin()->create();
        $listener = User::factory()->create();
        $assignment = app(ProgressService::class)->assignCourse($lesson->courseModule->course, $listener, $admin, null);
        app(ProgressService::class)->acceptAgreement($assignment);

        $service = app(TestGradingService::class);
        $attempt = $service->startAttempt($lesson->test, $listener);
        $service->submitAttempt($attempt, [$question->id => ['selected_choice_ids' => [$wrong->id]]]);

        $this->assertFalse($attempt->fresh()->passed);
        $progress = $assignment->lessonProgress()->where('lesson_id', $lesson->id)->first();
        $this->assertSame(LessonProgressStatus::Available, $progress->status);
        $this->assertSame(AssignmentStatus::InProgress, $assignment->fresh()->status);
    }
}
