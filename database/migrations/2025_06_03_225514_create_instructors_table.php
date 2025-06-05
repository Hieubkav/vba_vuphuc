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
        Schema::create('instructors', function (Blueprint $table) {
            $table->id();

            // Thông tin cơ bản
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->text('bio')->nullable();
            $table->string('avatar')->nullable();

            // Thông tin chuyên môn
            $table->string('specialization')->nullable(); // Chuyên môn
            $table->integer('experience_years')->default(0); // Số năm kinh nghiệm
            $table->text('education')->nullable(); // Học vấn
            $table->json('certifications')->nullable(); // Chứng chỉ
            $table->json('social_links')->nullable(); // Liên kết mạng xã hội

            // Thông tin khác
            $table->text('achievements')->nullable(); // Thành tích
            $table->text('teaching_philosophy')->nullable(); // Triết lý giảng dạy
            $table->decimal('hourly_rate', 10, 2)->nullable(); // Giá theo giờ

            // Quản lý
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->integer('order')->default(0);

            $table->timestamps();

            // Indexes
            $table->index(['status', 'order']);
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instructors');
    }
};
