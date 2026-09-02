<?php

namespace App\Http\Controllers\Admin;

use App\Exports\CourseReportExport;
use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AnalyticsExportController extends Controller
{
    public function courseReport(Course $course): BinaryFileResponse
    {
        $filename = Str::slug($course->title).'-report.xlsx';

        return Excel::download(new CourseReportExport($course), $filename);
    }
}
