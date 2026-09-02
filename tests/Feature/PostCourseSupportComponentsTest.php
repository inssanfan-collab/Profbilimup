<?php

namespace Tests\Feature;

use App\Enums\PostCourseSupportStatus;
use App\Livewire\Admin\PostCourseSupport\Index as AdminIndex;
use App\Livewire\Admin\PostCourseSupport\Show as AdminShow;
use App\Livewire\Listener\PostCourseSupport\Show as ListenerShow;
use App\Models\Course;
use App\Models\User;
use App\Services\ProgressService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PostCourseSupportComponentsTest extends TestCase
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

    public function test_listener_cannot_open_post_course_support_before_the_course_is_completed(): void
    {
        $admin = User::factory()->admin()->create();
        $course = Course::factory()->create(['created_by' => $admin->id]);
        $listener = User::factory()->create();
        $assignment = app(ProgressService::class)->assignCourse($course, $listener, $admin, null);

        Livewire::actingAs($listener)
            ->test(ListenerShow::class, ['assignment' => $assignment])
            ->assertStatus(403);
    }

    public function test_another_listener_cannot_open_someone_elses_post_course_support(): void
    {
        [$assignment] = $this->completedAssignment();
        $stranger = User::factory()->create();

        Livewire::actingAs($stranger)
            ->test(ListenerShow::class, ['assignment' => $assignment])
            ->assertStatus(403);
    }

    public function test_listener_can_submit_a_plan_add_a_lesson_plan_a_report_and_an_event(): void
    {
        [$assignment, , $listener] = $this->completedAssignment();

        $component = Livewire::actingAs($listener)
            ->test(ListenerShow::class, ['assignment' => $assignment])
            ->set('planContent', 'Мой план на следующий год')
            ->call('submitPlan')
            ->assertHasNoErrors();

        $this->assertNotNull($assignment->fresh()->postCoursePlan);
        $this->assertSame(PostCourseSupportStatus::Submitted, $assignment->fresh()->postCoursePlan->status);

        $component->set('newLessonPlanTitle', 'Урок про грамотность')
            ->set('newLessonPlanContent', 'Содержание урока')
            ->call('addLessonPlan')
            ->assertHasNoErrors();

        $this->assertSame(1, $assignment->fresh()->postCoursePlan->lessonPlans()->count());

        $component->set('reportContent', 'Отчёт')
            ->call('addReport')
            ->assertHasNoErrors();

        $this->assertSame(1, $assignment->fresh()->postCourseReports()->count());

        $component->set('eventType', 'seminar')
            ->set('eventTitle', 'Семинар')
            ->set('eventDate', now()->format('Y-m-d'))
            ->call('addEvent')
            ->assertHasNoErrors();

        $this->assertSame(1, $assignment->fresh()->postCourseEvents()->count());
    }

    public function test_admin_index_lists_completed_assignments_with_plan_status(): void
    {
        [$assignment, $admin] = $this->completedAssignment();
        app(\App\Services\PostCourseSupportService::class)->submitPlan($assignment, 'План');

        Livewire::actingAs($admin)
            ->test(AdminIndex::class)
            ->assertSee($assignment->listener->name)
            ->assertSee($assignment->course->title);
    }

    public function test_admin_can_approve_a_plan_and_give_lesson_plan_feedback(): void
    {
        [$assignment, $admin] = $this->completedAssignment();
        $service = app(\App\Services\PostCourseSupportService::class);
        $plan = $service->submitPlan($assignment, 'План');
        $lessonPlan = $service->addLessonPlan($plan, 'Урок 1', 'Содержание');

        Livewire::actingAs($admin)
            ->test(AdminShow::class, ['assignment' => $assignment])
            ->call('approvePlan')
            ->assertHasNoErrors();

        $this->assertSame(PostCourseSupportStatus::Approved, $plan->fresh()->status);

        Livewire::actingAs($admin)
            ->test(AdminShow::class, ['assignment' => $assignment])
            ->set("feedbackText.{$lessonPlan->id}", 'Хорошая работа')
            ->call('giveFeedback', $lessonPlan->id)
            ->assertHasNoErrors();

        $this->assertSame('Хорошая работа', $lessonPlan->fresh()->feedback_text);
    }

    public function test_admin_rejecting_a_plan_requires_a_review_note(): void
    {
        [$assignment, $admin] = $this->completedAssignment();
        $plan = app(\App\Services\PostCourseSupportService::class)->submitPlan($assignment, 'План');

        Livewire::actingAs($admin)
            ->test(AdminShow::class, ['assignment' => $assignment])
            ->call('rejectPlan')
            ->assertHasErrors('reviewNote');

        $this->assertSame(PostCourseSupportStatus::Submitted, $plan->fresh()->status);
    }

    public function test_admin_can_issue_a_post_course_reference(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');
        [$assignment, $admin] = $this->completedAssignment();

        Livewire::actingAs($admin)
            ->test(AdminShow::class, ['assignment' => $assignment])
            ->call('issueReference')
            ->assertHasNoErrors();

        $this->assertNotNull($assignment->fresh()->postCourseReference);
    }
}
