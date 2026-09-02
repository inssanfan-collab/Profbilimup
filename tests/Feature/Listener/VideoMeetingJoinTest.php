<?php

namespace Tests\Feature\Listener;

use App\Enums\VideoMeetingStatus;
use App\Livewire\Listener\CoursePlayer;
use App\Models\Course;
use App\Models\User;
use App\Services\ProgressService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class VideoMeetingJoinTest extends TestCase
{
    use RefreshDatabase;

    public function test_listener_can_join_a_scheduled_meeting_for_their_course(): void
    {
        $admin = User::factory()->admin()->create();
        $course = Course::factory()->create(['created_by' => $admin->id]);
        $meeting = $course->videoMeetings()->create([
            'created_by' => $admin->id,
            'external_meeting_id' => 'ext-1',
            'name' => 'Онлайн-встреча',
            'moderator_password' => 'mod',
            'attendee_password' => 'att',
            'status' => VideoMeetingStatus::Scheduled,
        ]);

        $listener = User::factory()->create();
        $assignment = app(ProgressService::class)->assignCourse($course, $listener, $admin, null);

        $response = Livewire::actingAs($listener)
            ->test(CoursePlayer::class, ['assignment' => $assignment])
            ->call('joinVideoMeeting', $meeting->id);

        $response->assertRedirect();
        $this->assertStringContainsString('/api/join?', $response->effects['redirect']);
    }

    public function test_listener_cannot_join_an_ended_meeting(): void
    {
        $admin = User::factory()->admin()->create();
        $course = Course::factory()->create(['created_by' => $admin->id]);
        $meeting = $course->videoMeetings()->create([
            'created_by' => $admin->id,
            'external_meeting_id' => 'ext-1',
            'name' => 'M',
            'moderator_password' => 'mod',
            'attendee_password' => 'att',
            'status' => VideoMeetingStatus::Ended,
        ]);

        $listener = User::factory()->create();
        $assignment = app(ProgressService::class)->assignCourse($course, $listener, $admin, null);

        Livewire::actingAs($listener)
            ->test(CoursePlayer::class, ['assignment' => $assignment])
            ->call('joinVideoMeeting', $meeting->id)
            ->assertForbidden();
    }
}
