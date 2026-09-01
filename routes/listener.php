<?php

use App\Livewire\Listener\CoursePlayer;
use App\Livewire\Listener\Dashboard;
use App\Livewire\Listener\LessonView;
use Illuminate\Support\Facades\Route;

Route::get('/', Dashboard::class)->name('dashboard');
Route::get('courses/{assignment}', CoursePlayer::class)->name('courses.show');
Route::get('courses/{assignment}/lessons/{lesson}', LessonView::class)->name('lessons.show');
