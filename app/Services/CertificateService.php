<?php

namespace App\Services;

use App\Enums\CertificateType;
use App\Enums\FinalOutcome;
use App\Enums\TestAttemptStatus;
use App\Models\Certificate;
use App\Models\CertificateModuleGrade;
use App\Models\CourseAssignment;
use App\Models\OrganizationSettings;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\Builder\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class CertificateService
{
    public function generate(CourseAssignment $assignment): Certificate
    {
        if ($assignment->certificate) {
            return $assignment->certificate;
        }

        return DB::transaction(function () use ($assignment) {
            $organization = OrganizationSettings::current();
            $type = $assignment->final_outcome === FinalOutcome::Passed
                ? CertificateType::Certificate
                : CertificateType::AttendanceReference;

            $certificate = Certificate::create([
                'course_assignment_id' => $assignment->id,
                'type' => $type,
                'certificate_number' => $this->nextCertificateNumber($type),
                'pdf_path' => '',
                'qr_token' => (string) Str::uuid(),
                'director_full_name_snapshot' => $organization->director_full_name,
                'issued_at' => now(),
                'valid_until' => $type === CertificateType::Certificate ? now()->addYears(3) : null,
            ]);

            $this->attachModuleGrades($certificate, $assignment);

            $certificate->update([
                'pdf_path' => $this->renderPdf($certificate, $assignment, $organization),
            ]);

            return $certificate->fresh();
        });
    }

    private function attachModuleGrades(Certificate $certificate, CourseAssignment $assignment): void
    {
        foreach ($assignment->course->modules as $module) {
            $scores = [];

            foreach ($module->lessons as $lesson) {
                if (! $lesson->test) {
                    continue;
                }

                $bestAttempt = $lesson->test->attempts()
                    ->where('listener_id', $assignment->listener_id)
                    ->where('status', TestAttemptStatus::Graded)
                    ->orderByDesc('score_percent')
                    ->first();

                if ($bestAttempt) {
                    $scores[] = $bestAttempt->score_percent;
                }
            }

            CertificateModuleGrade::create([
                'certificate_id' => $certificate->id,
                'course_module_id' => $module->id,
                'module_title_snapshot' => $module->title,
                'score_percent' => count($scores) > 0 ? (int) round(array_sum($scores) / count($scores)) : null,
            ]);
        }
    }

    private function nextCertificateNumber(CertificateType $type): string
    {
        $prefix = $type === CertificateType::Certificate ? 'УП' : 'СП';
        $year = now()->format('Y');
        $sequence = Certificate::where('type', $type)->whereYear('issued_at', $year)->count() + 1;

        return sprintf('%s-%s-%06d', $prefix, $year, $sequence);
    }

    private function renderPdf(Certificate $certificate, CourseAssignment $assignment, OrganizationSettings $organization): string
    {
        $verifyUrl = URL::to('/certificates/verify/'.$certificate->qr_token);
        $qrDataUri = (new Builder())->build(data: $verifyUrl, size: 180, margin: 5)->getDataUri();

        $view = $certificate->type === CertificateType::Certificate
            ? 'certificates.certificate'
            : 'certificates.attendance-reference';

        $pdf = Pdf::loadView($view, [
            'certificate' => $certificate,
            'assignment' => $assignment,
            'organization' => $organization,
            'listener' => $assignment->listener,
            'course' => $assignment->course,
            'moduleGrades' => $certificate->moduleGrades()->get(),
            'qrDataUri' => $qrDataUri,
        ]);

        $path = "certificates/{$certificate->certificate_number}.pdf";
        Storage::disk('public')->put($path, $pdf->output());

        return $path;
    }
}
