<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Курс раньше выдавал только один документ на назначение (сертификат либо справка
     * о прослушивании). Теперь по завершении посткурсового сопровождения добавляется
     * ещё и справка о его прохождении — значит на одно назначение может быть несколько
     * документов, но не более одного каждого типа.
     */
    public function up(): void
    {
        Schema::table('certificates', function ($table) {
            $table->dropUnique('certificates_course_assignment_id_unique');
        });

        DB::statement('ALTER TABLE certificates DROP CONSTRAINT IF EXISTS certificates_type_check');
        DB::statement("ALTER TABLE certificates ADD CONSTRAINT certificates_type_check CHECK (type IN ('certificate', 'attendance_reference', 'post_course_reference'))");

        Schema::table('certificates', function ($table) {
            $table->unique(['course_assignment_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::table('certificates', function ($table) {
            $table->dropUnique(['course_assignment_id', 'type']);
        });

        DB::statement('ALTER TABLE certificates DROP CONSTRAINT IF EXISTS certificates_type_check');
        DB::statement("ALTER TABLE certificates ADD CONSTRAINT certificates_type_check CHECK (type IN ('certificate', 'attendance_reference'))");

        Schema::table('certificates', function ($table) {
            $table->unique('course_assignment_id');
        });
    }
};
