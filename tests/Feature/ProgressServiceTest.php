<?php

namespace Tests\Feature;

use App\Enums\AssignmentStatus;
use App\Enums\FinalOutcome;
use App\Enums\LessonProgressStatus;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\Lesson;
use App\Models\User;
use App\Services\ProgressService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProgressServiceTest extends TestCase
{
    use RefreshDatabase;

    private function makeCourseWithTwoLessons(): Course
    {
        $course = Course::factory()->create();
        $module = CourseModule::factory()->for($course)->create(['order' => 1]);
        Lesson::factory()->for($module, 'courseModule')->create(['title' => 'Урок 1', 'order' => 1]);
        Lesson::factory()->for($module, 'courseModule')->create(['title' => 'Урок 2', 'order' => 2]);

        return $course->fresh();
    }

    public function test_assigning_a_course_creates_locked_progress_rows_for_every_lesson(): void
    {
        $course = $this->makeCourseWithTwoLessons();
        $admin = User::factory()->admin()->create();
        $listener = User::factory()->create();

        $assignment = app(ProgressService::class)->assignCourse($course, $listener, $admin, null);

        $this->assertCount(2, $assignment->lessonProgress);
        $this->assertTrue($assignment->lessonProgress->every(fn ($p) => $p->status === LessonProgressStatus::Locked));
    }

    public function test_accepting_agreement_unlocks_only_the_first_lesson(): void
    {
        $course = $this->makeCourseWithTwoLessons();
        $admin = User::factory()->admin()->create();
        $listener = User::factory()->create();
        $service = app(ProgressService::class);

        $assignment = $service->assignCourse($course, $listener, $admin, null);
        $service->acceptAgreement($assignment);

        $lessons = $course->orderedLessons();
        $first = $assignment->lessonProgress()->where('lesson_id', $lessons[0]->id)->first();
        $second = $assignment->lessonProgress()->where('lesson_id', $lessons[1]->id)->first();

        $this->assertSame(LessonProgressStatus::Available, $first->status);
        $this->assertSame(LessonProgressStatus::Locked, $second->status);
        $this->assertSame(AssignmentStatus::InProgress, $assignment->fresh()->status);
    }

    public function test_completing_a_lesson_unlocks_the_next_one_and_finishing_the_last_completes_the_course(): void
    {
        $course = $this->makeCourseWithTwoLessons();
        $admin = User::factory()->admin()->create();
        $listener = User::factory()->create();
        $service = app(ProgressService::class);

        $assignment = $service->assignCourse($course, $listener, $admin, null);
        $service->acceptAgreement($assignment);

        $lessons = $course->orderedLessons();

        $service->completeLesson($assignment, $lessons[0]);
        $second = $assignment->lessonProgress()->where('lesson_id', $lessons[1]->id)->first();
        $this->assertSame(LessonProgressStatus::Available, $second->status);
        $this->assertSame(AssignmentStatus::InProgress, $assignment->fresh()->status);

        $service->completeLesson($assignment, $lessons[1]);
        $assignment->refresh();
        $this->assertSame(AssignmentStatus::Completed, $assignment->status);
        $this->assertSame(FinalOutcome::Passed, $assignment->final_outcome);
        $this->assertNotNull($assignment->completed_at);
    }

    public function test_a_locked_lesson_cannot_be_completed_out_of_order(): void
    {
        $course = $this->makeCourseWithTwoLessons();
        $admin = User::factory()->admin()->create();
        $listener = User::factory()->create();
        $service = app(ProgressService::class);

        $assignment = $service->assignCourse($course, $listener, $admin, null);
        $service->acceptAgreement($assignment);

        $lessons = $course->orderedLessons();
        // Try to complete the second lesson while it's still locked.
        $service->completeLesson($assignment, $lessons[1]);

        $second = $assignment->lessonProgress()->where('lesson_id', $lessons[1]->id)->first();
        $this->assertSame(LessonProgressStatus::Locked, $second->status);
    }
}
