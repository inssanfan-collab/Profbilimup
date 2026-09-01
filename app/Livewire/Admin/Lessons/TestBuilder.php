<?php

namespace App\Livewire\Admin\Lessons;

use App\Enums\QuestionType;
use App\Livewire\Concerns\HasPageHeader;
use App\Models\Lesson;
use App\Models\Question;
use App\Models\QuestionChoice;
use App\Models\Test;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class TestBuilder extends Component
{
    use HasPageHeader;

    #[Locked]
    public Lesson $lesson;

    public ?int $time_limit_minutes = null;

    public ?int $max_attempts = null;

    public int $passing_score_percent = 70;

    public string $newQuestionType = 'single';

    public string $newQuestionText = '';

    public int $newQuestionPoints = 1;

    public array $newChoiceText = [];

    public array $newChoiceIsCorrect = [];

    public function mount(Lesson $lesson): void
    {
        $this->lesson = $lesson;

        if ($test = $lesson->test) {
            $this->time_limit_minutes = $test->time_limit_minutes;
            $this->max_attempts = $test->max_attempts;
            $this->passing_score_percent = $test->passing_score_percent;
        }
    }

    public function createTest(): void
    {
        if ($this->lesson->test) {
            return;
        }

        Test::create([
            'lesson_id' => $this->lesson->id,
            'title' => $this->lesson->title,
            'passing_score_percent' => $this->passing_score_percent,
        ]);
    }

    public function saveSettings(): void
    {
        $validated = $this->validate([
            'time_limit_minutes' => ['nullable', 'integer', 'min:1', 'max:600'],
            'max_attempts' => ['nullable', 'integer', 'min:1', 'max:100'],
            'passing_score_percent' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        $this->lesson->test?->update($validated);
    }

    public function addQuestion(): void
    {
        $validated = $this->validate([
            'newQuestionType' => ['required', 'in:single,multiple,text'],
            'newQuestionText' => ['required', 'string', 'max:2000'],
            'newQuestionPoints' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        $nextOrder = ((int) $this->lesson->test->questions()->max('order')) + 1;

        $this->lesson->test->questions()->create([
            'type' => $validated['newQuestionType'],
            'question_text' => $validated['newQuestionText'],
            'points' => $validated['newQuestionPoints'],
            'order' => $nextOrder,
        ]);

        $this->newQuestionText = '';
        $this->newQuestionPoints = 1;
    }

    public function deleteQuestion(Question $question): void
    {
        abort_unless($question->test_id === $this->lesson->test?->id, 403);

        $question->delete();
    }

    public function addChoice(Question $question): void
    {
        abort_unless($question->test_id === $this->lesson->test?->id, 403);

        $text = trim($this->newChoiceText[$question->id] ?? '');
        $isCorrect = (bool) ($this->newChoiceIsCorrect[$question->id] ?? false);

        if ($text === '') {
            return;
        }

        if ($isCorrect && $question->type === QuestionType::Single) {
            $question->choices()->update(['is_correct' => false]);
        }

        $nextOrder = ((int) $question->choices()->max('order')) + 1;

        $question->choices()->create([
            'choice_text' => $text,
            'is_correct' => $isCorrect,
            'order' => $nextOrder,
        ]);

        $this->newChoiceText[$question->id] = '';
        $this->newChoiceIsCorrect[$question->id] = false;
    }

    public function deleteChoice(QuestionChoice $choice): void
    {
        abort_unless($choice->question->test_id === $this->lesson->test?->id, 403);

        $choice->delete();
    }

    public function render(): View
    {
        $questions = $this->lesson->test?->questions()->with('choices')->get() ?? collect();

        return view('livewire.admin.lessons.test-builder', ['questions' => $questions])
            ->layout('layouts.app', ['header' => $this->pageHeader(__('Тест урока: :title', ['title' => $this->lesson->title]))]);
    }
}
