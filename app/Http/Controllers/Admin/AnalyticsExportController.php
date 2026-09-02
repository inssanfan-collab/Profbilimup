<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CuratorPermission;
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
        abort_unless(auth()->user()->hasPermission(CuratorPermission::Analytics), 403);
        abort_unless(auth()->user()->hasCourseAccess($course), 403);

        $filename = Str::slug($course->title).'-report.xlsx';

        return Excel::download(new CourseReportExport($course), $filename);
    }
}
