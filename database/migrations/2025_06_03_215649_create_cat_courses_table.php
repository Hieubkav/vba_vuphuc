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
        Schema::create('cat_courses', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Tên danh mục
            $table->string('slug')->unique(); // Slug SEO-friendly
            $table->string('seo_title')->nullable(); // Tiêu đề SEO
            $table->text('seo_description')->nullable(); // Mô tả SEO
            $table->string('og_image_link')->nullable(); // Hình ảnh OG
            $table->string('image')->nullable(); // Hình ảnh danh mục
            $table->text('description')->nullable(); // Mô tả danh mục
            $table->string('color')->default('#dc2626'); // Màu sắc đại diện
            $table->string('icon')->nullable(); // Icon đại diện
            $table->foreignId('parent_id')->nullable()->constrained('cat_courses')->nullOnDelete(); // Danh mục cha
            $table->integer('order')->default(0); // Thứ tự hiển thị
            $table->enum('status', ['active', 'inactive'])->default('active'); // Trạng thái
            $table->timestamps();

            // Indexes để tối ưu performance
            $table->index(['status', 'order']);
            $table->index(['parent_id', 'status']);
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cat_courses');
    }
};
