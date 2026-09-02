<?php

namespace App\Exports;

use App\Enums\AssignmentStatus;
use App\Enums\CertificateType;
use App\Models\Course;
use App\Models\CourseAssignment;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CourseReportExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(private readonly Course $course)
    {
    }

    public function collection(): Collection
    {
        return $this->course->assignments()->with(['listener.listenerProfile', 'certificate'])->get();
    }

    public function headings(): array
    {
        return ['ФИО', 'Место работы', 'Должность', 'Срок', 'Статус', 'Прогресс, %', 'Документ', '№ документа'];
    }

    public function map($assignment): array
    {
        /** @var CourseAssignment $assignment */
        $status = match (true) {
            $assignment->isOverdue() => 'Просрочен',
            $assignment->status === AssignmentStatus::Completed => 'Завершён',
            $assignment->status === AssignmentStatus::InProgress => 'В процессе',
            default => 'Назначен',
        };

        $document = match ($assignment->certificate?->type) {
            CertificateType::Certificate => 'Сертификат',
            CertificateType::AttendanceReference => 'Справка о прослушивании',
            default => '',
        };

        return [
            $assignment->listener->listenerProfile?->full_name ?? $assignment->listener->name,
            $assignment->listener->listenerProfile?->workplace,
            $assignment->listener->listenerProfile?->position,
            $assignment->deadline?->format('d.m.Y'),
            $status,
            $assignment->progressPercent(),
            $document,
            $assignment->certificate?->certificate_number,
        ];
    }
}
