<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Lessons\TestBuilder;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TestBuilderTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_a_test_and_add_questions_with_choices(): void
    {
        $admin = User::factory()->admin()->create();
        $course = Course::factory()->create(['created_by' => $admin->id]);
        $module = CourseModule::factory()->for($course)->create();
        $lesson = Lesson::factory()->for($module, 'courseModule')->create();

        $component = Livewire::actingAs($admin)
            ->test(TestBuilder::class, ['lesson' => $lesson])
            ->call('createTest')
            ->assertHasNoErrors();

        $lesson->refresh();
        $this->assertNotNull($lesson->test);

        $component
            ->set('newQuestionType', 'single')
            ->set('newQuestionText', 'Сколько будет 2+2?')
            ->set('newQuestionPoints', 2)
            ->call('addQuestion')
            ->assertHasNoErrors();

        $question = $lesson->test->questions()->firstOrFail();
        $this->assertSame('Сколько будет 2+2?', $question->question_text);

        $component
            ->set("newChoiceText.{$question->id}", '4')
            ->set("newChoiceIsCorrect.{$question->id}", true)
            ->call('addChoice', $question->id);

        $this->assertSame(1, $question->choices()->where('is_correct', true)->count());
    }

    public function test_marking_a_new_choice_correct_on_a_single_question_unmarks_previous_ones(): void
    {
        $admin = User::factory()->admin()->create();
        $course = Course::factory()->create(['created_by' => $admin->id]);
        $module = CourseModule::factory()->for($course)->create();
        $lesson = Lesson::factory()->for($module, 'courseModule')->create();
        $lesson->test()->create(['title' => 'T', 'passing_score_percent' => 70]);
        $question = $lesson->test->questions()->create(['type' => 'single', 'question_text' => 'Q', 'order' => 1, 'points' => 1]);
        $question->choices()->create(['choice_text' => 'A', 'is_correct' => true, 'order' => 1]);

        Livewire::actingAs($admin)
            ->test(TestBuilder::class, ['lesson' => $lesson])
            ->set("newChoiceText.{$question->id}", 'B')
            ->set("newChoiceIsCorrect.{$question->id}", true)
            ->call('addChoice', $question->id);

        $this->assertSame(1, $question->choices()->where('is_correct', true)->count());
        $this->assertSame('B', $question->choices()->where('is_correct', true)->first()->choice_text);
    }
}
