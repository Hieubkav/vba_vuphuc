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
        Schema::create('course_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path');
            $table->string('file_name');
            $table->string('file_type'); // pdf, ppt, doc, video, etc.
            $table->bigInteger('file_size')->default(0); // bytes
            $table->enum('material_type', ['document', 'video', 'audio', 'image', 'other'])->default('document');
            $table->boolean('is_downloadable')->default(true);
            $table->boolean('is_preview')->default(false); // Có thể xem trước không cần đăng ký
            $table->integer('order')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            // Indexes để tối ưu performance
            $table->index(['course_id', 'status', 'order']);
            $table->index(['material_type', 'status']);
            $table->index('is_preview');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_materials');
    }
};
