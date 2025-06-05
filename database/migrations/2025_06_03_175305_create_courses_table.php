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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('og_image_link')->nullable();
            $table->string('slug')->unique();
            $table->string('thumbnail')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('compare_price', 10, 2)->nullable();
            $table->integer('duration_hours')->default(0); // Thời lượng khóa học (giờ)
            $table->enum('level', ['beginner', 'intermediate', 'advanced'])->default('beginner');
            $table->enum('status', ['active', 'inactive', 'draft'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->integer('order')->default(0);
            $table->integer('max_students')->nullable(); // Giới hạn số học viên
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->json('requirements')->nullable(); // Yêu cầu đầu vào
            $table->json('what_you_learn')->nullable(); // Những gì học được
            $table->string('instructor_name')->nullable();
            $table->text('instructor_bio')->nullable();
            $table->string('gg_form')->nullable(); // Link Google Form đăng ký khóa học
            $table->string('group_link')->nullable(); // Link nhóm Zalo/Facebook khóa học
            $table->boolean('show_form_link')->default(true); // Hiển thị link form đăng ký
            $table->boolean('show_group_link')->default(true); // Hiển thị link nhóm
            $table->foreignId('category_id')->nullable()->constrained('cat_posts')->nullOnDelete();
            $table->timestamps();

            // Indexes để tối ưu performance
            $table->index(['status', 'is_featured', 'order']);
            $table->index(['category_id', 'status']);
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
