<?php

namespace App\Enums;

enum CertificateType: string
{
    case Certificate = 'certificate';
    case AttendanceReference = 'attendance_reference';
}
