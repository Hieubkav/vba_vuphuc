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
        Schema::create('web_designs', function (Blueprint $table) {
            $table->id();
            
            // Hero Banner Settings
            $table->boolean('hero_banner_enabled')->default(true);
            $table->integer('hero_banner_order')->default(1);
            
            // Courses Overview Settings
            $table->boolean('courses_overview_enabled')->default(true);
            $table->integer('courses_overview_order')->default(2);
            $table->string('courses_overview_title')->default('Khóa học VBA Excel chuyên nghiệp');
            $table->text('courses_overview_description')->default('Nâng cao kỹ năng Excel với các khóa học VBA từ cơ bản đến nâng cao');
            $table->string('courses_overview_bg_color')->default('bg-white');
            $table->string('courses_overview_animation_class')->default('animate-fade-in-optimized');
            
            // Album Timeline Settings
            $table->boolean('album_timeline_enabled')->default(true);
            $table->integer('album_timeline_order')->default(3);
            $table->string('album_timeline_title')->default('Thư viện tài liệu');
            $table->text('album_timeline_description')->default('Tài liệu và hình ảnh từ các khóa học đã diễn ra');
            $table->string('album_timeline_bg_color')->default('bg-gray-25');
            $table->string('album_timeline_animation_class')->default('animate-fade-in-optimized');
            
            // Course Groups Settings
            $table->boolean('course_groups_enabled')->default(true);
            $table->integer('course_groups_order')->default(4);
            $table->string('course_groups_title')->default('Nhóm học tập');
            $table->text('course_groups_description')->default('Tham gia các nhóm Facebook/Zalo để học hỏi và trao đổi kinh nghiệm');
            $table->string('course_groups_bg_color')->default('bg-white');
            $table->string('course_groups_animation_class')->default('animate-fade-in-optimized');
            
            // Course Categories Settings
            $table->boolean('course_categories_enabled')->default(true);
            $table->integer('course_categories_order')->default(5);
            $table->string('course_categories_title')->default('Khóa học theo chuyên mục');
            $table->text('course_categories_description')->default('Khám phá các khóa học được phân loại theo từng chuyên mục');
            $table->string('course_categories_bg_color')->default('bg-gray-25');
            $table->string('course_categories_animation_class')->default('animate-fade-in-optimized');
            
            // Testimonials Settings
            $table->boolean('testimonials_enabled')->default(true);
            $table->integer('testimonials_order')->default(6);
            $table->string('testimonials_title')->default('Đánh giá từ học viên');
            $table->text('testimonials_description')->default('Chia sẻ từ những học viên đã tham gia khóa học');
            $table->string('testimonials_bg_color')->default('bg-white');
            $table->string('testimonials_animation_class')->default('animate-fade-in-optimized');
            
            // FAQ Settings
            $table->boolean('faq_enabled')->default(true);
            $table->integer('faq_order')->default(7);
            $table->string('faq_title')->default('Câu hỏi thường gặp');
            $table->text('faq_description')->default('Giải đáp những thắc mắc phổ biến về khóa học');
            $table->string('faq_bg_color')->default('bg-gray-25');
            $table->string('faq_animation_class')->default('animate-fade-in-optimized');
            
            // Partners Settings
            $table->boolean('partners_enabled')->default(true);
            $table->integer('partners_order')->default(8);
            $table->string('partners_title')->default('Đối tác tin cậy');
            $table->text('partners_description')->default('Những đối tác đồng hành cùng chúng tôi');
            $table->string('partners_bg_color')->default('bg-white');
            $table->string('partners_animation_class')->default('animate-fade-in-optimized');
            
            // Blog Posts Settings
            $table->boolean('blog_posts_enabled')->default(true);
            $table->integer('blog_posts_order')->default(9);
            $table->string('blog_posts_title')->default('Bài viết mới nhất');
            $table->text('blog_posts_description')->default('Cập nhật kiến thức và thông tin hữu ích');
            $table->string('blog_posts_bg_color')->default('bg-gray-25');
            $table->string('blog_posts_animation_class')->default('animate-fade-in-optimized');
            
            // Homepage CTA Settings
            $table->boolean('homepage_cta_enabled')->default(true);
            $table->integer('homepage_cta_order')->default(10);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('web_designs');
    }
};
