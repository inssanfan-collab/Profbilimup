<?php

use App\Http\Controllers\CertificateVerificationController;
use App\Http\Controllers\LocaleController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::get('certificates/verify/{qrToken}', [CertificateVerificationController::class, 'show'])
    ->name('certificates.verify');

Route::get('locale/{locale}', [LocaleController::class, 'update'])->name('locale.update');

Route::get('dashboard', function () {
    $user = request()->user();

    return redirect()->route($user->isAdmin() ? 'admin.dashboard' : 'listener.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
