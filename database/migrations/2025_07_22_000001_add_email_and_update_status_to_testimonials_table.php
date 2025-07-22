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
        Schema::table('testimonials', function (Blueprint $table) {
            // Thêm trường email để lưu email khách hàng gửi feedback
            $table->string('email')->nullable()->after('name');
            
            // Cập nhật enum status để thêm trạng thái 'pending' cho feedback chưa được duyệt
            $table->dropColumn('status');
        });
        
        // Thêm lại cột status với enum mới
        Schema::table('testimonials', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive', 'pending'])->default('pending')->after('order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            // Xóa trường email
            $table->dropColumn('email');
            
            // Khôi phục enum status cũ
            $table->dropColumn('status');
        });
        
        Schema::table('testimonials', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('active')->after('order');
        });
    }
};
