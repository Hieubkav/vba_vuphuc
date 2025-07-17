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
        Schema::table('course_materials', function (Blueprint $table) {
            // Thêm giá trị mặc định cho file_type để tránh lỗi khi insert
            $table->string('file_type')->default('application/octet-stream')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_materials', function (Blueprint $table) {
            // Khôi phục lại trạng thái ban đầu (không có default)
            $table->string('file_type')->change();
        });
    }
};
