<?php

namespace Tests\Feature\Listener;

use App\Models\Course;
use App\Models\User;
use App\Services\ProgressService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_listener_dashboard_lists_assigned_courses_with_progress(): void
    {
        $listener = User::factory()->create();
        $course = Course::factory()->create(['title' => 'Функциональная грамотность']);
        app(ProgressService::class)->assignCourse($course, $listener, User::factory()->admin()->create(), null);

        $this->actingAs($listener)
            ->get(route('listener.dashboard'))
            ->assertOk()
            ->assertSee('Функциональная грамотность');
    }

    public function test_listener_dashboard_shows_empty_state_without_assignments(): void
    {
        $listener = User::factory()->create();

        $this->actingAs($listener)
            ->get(route('listener.dashboard'))
            ->assertOk()
            ->assertSee('Вам пока не назначено ни одного курса.');
    }
}
