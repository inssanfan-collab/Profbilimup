<?php

namespace App\Models;

use App\Enums\CertificateType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['course_assignment_id', 'type', 'certificate_number', 'pdf_path', 'qr_token', 'director_full_name_snapshot', 'issued_at', 'valid_until'])]
class Certificate extends Model
{
    protected function casts(): array
    {
        return [
            'type' => CertificateType::class,
            'issued_at' => 'datetime',
            'valid_until' => 'date',
        ];
    }

    public function courseAssignment(): BelongsTo
    {
        return $this->belongsTo(CourseAssignment::class);
    }

    public function moduleGrades(): HasMany
    {
        return $this->hasMany(CertificateModuleGrade::class);
    }
}
