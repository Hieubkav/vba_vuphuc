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
        Schema::table('web_designs', function (Blueprint $table) {
            // Thêm các trường CTA
            $table->string('homepage_cta_title')->default('Bắt đầu hành trình với VBA Vũ Phúc')->after('homepage_cta_order');
            $table->text('homepage_cta_description')->default('Khám phá các khóa học VBA chất lượng cao và chuyên sâu. Học tập hiệu quả, hỗ trợ tận tâm từ giảng viên.')->after('homepage_cta_title');
            $table->string('homepage_cta_primary_button_text')->default('Xem khóa học')->after('homepage_cta_description');
            $table->string('homepage_cta_primary_button_url')->default('/courses')->after('homepage_cta_primary_button_text');
            $table->string('homepage_cta_secondary_button_text')->default('Đăng ký học')->after('homepage_cta_primary_button_url');
            $table->string('homepage_cta_secondary_button_url')->default('/students/register')->after('homepage_cta_secondary_button_text');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('web_designs', function (Blueprint $table) {
            // Xóa các trường CTA
            $table->dropColumn([
                'homepage_cta_title',
                'homepage_cta_description',
                'homepage_cta_primary_button_text',
                'homepage_cta_primary_button_url',
                'homepage_cta_secondary_button_text',
                'homepage_cta_secondary_button_url'
            ]);
        });
    }
};
