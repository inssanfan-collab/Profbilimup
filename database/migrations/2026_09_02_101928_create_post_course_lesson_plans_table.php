<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_course_lesson_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_course_plan_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('content')->nullable();
            $table->text('feedback_text')->nullable();
            $table->foreignId('feedback_by')->nullable()->constrained('users');
            $table->timestamp('feedback_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_course_lesson_plans');
    }
};
