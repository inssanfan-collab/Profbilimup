<?php

namespace Tests\Feature\Listener;

use App\Livewire\Listener\CoursePlayer;
use App\Livewire\Listener\LessonView;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\Lesson;
use App\Models\User;
use App\Services\ProgressService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CoursePlayerTest extends TestCase
{
    use RefreshDatabase;

    public function test_listener_cannot_open_another_listeners_assignment(): void
    {
        $course = Course::factory()->create();
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $assignment = app(ProgressService::class)->assignCourse($course, $owner, User::factory()->admin()->create(), null);

        $this->actingAs($intruder)
            ->get(route('listener.courses.show', $assignment))
            ->assertForbidden();
    }

    public function test_listener_must_accept_agreement_before_seeing_lessons(): void
    {
        $course = Course::factory()->create();
        $module = CourseModule::factory()->for($course)->create();
        Lesson::factory()->for($module, 'courseModule')->create();
        $listener = User::factory()->create();
        $assignment = app(ProgressService::class)->assignCourse($course, $listener, User::factory()->admin()->create(), null);

        Livewire::actingAs($listener)
            ->test(CoursePlayer::class, ['assignment' => $assignment])
            ->assertSee('Соглашение на обучение')
            ->call('acceptAgreement');

        $this->assertNotNull($assignment->fresh()->agreement_accepted_at);
    }

    public function test_overdue_assignment_blocks_agreement_acceptance(): void
    {
        $course = Course::factory()->create();
        $listener = User::factory()->create();
        $assignment = app(ProgressService::class)->assignCourse($course, $listener, User::factory()->admin()->create(), now()->subDay());

        Livewire::actingAs($listener)
            ->test(CoursePlayer::class, ['assignment' => $assignment])
            ->call('acceptAgreement')
            ->assertForbidden();
    }

    public function test_a_locked_lesson_cannot_be_viewed_directly(): void
    {
        $course = Course::factory()->create();
        $module = CourseModule::factory()->for($course)->create();
        $firstLesson = Lesson::factory()->for($module, 'courseModule')->create(['order' => 1]);
        $secondLesson = Lesson::factory()->for($module, 'courseModule')->create(['order' => 2]);
        $listener = User::factory()->create();
        $service = app(ProgressService::class);
        $assignment = $service->assignCourse($course, $listener, User::factory()->admin()->create(), null);
        $service->acceptAgreement($assignment);

        $this->actingAs($listener)
            ->get(route('listener.lessons.show', [$assignment, $secondLesson]))
            ->assertForbidden();

        Livewire::actingAs($listener)
            ->test(LessonView::class, ['assignment' => $assignment, 'lesson' => $firstLesson])
            ->call('complete')
            ->assertRedirect(route('listener.courses.show', $assignment));

        // Now the second lesson is unlocked and reachable.
        $this->actingAs($listener)
            ->get(route('listener.lessons.show', [$assignment, $secondLesson]))
            ->assertOk();
    }
}
