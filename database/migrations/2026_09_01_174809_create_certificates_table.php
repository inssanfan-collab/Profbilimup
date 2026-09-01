<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_assignment_id')->unique()->constrained()->cascadeOnDelete();
            $table->enum('type', ['certificate', 'attendance_reference']);
            $table->string('certificate_number')->unique();
            $table->string('pdf_path');
            $table->string('qr_token')->unique();
            $table->string('director_full_name_snapshot')->nullable();
            $table->timestamp('issued_at');
            $table->date('valid_until')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
