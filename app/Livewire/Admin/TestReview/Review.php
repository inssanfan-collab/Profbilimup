<?php

namespace App\Livewire\Admin\TestReview;

use App\Enums\QuestionType;
use App\Livewire\Concerns\HasPageHeader;
use App\Models\TestAnswer;
use App\Models\TestAttempt;
use App\Services\TestGradingService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Review extends Component
{
    use HasPageHeader;

    #[Locked]
    public TestAttempt $attempt;

    public array $points = [];

    public function mount(TestAttempt $attempt): void
    {
        $this->attempt = $attempt;

        foreach ($attempt->answers()->whereNull('points_awarded')->get() as $answer) {
            $this->points[$answer->id] = 0;
        }
    }

    public function gradeAnswer(TestAnswer $answer, TestGradingService $testGradingService): void
    {
        abort_unless($answer->test_attempt_id === $this->attempt->id, 403);

        $this->validate([
            'points.'.$answer->id => ['required', 'integer', 'min:0', 'max:'.$answer->question->points],
        ]);

        $testGradingService->gradeTextAnswer($answer, (int) $this->points[$answer->id], auth()->user());

        $this->attempt->refresh();
    }

    public function render(): View
    {
        $answers = $this->attempt->answers()->with('question')->get();

        return view('livewire.admin.test-review.review', [
            'answers' => $answers,
            'textQuestionType' => QuestionType::Text,
        ])->layout('layouts.app', ['header' => $this->pageHeader(__('Проверка попытки'))]);
    }
}
