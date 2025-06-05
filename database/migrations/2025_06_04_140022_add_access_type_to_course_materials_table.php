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
            // Thêm trường access_type để phân loại tài liệu
            $table->enum('access_type', ['public', 'enrolled'])
                  ->default('public')
                  ->after('material_type')
                  ->comment('public: tài liệu mở, enrolled: tài liệu khóa (chỉ học viên đăng ký)');

            // Thêm index để tối ưu performance
            $table->index(['access_type', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_materials', function (Blueprint $table) {
            $table->dropIndex(['access_type', 'status']);
            $table->dropColumn('access_type');
        });
    }
};
