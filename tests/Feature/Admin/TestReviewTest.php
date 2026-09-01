<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\TestReview\Queue;
use App\Livewire\Admin\TestReview\Review;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\Lesson;
use App\Models\User;
use App\Services\ProgressService;
use App\Services\TestGradingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TestReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_pending_review_queue_lists_awaiting_attempts_and_grading_removes_it(): void
    {
        $admin = User::factory()->admin()->create();
        $course = Course::factory()->create(['created_by' => $admin->id]);
        $module = CourseModule::factory()->for($course)->create();
        $lesson = Lesson::factory()->for($module, 'courseModule')->create();
        $lesson->test()->create(['title' => 'T', 'passing_score_percent' => 50]);
        $question = $lesson->test->questions()->create(['type' => 'text', 'question_text' => 'Опишите', 'order' => 1, 'points' => 10]);

        $listener = User::factory()->create();
        $assignment = app(ProgressService::class)->assignCourse($course, $listener, $admin, null);
        app(ProgressService::class)->acceptAgreement($assignment);

        $gradingService = app(TestGradingService::class);
        $attempt = $gradingService->startAttempt($lesson->test, $listener);
        $gradingService->submitAttempt($attempt, [$question->id => ['free_text_answer' => 'Ответ']]);

        Livewire::actingAs($admin)
            ->test(Queue::class)
            ->assertSee($lesson->title);

        $answer = $attempt->answers()->firstOrFail();

        Livewire::actingAs($admin)
            ->test(Review::class, ['attempt' => $attempt])
            ->set("points.{$answer->id}", 6)
            ->call('gradeAnswer', $answer->id)
            ->assertHasNoErrors();

        $this->assertTrue($attempt->fresh()->passed);

        Livewire::actingAs($admin)
            ->test(Queue::class)
            ->assertDontSee($lesson->title);
    }
}
