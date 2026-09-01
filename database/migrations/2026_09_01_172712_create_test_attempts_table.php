<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('test_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id')->constrained('tests')->cascadeOnDelete();
            $table->foreignId('listener_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedInteger('attempt_number');
            $table->enum('status', ['in_progress', 'submitted', 'awaiting_review', 'graded'])->default('in_progress');
            $table->boolean('passed')->nullable();
            $table->unsignedTinyInteger('score_percent')->nullable();
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('test_attempts');
    }
};
