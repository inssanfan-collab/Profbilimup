<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_course_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_assignment_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['methodological_event', 'conference', 'seminar', 'other']);
            $table->string('title');
            $table->date('event_date');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_course_events');
    }
};
