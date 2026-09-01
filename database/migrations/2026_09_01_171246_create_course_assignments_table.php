<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('listener_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('assigned_by')->constrained('users');
            $table->date('deadline')->nullable();
            $table->enum('status', ['assigned', 'in_progress', 'completed', 'overdue'])->default('assigned');
            $table->enum('final_outcome', ['pending', 'passed', 'attendance_only'])->default('pending');
            $table->timestamp('agreement_accepted_at')->nullable();
            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamp('completed_at')->nullable();
            $table->date('retake_available_at')->nullable();
            $table->timestamps();

            $table->unique(['course_id', 'listener_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_assignments');
    }
};
