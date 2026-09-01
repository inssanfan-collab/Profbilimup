<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->unsignedInteger('time_limit_minutes')->nullable();
            $table->unsignedInteger('max_attempts')->nullable();
            $table->unsignedTinyInteger('passing_score_percent')->default(70);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tests');
    }
};
