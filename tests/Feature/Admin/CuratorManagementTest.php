<?php

namespace Tests\Feature\Admin;

use App\Enums\CuratorPermission;
use App\Enums\UserRole;
use App\Livewire\Admin\Curators\Form;
use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Livewire\Volt\Volt;
use Tests\TestCase;

class CuratorManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_listener_cannot_access_curators_routes(): void
    {
        $listener = User::factory()->create(['role' => UserRole::Listener]);

        $this->actingAs($listener)->get('/admin/curators')->assertForbidden();
    }

    public function test_curator_cannot_access_curators_routes_even_with_every_permission(): void
    {
        $curator = User::factory()->curator(
            array_map(fn ($case) => $case->value, CuratorPermission::cases()),
            hasAllCoursesAccess: true,
        )->create();

        $this->actingAs($curator)->get('/admin/curators')->assertForbidden();
    }

    public function test_admin_can_view_curators_index(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get('/admin/curators')->assertOk();
    }

    public function test_admin_can_create_a_curator_with_default_permissions_and_selected_courses(): void
    {
        $admin = User::factory()->admin()->create();
        $course = Course::factory()->create(['created_by' => $admin->id]);

        Livewire::actingAs($admin)
            ->test(Form::class)
            ->set('full_name', 'Серикова Айгуль')
            ->set('email', 'curator@example.test')
            ->set('password', 'a-strong-password')
            ->set('password_confirmation', 'a-strong-password')
            ->set('courseScope', 'selected')
            ->set('courseIds', [$course->id])
            ->call('save')
            ->assertRedirect(route('admin.curators.index'));

        $curator = User::where('email', 'curator@example.test')->firstOrFail();

        $this->assertSame(UserRole::Curator, $curator->role);
        $this->assertFalse($curator->must_set_password);
        $this->assertNotNull($curator->email_verified_at);
        $this->assertSame([CuratorPermission::VideoMeetings->value], $curator->permissions);
        $this->assertFalse($curator->has_all_courses_access);
        $this->assertTrue($curator->curatorCourses()->whereKey($course->id)->exists());
    }

    public function test_admin_can_create_a_curator_with_access_to_all_courses(): void
    {
        $admin = User::factory()->admin()->create();

        Livewire::actingAs($admin)
            ->test(Form::class)
            ->set('full_name', 'Куратор Всего')
            ->set('email', 'all-courses@example.test')
            ->set('password', 'a-strong-password')
            ->set('password_confirmation', 'a-strong-password')
            ->set('courseScope', 'all')
            ->call('save')
            ->assertRedirect(route('admin.curators.index'));

        $curator = User::where('email', 'all-courses@example.test')->firstOrFail();

        $this->assertTrue($curator->has_all_courses_access);
        $this->assertSame(0, $curator->curatorCourses()->count());
    }

    public function test_curator_can_log_in_with_the_password_the_admin_set(): void
    {
        $admin = User::factory()->admin()->create();

        Livewire::actingAs($admin)
            ->test(Form::class)
            ->set('full_name', 'Куратор Логин')
            ->set('email', 'login-check@example.test')
            ->set('password', 'a-strong-password')
            ->set('password_confirmation', 'a-strong-password')
            ->set('courseScope', 'all')
            ->call('save');

        Volt::test('pages.auth.login')
            ->set('form.email', 'login-check@example.test')
            ->set('form.password', 'a-strong-password')
            ->call('login')
            ->assertHasNoErrors();

        $this->assertAuthenticated();
    }

    public function test_admin_can_edit_a_curators_permissions_and_course_scope(): void
    {
        $admin = User::factory()->admin()->create();
        $courseA = Course::factory()->create(['created_by' => $admin->id]);
        $courseB = Course::factory()->create(['created_by' => $admin->id]);
        $curator = User::factory()->curator([CuratorPermission::VideoMeetings->value])->create();
        $curator->curatorCourses()->sync([$courseA->id]);

        Livewire::actingAs($admin)
            ->test(Form::class, ['user' => $curator])
            ->set('permissions', [CuratorPermission::VideoMeetings->value, CuratorPermission::TestReview->value])
            ->set('courseScope', 'selected')
            ->set('courseIds', [$courseB->id])
            ->call('save')
            ->assertRedirect(route('admin.curators.index'));

        $curator->refresh();

        $this->assertEqualsCanonicalizing(
            [CuratorPermission::VideoMeetings->value, CuratorPermission::TestReview->value],
            $curator->permissions,
        );
        $this->assertFalse($curator->curatorCourses()->whereKey($courseA->id)->exists());
        $this->assertTrue($curator->curatorCourses()->whereKey($courseB->id)->exists());
    }

    public function test_editing_a_curator_without_a_new_password_keeps_the_old_one(): void
    {
        $admin = User::factory()->admin()->create();
        $curator = User::factory()->curator([CuratorPermission::VideoMeetings->value])->create();
        $originalHash = $curator->password;

        Livewire::actingAs($admin)
            ->test(Form::class, ['user' => $curator])
            ->set('permissions', [CuratorPermission::VideoMeetings->value])
            ->set('courseScope', 'all')
            ->call('save');

        $this->assertSame($originalHash, $curator->fresh()->password);
    }
}
