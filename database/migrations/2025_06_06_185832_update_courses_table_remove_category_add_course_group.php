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
            // Bỏ cột category_id (liên kết với CatPost)
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');

            // Thêm cột course_group_id (liên kết với CourseGroup)
            $table->foreignId('course_group_id')->nullable()->constrained('course_groups')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            // Khôi phục cột category_id
            $table->foreignId('category_id')->nullable()->constrained('cat_posts')->onDelete('set null');

            // Bỏ cột course_group_id
            $table->dropForeign(['course_group_id']);
            $table->dropColumn('course_group_id');
        });
    }
};
