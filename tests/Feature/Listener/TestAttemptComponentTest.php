<?php

namespace Tests\Feature\Listener;

use App\Livewire\Listener\TestAttempt;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\Lesson;
use App\Models\User;
use App\Services\ProgressService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TestAttemptComponentTest extends TestCase
{
    use RefreshDatabase;

    public function test_listener_can_take_a_test_from_the_lesson_and_pass_it(): void
    {
        $admin = User::factory()->admin()->create();
        $course = Course::factory()->create(['created_by' => $admin->id]);
        $module = CourseModule::factory()->for($course)->create();
        $lesson = Lesson::factory()->for($module, 'courseModule')->create();
        $lesson->test()->create(['title' => 'T', 'passing_score_percent' => 70]);
        $question = $lesson->test->questions()->create(['type' => 'single', 'question_text' => 'Q', 'order' => 1, 'points' => 1]);
        $correct = $question->choices()->create(['choice_text' => 'A', 'is_correct' => true, 'order' => 1]);
        $question->choices()->create(['choice_text' => 'B', 'is_correct' => false, 'order' => 2]);

        $listener = User::factory()->create();
        $assignment = app(ProgressService::class)->assignCourse($course, $listener, $admin, null);
        app(ProgressService::class)->acceptAgreement($assignment);

        Livewire::actingAs($listener)
            ->test(TestAttempt::class, ['assignment' => $assignment, 'lesson' => $lesson])
            ->call('start')
            ->set("responses.{$question->id}.selected_choice_ids", [$correct->id])
            ->call('submit')
            ->assertHasNoErrors();

        $assignment->refresh();
        $this->assertSame(
            \App\Enums\LessonProgressStatus::Completed,
            $assignment->lessonProgress()->where('lesson_id', $lesson->id)->first()->status,
        );
    }

    public function test_cannot_open_test_page_for_a_lesson_without_a_test(): void
    {
        $admin = User::factory()->admin()->create();
        $course = Course::factory()->create(['created_by' => $admin->id]);
        $module = CourseModule::factory()->for($course)->create();
        $lesson = Lesson::factory()->for($module, 'courseModule')->create();

        $listener = User::factory()->create();
        $assignment = app(ProgressService::class)->assignCourse($course, $listener, $admin, null);
        app(ProgressService::class)->acceptAgreement($assignment);

        $this->actingAs($listener)
            ->get(route('listener.tests.show', [$assignment, $lesson]))
            ->assertNotFound();
    }
}
