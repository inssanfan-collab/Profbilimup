<?php

namespace Tests\Feature;

use App\Livewire\Shared\NotificationBell;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\Lesson;
use App\Models\User;
use App\Notifications\CertificateIssuedNotification;
use App\Notifications\CourseAssignedNotification;
use App\Notifications\TestAwaitingReviewNotification;
use App\Notifications\TestGradedNotification;
use App\Notifications\VideoMeetingScheduledNotification;
use App\Services\ProgressService;
use App\Services\TestGradingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Tests\TestCase;

class NotificationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_assigning_a_course_notifies_the_listener(): void
    {
        Notification::fake();

        $admin = User::factory()->admin()->create();
        $listener = User::factory()->create();
        $course = Course::factory()->create(['created_by' => $admin->id]);

        app(ProgressService::class)->assignCourse($course, $listener, $admin, null);

        Notification::assertSentTo($listener, CourseAssignedNotification::class);
    }

    public function test_passing_a_test_notifies_the_listener_and_completing_the_course_issues_a_certificate_notification(): void
    {
        Notification::fake();

        $admin = User::factory()->admin()->create();
        $course = Course::factory()->create(['created_by' => $admin->id]);
        $module = CourseModule::factory()->for($course)->create();
        $lesson = Lesson::factory()->for($module, 'courseModule')->create();
        $lesson->test()->create(['title' => 'T', 'passing_score_percent' => 50]);
        $question = $lesson->test->questions()->create(['type' => 'single', 'question_text' => 'Q', 'order' => 1, 'points' => 1]);
        $correct = $question->choices()->create(['choice_text' => 'A', 'is_correct' => true, 'order' => 1]);

        $listener = User::factory()->create();
        $progressService = app(ProgressService::class);
        $assignment = $progressService->assignCourse($course, $listener, $admin, null);
        $progressService->acceptAgreement($assignment);

        $gradingService = app(TestGradingService::class);
        $attempt = $gradingService->startAttempt($lesson->test, $listener);
        $gradingService->submitAttempt($attempt, [$question->id => ['selected_choice_ids' => [$correct->id]]]);

        Notification::assertSentTo($listener, TestGradedNotification::class);
        Notification::assertSentTo($listener, CertificateIssuedNotification::class);
    }

    public function test_a_free_text_answer_notifies_admins_that_review_is_pending(): void
    {
        Notification::fake();

        $admin = User::factory()->admin()->create();
        $course = Course::factory()->create(['created_by' => $admin->id]);
        $module = CourseModule::factory()->for($course)->create();
        $lesson = Lesson::factory()->for($module, 'courseModule')->create();
        $lesson->test()->create(['title' => 'T', 'passing_score_percent' => 50]);
        $question = $lesson->test->questions()->create(['type' => 'text', 'question_text' => 'Q', 'order' => 1, 'points' => 5]);

        $listener = User::factory()->create();
        $progressService = app(ProgressService::class);
        $assignment = $progressService->assignCourse($course, $listener, $admin, null);
        $progressService->acceptAgreement($assignment);

        $gradingService = app(TestGradingService::class);
        $attempt = $gradingService->startAttempt($lesson->test, $listener);
        $gradingService->submitAttempt($attempt, [$question->id => ['free_text_answer' => 'Ответ']]);

        Notification::assertSentTo($admin, TestAwaitingReviewNotification::class);
    }

    public function test_scheduling_a_video_meeting_notifies_assigned_listeners(): void
    {
        Http::fake(['*/api/create*' => Http::response('<response><returncode>SUCCESS</returncode></response>', 200)]);
        Notification::fake();

        $admin = User::factory()->admin()->create();
        $course = Course::factory()->create(['created_by' => $admin->id]);
        $listener = User::factory()->create();
        app(ProgressService::class)->assignCourse($course, $listener, $admin, null);

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\VideoMeetings\Index::class, ['course' => $course])
            ->set('name', 'Онлайн-встреча')
            ->call('schedule');

        Notification::assertSentTo($listener, VideoMeetingScheduledNotification::class);
    }

    public function test_notification_bell_shows_unread_count_and_marking_read_clears_it(): void
    {
        $listener = User::factory()->create();
        $admin = User::factory()->admin()->create();
        $course = Course::factory()->create(['created_by' => $admin->id]);

        app(ProgressService::class)->assignCourse($course, $listener, $admin, null);

        Livewire::actingAs($listener)
            ->test(NotificationBell::class)
            ->assertViewHas('unreadCount', 1);

        $notificationId = $listener->notifications()->first()->id;

        Livewire::actingAs($listener)
            ->test(NotificationBell::class)
            ->call('markAsRead', $notificationId)
            ->assertRedirect();

        $this->assertNotNull($listener->notifications()->first()->read_at);
    }
}
