<?php

namespace Tests\Feature\Admin;

use App\Enums\CuratorPermission;
use App\Enums\VideoMeetingStatus;
use App\Livewire\Admin\Courses\Form as CourseForm;
use App\Livewire\Admin\Courses\Index as CoursesIndex;
use App\Livewire\Admin\TestReview\Queue as TestReviewQueue;
use App\Livewire\Admin\VideoMeetings\Index as VideoMeetingsIndex;
use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

class CuratorAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_curator_with_only_video_meetings_permission_can_manage_meetings_for_their_course(): void
    {
        Http::fake(['*/api/create*' => Http::response('<response><returncode>SUCCESS</returncode></response>', 200)]);

        $admin = User::factory()->admin()->create();
        $course = Course::factory()->create(['created_by' => $admin->id]);
        $curator = User::factory()->curator([CuratorPermission::VideoMeetings->value])->create();
        $curator->curatorCourses()->sync([$course->id]);

        Livewire::actingAs($curator)
            ->test(VideoMeetingsIndex::class, ['course' => $course])
            ->set('name', 'Разбор задания')
            ->call('schedule')
            ->assertHasNoErrors();

        $this->assertSame(1, $course->videoMeetings()->count());
    }

    public function test_curator_cannot_manage_meetings_for_a_course_outside_their_scope(): void
    {
        $admin = User::factory()->admin()->create();
        $ownCourse = Course::factory()->create(['created_by' => $admin->id]);
        $otherCourse = Course::factory()->create(['created_by' => $admin->id]);
        $curator = User::factory()->curator([CuratorPermission::VideoMeetings->value])->create();
        $curator->curatorCourses()->sync([$ownCourse->id]);

        Livewire::actingAs($curator)
            ->test(VideoMeetingsIndex::class, ['course' => $otherCourse])
            ->assertStatus(403);
    }

    public function test_curator_without_video_meetings_permission_cannot_open_the_meetings_page(): void
    {
        $admin = User::factory()->admin()->create();
        $course = Course::factory()->create(['created_by' => $admin->id]);
        $curator = User::factory()->curator([CuratorPermission::Courses->value], hasAllCoursesAccess: true)->create();

        Livewire::actingAs($curator)
            ->test(VideoMeetingsIndex::class, ['course' => $course])
            ->assertStatus(403);
    }

    public function test_curator_with_only_video_meetings_permission_cannot_open_test_review(): void
    {
        $curator = User::factory()->curator([CuratorPermission::VideoMeetings->value])->create();

        $this->actingAs($curator)->get('/admin/test-review')->assertForbidden();
    }

    public function test_test_review_queue_is_scoped_to_the_curators_courses(): void
    {
        Http::fake(); // no BBB calls expected in this test
        $admin = User::factory()->admin()->create();
        $ownCourse = Course::factory()->create(['created_by' => $admin->id]);
        $otherCourse = Course::factory()->create(['created_by' => $admin->id]);

        $ownModule = $ownCourse->modules()->create(['title' => 'M1', 'order' => 1]);
        $ownLesson = $ownModule->lessons()->create(['title' => 'L1', 'order' => 1]);

        $otherModule = $otherCourse->modules()->create(['title' => 'M2', 'order' => 1]);
        $otherLesson = $otherModule->lessons()->create(['title' => 'L2', 'order' => 1]);

        $curator = User::factory()->curator([CuratorPermission::TestReview->value])->create();
        $curator->curatorCourses()->sync([$ownCourse->id]);

        Livewire::actingAs($curator)
            ->test(TestReviewQueue::class)
            ->assertViewHas('attempts', fn ($attempts) => $attempts->every(
                fn ($attempt) => $attempt->test->lesson->courseModule->course_id === $ownCourse->id,
            ));
    }

    public function test_curator_with_selected_course_scope_cannot_create_a_new_course(): void
    {
        $curator = User::factory()->curator([CuratorPermission::Courses->value], hasAllCoursesAccess: false)->create();

        Livewire::actingAs($curator)
            ->test(CourseForm::class)
            ->assertStatus(403);
    }

    public function test_curator_with_all_courses_access_can_create_a_new_course(): void
    {
        $curator = User::factory()->curator([CuratorPermission::Courses->value], hasAllCoursesAccess: true)->create();

        Livewire::actingAs($curator)
            ->test(CourseForm::class)
            ->set('title', 'Новый курс куратора')
            ->set('academic_hours', 40)
            ->call('save');

        $this->assertSame(1, Course::where('title', 'Новый курс куратора')->count());
    }

    public function test_courses_index_is_visible_to_a_curator_with_only_video_meetings_permission(): void
    {
        $admin = User::factory()->admin()->create();
        $course = Course::factory()->create(['created_by' => $admin->id]);
        $curator = User::factory()->curator([CuratorPermission::VideoMeetings->value])->create();
        $curator->curatorCourses()->sync([$course->id]);

        Livewire::actingAs($curator)
            ->test(CoursesIndex::class)
            ->assertOk()
            ->assertSee($course->title);
    }

    public function test_courses_index_only_lists_courses_in_the_curators_scope(): void
    {
        $admin = User::factory()->admin()->create();
        $ownCourse = Course::factory()->create(['created_by' => $admin->id, 'title' => 'Мой курс']);
        $otherCourse = Course::factory()->create(['created_by' => $admin->id, 'title' => 'Чужой курс']);
        $curator = User::factory()->curator([CuratorPermission::Courses->value])->create();
        $curator->curatorCourses()->sync([$ownCourse->id]);

        Livewire::actingAs($curator)
            ->test(CoursesIndex::class)
            ->assertSee('Мой курс')
            ->assertDontSee('Чужой курс');
    }

    public function test_curator_cannot_access_organization_settings_even_with_every_permission(): void
    {
        $curator = User::factory()->curator(
            array_map(fn ($case) => $case->value, CuratorPermission::cases()),
            hasAllCoursesAccess: true,
        )->create();

        $this->actingAs($curator)->get('/admin/settings/organization')->assertForbidden();
    }

    public function test_curator_joins_the_video_meeting_as_moderator(): void
    {
        $admin = User::factory()->admin()->create();
        $course = Course::factory()->create(['created_by' => $admin->id]);
        $meeting = $course->videoMeetings()->create([
            'created_by' => $admin->id,
            'external_meeting_id' => 'ext-curator-1',
            'name' => 'Встреча',
            'moderator_password' => 'mod-pass',
            'attendee_password' => 'att-pass',
            'status' => VideoMeetingStatus::Scheduled,
        ]);
        $curator = User::factory()->curator([CuratorPermission::VideoMeetings->value])->create();
        $curator->curatorCourses()->sync([$course->id]);

        Livewire::actingAs($curator)
            ->test(VideoMeetingsIndex::class, ['course' => $course])
            ->call('joinAsModerator', $meeting->id)
            ->assertRedirect();
    }
}
