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
        Schema::create('course_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->timestamp('enrolled_at')->useCurrent();
            $table->timestamp('completed_at')->nullable();
            $table->enum('status', ['enrolled', 'in_progress', 'completed', 'dropped', 'suspended'])->default('enrolled');
            $table->decimal('progress_percentage', 5, 2)->default(0); // 0-100%
            $table->integer('total_study_hours')->default(0);
            $table->decimal('final_score', 5, 2)->nullable(); // Điểm cuối khóa
            $table->text('notes')->nullable(); // Ghi chú của giảng viên
            $table->json('completion_data')->nullable(); // Dữ liệu hoàn thành chi tiết
            $table->timestamps();

            // Unique constraint để tránh đăng ký trùng
            $table->unique(['course_id', 'student_id']);

            // Indexes để tối ưu performance
            $table->index(['course_id', 'status']);
            $table->index(['student_id', 'status']);
            $table->index('enrolled_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_student');
    }
};
