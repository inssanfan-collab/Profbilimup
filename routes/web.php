<?php

use App\Enums\CourseStatus;
use App\Enums\UserRole;
use App\Http\Controllers\CertificateVerificationController;
use App\Http\Controllers\LocaleController;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\OrganizationSettings;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $publishedCourses = fn () => Course::where('status', CourseStatus::Published);

    return view('welcome', [
        'organization' => OrganizationSettings::current(),
        'publishedCoursesCount' => $publishedCourses()->count(),
        'academicHoursSum' => (int) $publishedCourses()->sum('academic_hours'),
        'certificatesCount' => Certificate::count(),
        'listenersCount' => User::where('role', UserRole::Listener)->count(),
    ]);
})->name('home');

Route::get('certificates/verify/{qrToken}', [CertificateVerificationController::class, 'show'])
    ->name('certificates.verify');

Route::get('locale/{locale}', [LocaleController::class, 'update'])->name('locale.update');

Route::get('dashboard', function () {
    $user = request()->user();

    return redirect()->route($user->isAdmin() || $user->isCurator() ? 'admin.dashboard' : 'listener.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
