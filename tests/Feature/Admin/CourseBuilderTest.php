<?php

namespace Tests\Feature\Admin;

use App\Enums\CourseStatus;
use App\Livewire\Admin\Courses\Builder;
use App\Livewire\Admin\Lessons\Form as LessonForm;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class CourseBuilderTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_add_modules_and_lessons_to_a_course(): void
    {
        $admin = User::factory()->admin()->create();
        $course = Course::factory()->create(['created_by' => $admin->id]);

        Livewire::actingAs($admin)
            ->test(Builder::class, ['course' => $course])
            ->set('newModuleTitle', 'Модуль 1')
            ->call('addModule')
            ->assertHasNoErrors();

        $module = $course->modules()->firstOrFail();
        $this->assertSame('Модуль 1', $module->title);

        Livewire::actingAs($admin)
            ->test(Builder::class, ['course' => $course])
            ->call('startAddingLesson', $module)
            ->set('newLessonTitle', 'Урок 1')
            ->call('addLesson')
            ->assertHasNoErrors();

        $this->assertSame('Урок 1', $module->lessons()->firstOrFail()->title);
    }

    public function test_reordering_swaps_module_order(): void
    {
        $admin = User::factory()->admin()->create();
        $course = Course::factory()->create(['created_by' => $admin->id]);
        $first = CourseModule::factory()->for($course)->create(['order' => 1]);
        $second = CourseModule::factory()->for($course)->create(['order' => 2]);

        Livewire::actingAs($admin)
            ->test(Builder::class, ['course' => $course])
            ->call('moveModuleUp', $second);

        $this->assertSame(1, $second->fresh()->order);
        $this->assertSame(2, $first->fresh()->order);
    }

    public function test_course_cannot_be_published_below_minimum_hours(): void
    {
        $admin = User::factory()->admin()->create();
        $course = Course::factory()->create([
            'created_by' => $admin->id,
            'academic_hours' => Course::MIN_ACADEMIC_HOURS_TO_PUBLISH - 1,
        ]);

        Livewire::actingAs($admin)
            ->test(Builder::class, ['course' => $course])
            ->call('publish')
            ->assertHasErrors('publish');

        $this->assertSame(CourseStatus::Draft, $course->fresh()->status);
    }

    public function test_course_can_be_published_at_or_above_minimum_hours(): void
    {
        $admin = User::factory()->admin()->create();
        $course = Course::factory()->create([
            'created_by' => $admin->id,
            'academic_hours' => Course::MIN_ACADEMIC_HOURS_TO_PUBLISH,
        ]);

        Livewire::actingAs($admin)
            ->test(Builder::class, ['course' => $course])
            ->call('publish')
            ->assertHasNoErrors();

        $this->assertSame(CourseStatus::Published, $course->fresh()->status);
    }

    public function test_admin_can_edit_lesson_content_video_and_upload_a_file(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $course = Course::factory()->create(['created_by' => $admin->id]);
        $module = CourseModule::factory()->for($course)->create();
        $lesson = Lesson::factory()->for($module, 'courseModule')->create();

        Livewire::actingAs($admin)
            ->test(LessonForm::class, ['lesson' => $lesson])
            ->set('title', 'Обновлённое название')
            ->set('content_html', '<div>Текст урока</div>')
            ->set('video_url', 'https://youtu.be/dQw4w9WgXcQ')
            ->call('save')
            ->assertHasNoErrors();

        $lesson->refresh();
        $this->assertSame('Обновлённое название', $lesson->title);
        $this->assertSame('https://www.youtube.com/embed/dQw4w9WgXcQ', $lesson->videoEmbedUrl());

        Livewire::actingAs($admin)
            ->test(LessonForm::class, ['lesson' => $lesson])
            ->set('newFiles', [UploadedFile::fake()->create('presentation.pdf', 500, 'application/pdf')])
            ->call('uploadFiles')
            ->assertHasNoErrors();

        $file = $lesson->files()->firstOrFail();
        $this->assertSame('presentation.pdf', $file->original_name);
        Storage::disk('public')->assertExists($file->path);
    }
}
