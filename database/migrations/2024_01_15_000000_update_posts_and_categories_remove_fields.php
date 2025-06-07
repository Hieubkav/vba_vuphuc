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
        // Cập nhật bảng cat_posts - bỏ image và parent_id
        Schema::table('cat_posts', function (Blueprint $table) {
            // Drop foreign key constraint trước
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['image', 'parent_id']);
        });

        // Cập nhật bảng posts - bỏ type và is_featured
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['type', 'is_featured']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Khôi phục bảng cat_posts
        Schema::table('cat_posts', function (Blueprint $table) {
            $table->string('image')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('cat_posts')->nullOnDelete();
        });

        // Khôi phục bảng posts
        Schema::table('posts', function (Blueprint $table) {
            $table->enum('type', ['normal', 'news', 'service', 'course'])->default('normal');
            $table->boolean('is_featured')->default(false);
        });
    }
};
