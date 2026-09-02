<?php

namespace Tests\Feature\Admin;

use App\Enums\VideoMeetingStatus;
use App\Livewire\Admin\VideoMeetings\Index;
use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

class VideoMeetingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_schedule_a_video_meeting(): void
    {
        Http::fake([
            '*/api/create*' => Http::response('<response><returncode>SUCCESS</returncode></response>', 200),
        ]);

        $admin = User::factory()->admin()->create();
        $course = Course::factory()->create(['created_by' => $admin->id]);

        Livewire::actingAs($admin)
            ->test(Index::class, ['course' => $course])
            ->set('name', 'Разбор домашнего задания')
            ->call('schedule')
            ->assertHasNoErrors();

        $meeting = $course->videoMeetings()->firstOrFail();
        $this->assertSame('Разбор домашнего задания', $meeting->name);
        $this->assertSame(VideoMeetingStatus::Scheduled, $meeting->status);
    }

    public function test_scheduling_fails_gracefully_when_the_video_server_is_unreachable(): void
    {
        Http::fake([
            '*/api/create*' => Http::response('<response><returncode>FAILED</returncode></response>', 200),
        ]);

        $admin = User::factory()->admin()->create();
        $course = Course::factory()->create(['created_by' => $admin->id]);

        Livewire::actingAs($admin)
            ->test(Index::class, ['course' => $course])
            ->set('name', 'Встреча')
            ->call('schedule')
            ->assertHasErrors('name');

        $this->assertSame(0, $course->videoMeetings()->count());
    }

    public function test_admin_can_end_a_meeting(): void
    {
        Http::fake(['*/api/*' => Http::response('<response><returncode>SUCCESS</returncode></response>', 200)]);

        $admin = User::factory()->admin()->create();
        $course = Course::factory()->create(['created_by' => $admin->id]);
        $meeting = $course->videoMeetings()->create([
            'created_by' => $admin->id,
            'external_meeting_id' => 'ext-1',
            'name' => 'M',
            'moderator_password' => 'mod',
            'attendee_password' => 'att',
            'status' => VideoMeetingStatus::Scheduled,
        ]);

        Livewire::actingAs($admin)
            ->test(Index::class, ['course' => $course])
            ->call('end', $meeting->id);

        $this->assertSame(VideoMeetingStatus::Ended, $meeting->fresh()->status);
        $this->assertNotNull($meeting->fresh()->ended_at);
    }
}
