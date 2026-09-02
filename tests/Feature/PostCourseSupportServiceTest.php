<?php

namespace Tests\Feature;

use App\Enums\CertificateType;
use App\Enums\PostCourseSupportStatus;
use App\Models\Course;
use App\Models\PostCoursePlan;
use App\Models\User;
use App\Notifications\LessonPlanFeedbackNotification;
use App\Notifications\PostCoursePlanReviewedNotification;
use App\Notifications\PostCoursePlanSubmittedNotification;
use App\Services\CertificateService;
use App\Services\PostCourseSupportService;
use App\Services\ProgressService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PostCourseSupportServiceTest extends TestCase
{
    use RefreshDatabase;

    private function completedAssignment(): array
    {
        $admin = User::factory()->admin()->create();
        $course = Course::factory()->create(['created_by' => $admin->id]);
        $listener = User::factory()->create();

        $progressService = app(ProgressService::class);
        $assignment = $progressService->assignCourse($course, $listener, $admin, null);
        $progressService->closeAsAttendanceOnly($assignment);

        return [$assignment->fresh(), $admin, $listener];
    }

    public function test_submitting_a_plan_notifies_all_admins(): void
    {
        Notification::fake();
        [$assignment, $admin] = $this->completedAssignment();

        $plan = app(PostCourseSupportService::class)->submitPlan($assignment, 'Мой индивидуальный план');

        $this->assertSame(PostCourseSupportStatus::Submitted, $plan->status);
        $this->assertSame('Мой индивидуальный план', $plan->content);
        Notification::assertSentTo($admin, PostCoursePlanSubmittedNotification::class);
    }

    public function test_resubmitting_a_plan_updates_the_existing_record_instead_of_creating_a_new_one(): void
    {
        [$assignment] = $this->completedAssignment();
        $service = app(PostCourseSupportService::class);

        $first = $service->submitPlan($assignment, 'Первая версия');
        $second = $service->submitPlan($assignment, 'Вторая версия');

        $this->assertSame($first->id, $second->id);
        $this->assertSame('Вторая версия', $second->fresh()->content);
        $this->assertSame(1, PostCoursePlan::where('course_assignment_id', $assignment->id)->count());
    }

    public function test_approving_a_plan_notifies_the_listener(): void
    {
        Notification::fake();
        [$assignment, $admin, $listener] = $this->completedAssignment();
        $service = app(PostCourseSupportService::class);

        $plan = $service->submitPlan($assignment, 'План');
        $service->reviewPlan($plan, PostCourseSupportStatus::Approved, $admin);

        $this->assertSame(PostCourseSupportStatus::Approved, $plan->fresh()->status);
        $this->assertSame($admin->id, $plan->fresh()->reviewed_by);
        Notification::assertSentTo($listener, PostCoursePlanReviewedNotification::class);
    }

    public function test_rejecting_a_plan_stores_the_review_note(): void
    {
        [$assignment, $admin] = $this->completedAssignment();
        $service = app(PostCourseSupportService::class);

        $plan = $service->submitPlan($assignment, 'План');
        $service->reviewPlan($plan, PostCourseSupportStatus::Rejected, $admin, 'Нужно доработать раздел 2');

        $this->assertSame(PostCourseSupportStatus::Rejected, $plan->fresh()->status);
        $this->assertSame('Нужно доработать раздел 2', $plan->fresh()->review_note);
    }

    public function test_giving_lesson_plan_feedback_notifies_the_listener(): void
    {
        Notification::fake();
        [$assignment, $admin, $listener] = $this->completedAssignment();
        $service = app(PostCourseSupportService::class);

        $plan = $service->submitPlan($assignment, 'План');
        $lessonPlan = $service->addLessonPlan($plan, 'Урок 1', 'Содержание урока 1');

        $service->giveLessonPlanFeedback($lessonPlan, 'Отличная работа', $admin);

        $this->assertSame('Отличная работа', $lessonPlan->fresh()->feedback_text);
        $this->assertSame($admin->id, $lessonPlan->fresh()->feedback_by);
        Notification::assertSentTo($listener, LessonPlanFeedbackNotification::class);
    }

    public function test_adding_a_report_persists_diagnostics(): void
    {
        [$assignment] = $this->completedAssignment();
        $service = app(PostCourseSupportService::class);

        $report = $service->addReport($assignment, 'Отчёт по итогам', 'До', 'После');

        $this->assertSame('Отчёт по итогам', $report->content);
        $this->assertSame('До', $report->diagnostic_before);
        $this->assertSame('После', $report->diagnostic_after);
    }

    public function test_adding_an_event_persists_it(): void
    {
        [$assignment] = $this->completedAssignment();
        $service = app(PostCourseSupportService::class);

        $event = $service->addEvent($assignment, 'seminar', 'Семинар по методике', now(), 'Описание');

        $this->assertSame('Семинар по методике', $event->title);
        $this->assertSame('seminar', $event->type->value);
    }

    public function test_issuing_a_reference_creates_a_post_course_reference_certificate(): void
    {
        Storage::fake('public');
        [$assignment] = $this->completedAssignment();
        $service = app(PostCourseSupportService::class);

        $certificate = $service->issueReference($assignment, app(CertificateService::class));

        $this->assertSame(CertificateType::PostCourseReference, $certificate->type);
        $this->assertStringStartsWith('ПС-', $certificate->certificate_number);
        Storage::disk('public')->assertExists($certificate->pdf_path);
    }
}
