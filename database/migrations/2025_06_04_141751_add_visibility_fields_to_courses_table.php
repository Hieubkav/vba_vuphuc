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
        Schema::table('courses', function (Blueprint $table) {
            // Thêm trường ẩn/hiện giảng viên
            $table->boolean('show_instructor')
                  ->default(true)
                  ->after('instructor_id')
                  ->comment('Ẩn/hiện thông tin giảng viên trong giao diện');

            // Thêm trường ẩn/hiện giá
            $table->boolean('show_price')
                  ->default(true)
                  ->after('compare_price')
                  ->comment('Ẩn/hiện giá khóa học trong giao diện');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['show_instructor', 'show_price']);
        });
    }
};
