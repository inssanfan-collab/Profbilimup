<?php

namespace App\Services;

use App\Enums\PostCourseSupportStatus;
use App\Enums\UserRole;
use App\Models\CourseAssignment;
use App\Models\PostCourseEvent;
use App\Models\PostCourseLessonPlan;
use App\Models\PostCoursePlan;
use App\Models\PostCourseReport;
use App\Models\User;
use App\Notifications\LessonPlanFeedbackNotification;
use App\Notifications\PostCoursePlanReviewedNotification;
use App\Notifications\PostCoursePlanSubmittedNotification;
use Illuminate\Support\Facades\Notification;

class PostCourseSupportService
{
    public function submitPlan(CourseAssignment $assignment, string $content): PostCoursePlan
    {
        $plan = $assignment->postCoursePlan()->updateOrCreate([], [
            'content' => $content,
            'status' => PostCourseSupportStatus::Submitted,
            'submitted_at' => now(),
            'reviewed_by' => null,
            'reviewed_at' => null,
            'review_note' => null,
        ]);

        Notification::send(User::where('role', UserRole::Admin)->get(), new PostCoursePlanSubmittedNotification($plan));

        return $plan;
    }

    public function reviewPlan(PostCoursePlan $plan, PostCourseSupportStatus $status, User $reviewer, ?string $note = null): void
    {
        $plan->update([
            'status' => $status,
            'reviewed_by' => $reviewer->id,
            'reviewed_at' => now(),
            'review_note' => $note,
        ]);

        $plan->courseAssignment->listener->notify(new PostCoursePlanReviewedNotification($plan->fresh()));
    }

    public function addLessonPlan(PostCoursePlan $plan, string $title, string $content): PostCourseLessonPlan
    {
        return $plan->lessonPlans()->create([
            'title' => $title,
            'content' => $content,
        ]);
    }

    public function giveLessonPlanFeedback(PostCourseLessonPlan $lessonPlan, string $feedback, User $reviewer): void
    {
        $lessonPlan->update([
            'feedback_text' => $feedback,
            'feedback_by' => $reviewer->id,
            'feedback_at' => now(),
        ]);

        $lessonPlan->postCoursePlan->courseAssignment->listener->notify(new LessonPlanFeedbackNotification($lessonPlan->fresh()));
    }

    public function addReport(CourseAssignment $assignment, string $content, ?string $diagnosticBefore, ?string $diagnosticAfter): PostCourseReport
    {
        return $assignment->postCourseReports()->create([
            'content' => $content,
            'diagnostic_before' => $diagnosticBefore,
            'diagnostic_after' => $diagnosticAfter,
            'submitted_at' => now(),
        ]);
    }

    public function addEvent(CourseAssignment $assignment, string $type, string $title, \DateTimeInterface $eventDate, ?string $description): PostCourseEvent
    {
        return $assignment->postCourseEvents()->create([
            'type' => $type,
            'title' => $title,
            'event_date' => $eventDate,
            'description' => $description,
        ]);
    }

    public function issueReference(CourseAssignment $assignment, CertificateService $certificateService)
    {
        return $certificateService->generatePostCourseReference($assignment);
    }
}
