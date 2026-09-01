<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Courses\Assign;
use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CourseAssignmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_assign_a_course_to_a_listener_with_a_deadline(): void
    {
        $admin = User::factory()->admin()->create();
        $course = Course::factory()->create(['created_by' => $admin->id]);
        $listener = User::factory()->create();

        Livewire::actingAs($admin)
            ->test(Assign::class, ['course' => $course])
            ->set('listenerId', $listener->id)
            ->set('deadline', now()->addMonth()->toDateString())
            ->call('assign')
            ->assertHasNoErrors();

        $assignment = $course->assignments()->firstOrFail();
        $this->assertSame($listener->id, $assignment->listener_id);
        $this->assertSame($admin->id, $assignment->assigned_by);
        $this->assertNotNull($assignment->deadline);
    }

    public function test_cannot_assign_the_same_course_to_the_same_listener_twice(): void
    {
        $admin = User::factory()->admin()->create();
        $course = Course::factory()->create(['created_by' => $admin->id]);
        $listener = User::factory()->create();

        app(\App\Services\ProgressService::class)->assignCourse($course, $listener, $admin, null);

        // Already-assigned listeners are excluded from the picker, but a resubmit
        // (double-click, stale form) must fail validation, not crash on the DB's
        // unique constraint.
        Livewire::actingAs($admin)
            ->test(Assign::class, ['course' => $course])
            ->set('listenerId', $listener->id)
            ->call('assign')
            ->assertHasErrors('listenerId');

        $this->assertSame(1, $course->assignments()->count());
    }
}
