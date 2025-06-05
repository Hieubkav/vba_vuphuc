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
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question'); // Câu hỏi
            $table->text('answer'); // Câu trả lời
            $table->string('category')->default('general'); // Danh mục FAQ
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
        Schema::dropIfExists('faqs');
    }
};
