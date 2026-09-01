<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('test_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_attempt_id')->constrained()->cascadeOnDelete();
            $table->foreignId('question_id')->constrained()->cascadeOnDelete();
            $table->json('selected_choice_ids')->nullable();
            $table->text('free_text_answer')->nullable();
            $table->boolean('is_correct')->nullable();
            $table->unsignedInteger('points_awarded')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->unique(['test_attempt_id', 'question_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('test_answers');
    }
};
