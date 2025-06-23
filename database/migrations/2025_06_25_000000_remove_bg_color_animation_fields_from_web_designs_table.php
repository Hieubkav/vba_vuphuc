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
            // Xóa các trường màu nền và hiệu ứng không cần thiết
            $table->dropColumn([
                'courses_overview_bg_color',
                'courses_overview_animation_class',
                'course_groups_bg_color',
                'course_groups_animation_class',
                'course_categories_bg_color',
                'course_categories_animation_class',
                'testimonials_bg_color',
                'testimonials_animation_class',
                'faq_bg_color',
                'faq_animation_class',
                'partners_bg_color',
                'partners_animation_class',
                'blog_posts_bg_color',
                'blog_posts_animation_class',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('web_designs', function (Blueprint $table) {
            // Khôi phục các trường đã xóa với giá trị mặc định
            $table->string('courses_overview_bg_color')->default('bg-white')->after('courses_overview_description');
            $table->string('courses_overview_animation_class')->default('animate-fade-in-optimized')->after('courses_overview_bg_color');
            $table->string('course_groups_bg_color')->default('bg-white')->after('course_groups_description');
            $table->string('course_groups_animation_class')->default('animate-fade-in-optimized')->after('course_groups_bg_color');
            $table->string('course_categories_bg_color')->default('bg-gray-25')->after('course_categories_description');
            $table->string('course_categories_animation_class')->default('animate-fade-in-optimized')->after('course_categories_bg_color');
            $table->string('testimonials_bg_color')->default('bg-white')->after('testimonials_description');
            $table->string('testimonials_animation_class')->default('animate-fade-in-optimized')->after('testimonials_bg_color');
            $table->string('faq_bg_color')->default('bg-gray-25')->after('faq_description');
            $table->string('faq_animation_class')->default('animate-fade-in-optimized')->after('faq_bg_color');
            $table->string('partners_bg_color')->default('bg-white')->after('partners_description');
            $table->string('partners_animation_class')->default('animate-fade-in-optimized')->after('partners_bg_color');
            $table->string('blog_posts_bg_color')->default('bg-gray-25')->after('blog_posts_description');
            $table->string('blog_posts_animation_class')->default('animate-fade-in-optimized')->after('blog_posts_bg_color');
        });
    }
};
