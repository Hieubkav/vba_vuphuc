<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            // Thêm instructor_id
            $table->foreignId('instructor_id')->nullable()->after('cat_course_id')->constrained('instructors')->onDelete('set null');

            // Xóa các trường instructor cũ
            $table->dropColumn(['instructor_name', 'instructor_bio']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            // Khôi phục các trường instructor cũ
            $table->string('instructor_name')->nullable();
            $table->text('instructor_bio')->nullable();

            // Xóa instructor_id
            $table->dropForeign(['instructor_id']);
            $table->dropColumn('instructor_id');
        });
    }
};
