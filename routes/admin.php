<?php

use App\Http\Controllers\Admin\AnalyticsExportController;
use App\Livewire\Admin\Analytics\CourseReport;
use App\Livewire\Admin\Analytics\Dashboard as AnalyticsDashboard;
use App\Livewire\Admin\Analytics\ListenerReport;
use App\Livewire\Admin\Courses\Assign as CourseAssign;
use App\Livewire\Admin\Courses\Builder as CourseBuilder;
use App\Livewire\Admin\Courses\Form as CourseForm;
use App\Livewire\Admin\Courses\Index as CoursesIndex;
use App\Livewire\Admin\Curators\Form as CuratorForm;
use App\Livewire\Admin\Curators\Index as CuratorsIndex;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\Lessons\Form as LessonForm;
use App\Livewire\Admin\Lessons\TestBuilder;
use App\Livewire\Admin\Listeners\Form as ListenerForm;
use App\Livewire\Admin\Listeners\Index as ListenersIndex;
use App\Livewire\Admin\PostCourseSupport\Index as PostCourseSupportIndex;
use App\Livewire\Admin\PostCourseSupport\Show as PostCourseSupportShow;
use App\Livewire\Admin\Settings\Organization as OrganizationSettingsPage;
use App\Livewire\Admin\TestReview\Queue as TestReviewQueue;
use App\Livewire\Admin\TestReview\Review as TestReviewReview;
use App\Livewire\Admin\VideoMeetings\Index as VideoMeetingsIndex;
use Illuminate\Support\Facades\Route;

Route::get('/', AdminDashboard::class)->name('dashboard');

Route::get('listeners', ListenersIndex::class)->name('listeners.index');
Route::get('listeners/create', ListenerForm::class)->name('listeners.create');
Route::get('listeners/{user}/edit', ListenerForm::class)->name('listeners.edit');

Route::get('courses', CoursesIndex::class)->name('courses.index');
Route::get('courses/create', CourseForm::class)->name('courses.create');
Route::get('courses/{course}/edit', CourseForm::class)->name('courses.edit');
Route::get('courses/{course}/assign', CourseAssign::class)->name('courses.assign');
Route::get('courses/{course}/video-meetings', VideoMeetingsIndex::class)->name('video-meetings.index');
Route::get('courses/{course}', CourseBuilder::class)->name('courses.builder');

Route::get('lessons/{lesson}', LessonForm::class)->name('lessons.edit');
Route::get('lessons/{lesson}/test', TestBuilder::class)->name('lessons.test');

Route::get('test-review', TestReviewQueue::class)->name('test-review.index');
Route::get('test-review/{attempt}', TestReviewReview::class)->name('test-review.show');

Route::get('settings/organization', OrganizationSettingsPage::class)->name('settings.organization');

Route::get('analytics', AnalyticsDashboard::class)->name('analytics.index');
Route::get('analytics/courses/{course}', CourseReport::class)->name('analytics.course');
Route::get('analytics/courses/{course}/export', [AnalyticsExportController::class, 'courseReport'])->name('analytics.course.export');
Route::get('analytics/listeners/{listener}', ListenerReport::class)->name('analytics.listener');

Route::get('post-course-support', PostCourseSupportIndex::class)->name('post-course-support.index');
Route::get('post-course-support/{assignment}', PostCourseSupportShow::class)->name('post-course-support.show');

Route::get('curators', CuratorsIndex::class)->name('curators.index');
Route::get('curators/create', CuratorForm::class)->name('curators.create');
Route::get('curators/{user}/edit', CuratorForm::class)->name('curators.edit');
