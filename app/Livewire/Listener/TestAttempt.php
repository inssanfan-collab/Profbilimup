<?php

namespace App\Livewire\Listener;

use App\Enums\QuestionType;
use App\Livewire\Concerns\HasPageHeader;
use App\Models\CourseAssignment;
use App\Models\Lesson;
use App\Models\TestAttempt as TestAttemptModel;
use App\Services\TestGradingService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class TestAttempt extends Component
{
    use HasPageHeader;

    #[Locked]
    public CourseAssignment $assignment;

    #[Locked]
    public Lesson $lesson;

    /** @var array<int, array{selected_choice_ids?: array<int>, free_text_answer?: string}> */
    public array $responses = [];

    public function mount(CourseAssignment $assignment, Lesson $lesson): void
    {
        abort_unless($assignment->listener_id === auth()->id(), 403);
        abort_unless($lesson->courseModule->course_id === $assignment->course_id, 404);
        abort_if($assignment->isOverdue(), 403);
        abort_unless($lesson->test, 404);

        $progress = $assignment->lessonProgress()->where('lesson_id', $lesson->id)->first();
        abort_if(! $progress || $progress->status->value === 'locked', 403);

        $this->assignment = $assignment;
        $this->lesson = $lesson;

        $this->initResponses();
    }

    /**
     * Livewire's checkbox-array binding needs a real array at the nested path from
     * the start — without this, checking one "multiple" choice ends up toggling all
     * of them, since the path doesn't exist until the first write.
     */
    private function initResponses(): void
    {
        foreach ($this->lesson->test->questions as $question) {
            $this->responses[$question->id] = [
                'selected_choice_ids' => [],
                'free_text_answer' => '',
            ];
        }
    }

    private function currentAttempt(): ?TestAttemptModel
    {
        return $this->lesson->test->attempts()
            ->where('listener_id', auth()->id())
            ->latest('attempt_number')
            ->first();
    }

    public function start(TestGradingService $testGradingService): void
    {
        abort_unless($testGradingService->canStartAttempt($this->lesson->test, auth()->user()), 403);

        $testGradingService->startAttempt($this->lesson->test, auth()->user());
        $this->initResponses();
    }

    public function submit(TestGradingService $testGradingService): void
    {
        $attempt = $this->currentAttempt();
        abort_unless($attempt && $attempt->status->value === 'in_progress', 403);

        $testGradingService->submitAttempt($attempt, $this->responses);
        $this->responses = [];
    }

    public function render(): View
    {
        $attempt = $this->currentAttempt();
        $testGradingService = app(TestGradingService::class);

        return view('livewire.listener.test-attempt', [
            'attempt' => $attempt,
            'canStart' => $testGradingService->canStartAttempt($this->lesson->test, auth()->user()),
            'attemptsUsed' => $testGradingService->attemptsUsed($this->lesson->test, auth()->user()),
            'textQuestionType' => QuestionType::Text,
            'multipleQuestionType' => QuestionType::Multiple,
        ])->layout('layouts.app', ['header' => $this->pageHeader(__('Тест: :title', ['title' => $this->lesson->title]))]);
    }
}
