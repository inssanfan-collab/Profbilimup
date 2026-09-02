<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_course_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_assignment_id')->constrained()->cascadeOnDelete();
            $table->text('content');
            $table->text('diagnostic_before')->nullable();
            $table->text('diagnostic_after')->nullable();
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_course_reports');
    }
};
