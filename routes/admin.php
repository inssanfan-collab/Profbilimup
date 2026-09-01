<?php

use App\Livewire\Admin\Courses\Assign as CourseAssign;
use App\Livewire\Admin\Courses\Builder as CourseBuilder;
use App\Livewire\Admin\Courses\Form as CourseForm;
use App\Livewire\Admin\Courses\Index as CoursesIndex;
use App\Livewire\Admin\Lessons\Form as LessonForm;
use App\Livewire\Admin\Lessons\TestBuilder;
use App\Livewire\Admin\Listeners\Form as ListenerForm;
use App\Livewire\Admin\Listeners\Index as ListenersIndex;
use App\Livewire\Admin\TestReview\Queue as TestReviewQueue;
use App\Livewire\Admin\TestReview\Review as TestReviewReview;
use Illuminate\Support\Facades\Route;

Route::view('/', 'admin.dashboard')->name('dashboard');

Route::get('listeners', ListenersIndex::class)->name('listeners.index');
Route::get('listeners/create', ListenerForm::class)->name('listeners.create');
Route::get('listeners/{user}/edit', ListenerForm::class)->name('listeners.edit');

Route::get('courses', CoursesIndex::class)->name('courses.index');
Route::get('courses/create', CourseForm::class)->name('courses.create');
Route::get('courses/{course}/edit', CourseForm::class)->name('courses.edit');
Route::get('courses/{course}/assign', CourseAssign::class)->name('courses.assign');
Route::get('courses/{course}', CourseBuilder::class)->name('courses.builder');

Route::get('lessons/{lesson}', LessonForm::class)->name('lessons.edit');
Route::get('lessons/{lesson}/test', TestBuilder::class)->name('lessons.test');

Route::get('test-review', TestReviewQueue::class)->name('test-review.index');
Route::get('test-review/{attempt}', TestReviewReview::class)->name('test-review.show');
