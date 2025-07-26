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
            // Thêm trường edited_content để admin có thể biên tập nội dung hiển thị
            $table->text('edited_content')->nullable()->after('content')
                ->comment('Nội dung đã được biên tập bởi admin để hiển thị trên website');

            // Loại bỏ các trường không cần thiết theo yêu cầu đơn giản hóa
            $table->dropColumn(['position', 'company']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            // Khôi phục lại các trường đã xóa
            $table->string('position')->nullable()->after('email');
            $table->string('company')->nullable()->after('position');

            // Xóa trường edited_content
            $table->dropColumn('edited_content');
        });
    }
};
