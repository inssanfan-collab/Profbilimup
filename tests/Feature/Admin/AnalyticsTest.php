<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Analytics\CourseReport;
use App\Livewire\Admin\Analytics\Dashboard;
use App\Livewire\Admin\Analytics\ListenerReport;
use App\Models\Course;
use App\Models\User;
use App\Services\ProgressService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AnalyticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_shows_aggregate_counts(): void
    {
        $admin = User::factory()->admin()->create();
        $course = Course::factory()->create(['created_by' => $admin->id, 'title' => 'Курс аналитики']);
        $listener = User::factory()->create();
        app(ProgressService::class)->assignCourse($course, $listener, $admin, null);

        Livewire::actingAs($admin)
            ->test(Dashboard::class)
            ->assertViewHas('totalAssignments', 1)
            ->assertViewHas('completionRate', 0)
            ->assertSee('Курс аналитики');
    }

    public function test_course_report_lists_assigned_listeners_with_progress(): void
    {
        $admin = User::factory()->admin()->create();
        $course = Course::factory()->create(['created_by' => $admin->id]);
        $listener = User::factory()->create();
        $listener->listenerProfile()->create(['full_name' => 'Иванова Аружан']);
        app(ProgressService::class)->assignCourse($course, $listener, $admin, null);

        Livewire::actingAs($admin)
            ->test(CourseReport::class, ['course' => $course])
            ->assertSee('Иванова Аружан');
    }

    public function test_listener_report_lists_their_assignments(): void
    {
        $admin = User::factory()->admin()->create();
        $course = Course::factory()->create(['created_by' => $admin->id, 'title' => 'Курс для отчёта']);
        $listener = User::factory()->create(['role' => \App\Enums\UserRole::Listener]);
        app(ProgressService::class)->assignCourse($course, $listener, $admin, null);

        Livewire::actingAs($admin)
            ->test(ListenerReport::class, ['listener' => $listener])
            ->assertSee('Курс для отчёта');
    }

    public function test_listener_cannot_access_analytics(): void
    {
        $listener = User::factory()->create();

        $this->actingAs($listener)->get(route('admin.analytics.index'))->assertForbidden();
    }

    public function test_course_report_can_be_exported_to_excel(): void
    {
        $admin = User::factory()->admin()->create();
        $course = Course::factory()->create(['created_by' => $admin->id]);
        $listener = User::factory()->create();
        app(ProgressService::class)->assignCourse($course, $listener, $admin, null);

        $response = $this->actingAs($admin)->get(route('admin.analytics.course.export', $course));

        $response->assertOk();
        $this->assertStringContainsString('spreadsheetml', $response->headers->get('Content-Type'));
    }
}
