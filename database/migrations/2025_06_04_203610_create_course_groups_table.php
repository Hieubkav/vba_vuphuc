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
        Schema::create('course_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Tên nhóm khóa học
            $table->string('slug')->unique(); // Slug SEO-friendly
            $table->text('description')->nullable(); // Mô tả nhóm
            $table->string('thumbnail')->nullable(); // Hình đại diện nhóm
            $table->string('group_link')->nullable(); // Link nhóm Facebook/Zalo
            $table->string('group_type')->default('facebook'); // facebook, zalo, telegram
            $table->string('level')->default('beginner'); // beginner, intermediate, advanced
            $table->integer('max_members')->nullable(); // Giới hạn thành viên
            $table->integer('current_members')->default(0); // Số thành viên hiện tại
            $table->string('instructor_name')->nullable(); // Tên giảng viên phụ trách
            $table->text('instructor_bio')->nullable(); // Tiểu sử giảng viên
            $table->string('color')->default('#dc2626'); // Màu sắc đại diện
            $table->string('icon')->nullable(); // Icon đại diện
            $table->boolean('is_featured')->default(false); // Nổi bật
            $table->integer('order')->default(0); // Thứ tự hiển thị
            $table->enum('status', ['active', 'inactive'])->default('active'); // Trạng thái
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_groups');
    }
};
