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
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Tên khách hàng
            $table->string('position')->nullable(); // Chức vụ
            $table->string('company')->nullable(); // Công ty
            $table->string('location')->nullable(); // Địa điểm
            $table->text('content'); // Nội dung lời khen
            $table->tinyInteger('rating')->default(5); // Đánh giá 1-5 sao
            $table->string('avatar')->nullable(); // Ảnh đại diện
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
        Schema::dropIfExists('testimonials');
    }
};
