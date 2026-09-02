<?php

use App\Enums\CourseStatus;
use App\Http\Controllers\CertificateVerificationController;
use App\Http\Controllers\LocaleController;
use App\Models\Course;
use App\Models\OrganizationSettings;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome', [
        'organization' => OrganizationSettings::current(),
        'publishedCoursesCount' => Course::where('status', CourseStatus::Published)->count(),
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
