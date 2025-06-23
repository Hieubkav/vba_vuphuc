<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            // Kiểm tra và thêm các trường chưa tồn tại
            if (!Schema::hasColumn('menu_items', 'cat_course_id')) {
                $table->foreignId('cat_course_id')->nullable()->constrained('cat_courses')->nullOnDelete();
            }
            if (!Schema::hasColumn('menu_items', 'course_group_id')) {
                $table->foreignId('course_group_id')->nullable()->constrained('course_groups')->nullOnDelete();
            }
        });

        // Cập nhật enum type để thêm các loại menu mới
        DB::statement("ALTER TABLE menu_items MODIFY COLUMN type ENUM('link', 'cat_post', 'all_posts', 'post', 'cat_product', 'all_products', 'product', 'cat_course', 'all_courses', 'course', 'course_group', 'display_only') NOT NULL DEFAULT 'link'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            if (Schema::hasColumn('menu_items', 'cat_course_id')) {
                $table->dropForeign(['cat_course_id']);
                $table->dropColumn('cat_course_id');
            }
            if (Schema::hasColumn('menu_items', 'course_group_id')) {
                $table->dropForeign(['course_group_id']);
                $table->dropColumn('course_group_id');
            }
        });

        // Rollback enum
        DB::statement("ALTER TABLE menu_items MODIFY COLUMN type ENUM('link', 'cat_post', 'all_posts', 'post', 'cat_product', 'all_products', 'product', 'display_only') NOT NULL DEFAULT 'link'");
    }
};
