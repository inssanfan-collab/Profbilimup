<?php

namespace Tests\Feature;

use App\Enums\CertificateType;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\Lesson;
use App\Models\OrganizationSettings;
use App\Models\User;
use App\Services\ProgressService;
use App\Services\TestGradingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CertificateServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_completing_a_course_automatically_issues_a_certificate_with_module_grades(): void
    {
        Storage::fake('public');
        OrganizationSettings::current()->update(['name_ru' => 'Учебный центр «Білім»', 'director_full_name' => 'Иванов И.И.']);

        $admin = User::factory()->admin()->create();
        $course = Course::factory()->create(['created_by' => $admin->id, 'title' => 'Функциональная грамотность']);
        $module = CourseModule::factory()->for($course)->create(['title' => 'Модуль 1']);
        $lesson = Lesson::factory()->for($module, 'courseModule')->create();
        $lesson->test()->create(['title' => 'T', 'passing_score_percent' => 50]);
        $question = $lesson->test->questions()->create(['type' => 'single', 'question_text' => 'Q', 'order' => 1, 'points' => 1]);
        $correct = $question->choices()->create(['choice_text' => 'A', 'is_correct' => true, 'order' => 1]);

        $listener = User::factory()->create();
        $progressService = app(ProgressService::class);
        $assignment = $progressService->assignCourse($course, $listener, $admin, null);
        $progressService->acceptAgreement($assignment);

        $gradingService = app(TestGradingService::class);
        $attempt = $gradingService->startAttempt($lesson->test, $listener);
        $gradingService->submitAttempt($attempt, [$question->id => ['selected_choice_ids' => [$correct->id]]]);

        $certificate = $assignment->fresh()->certificate;

        $this->assertNotNull($certificate);
        $this->assertSame(CertificateType::Certificate, $certificate->type);
        $this->assertStringStartsWith('УП-', $certificate->certificate_number);
        Storage::disk('public')->assertExists($certificate->pdf_path);

        $grade = $certificate->moduleGrades()->firstOrFail();
        $this->assertSame('Модуль 1', $grade->module_title_snapshot);
        $this->assertSame(100, $grade->score_percent);
    }

    public function test_closing_an_unfinished_assignment_as_attendance_only_issues_a_reference_not_a_certificate(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $course = Course::factory()->create(['created_by' => $admin->id]);
        $module = CourseModule::factory()->for($course)->create();
        Lesson::factory()->for($module, 'courseModule')->create();

        $listener = User::factory()->create();
        $progressService = app(ProgressService::class);
        $assignment = $progressService->assignCourse($course, $listener, $admin, null);
        $progressService->acceptAgreement($assignment);

        $progressService->closeAsAttendanceOnly($assignment);

        $certificate = $assignment->fresh()->certificate;
        $this->assertNotNull($certificate);
        $this->assertSame(CertificateType::AttendanceReference, $certificate->type);
        $this->assertNull($certificate->valid_until);
        Storage::disk('public')->assertExists($certificate->pdf_path);
    }

    public function test_certificate_verification_page_shows_document_details_for_a_valid_token(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $course = Course::factory()->create(['created_by' => $admin->id, 'title' => 'Проверяемый курс']);
        $listener = User::factory()->create();
        $progressService = app(ProgressService::class);
        $assignment = $progressService->assignCourse($course, $listener, $admin, null);
        $progressService->closeAsAttendanceOnly($assignment);

        $certificate = $assignment->fresh()->certificate;

        $this->get(route('certificates.verify', $certificate->qr_token))
            ->assertOk()
            ->assertSee('Проверяемый курс')
            ->assertSee($certificate->certificate_number);
    }

    public function test_certificate_verification_page_reports_unknown_token(): void
    {
        $this->get(route('certificates.verify', 'not-a-real-token'))
            ->assertOk()
            ->assertSee('не найден');
    }
}
