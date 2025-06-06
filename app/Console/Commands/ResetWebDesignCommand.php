<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\WebDesign;
use Illuminate\Support\Facades\Cache;

class ResetWebDesignCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'webdesign:reset {--force : Force reset without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset WebDesign settings to default values';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->option('force')) {
            if (!$this->confirm('Bạn có chắc chắn muốn reset WebDesign về mặc định?')) {
                $this->info('Hủy bỏ reset WebDesign.');
                return 0;
            }
        }

        $this->info('Đang reset WebDesign settings...');

        // Delete existing record
        WebDesign::truncate();

        // Create default record
        $webDesign = WebDesign::create([
            // Hero Banner Settings
            'hero_banner_enabled' => true,
            'hero_banner_order' => 1,
            
            // Courses Overview Settings
            'courses_overview_enabled' => true,
            'courses_overview_order' => 2,
            'courses_overview_title' => 'Khóa học VBA Excel chuyên nghiệp',
            'courses_overview_description' => 'Nâng cao kỹ năng Excel với các khóa học VBA từ cơ bản đến nâng cao',
            'courses_overview_bg_color' => 'bg-white',
            'courses_overview_animation_class' => 'animate-fade-in-optimized',
            
            // Album Timeline Settings
            'album_timeline_enabled' => true,
            'album_timeline_order' => 3,
            'album_timeline_title' => 'Thư viện tài liệu',
            'album_timeline_description' => 'Tài liệu và hình ảnh từ các khóa học đã diễn ra',
            'album_timeline_bg_color' => 'bg-gray-25',
            'album_timeline_animation_class' => 'animate-fade-in-optimized',
            
            // Course Groups Settings
            'course_groups_enabled' => true,
            'course_groups_order' => 4,
            'course_groups_title' => 'Nhóm học tập',
            'course_groups_description' => 'Tham gia các nhóm Facebook/Zalo để học hỏi và trao đổi kinh nghiệm',
            'course_groups_bg_color' => 'bg-white',
            'course_groups_animation_class' => 'animate-fade-in-optimized',
            
            // Course Categories Settings
            'course_categories_enabled' => true,
            'course_categories_order' => 5,
            'course_categories_title' => 'Khóa học theo chuyên mục',
            'course_categories_description' => 'Khám phá các khóa học được phân loại theo từng chuyên mục',
            'course_categories_bg_color' => 'bg-gray-25',
            'course_categories_animation_class' => 'animate-fade-in-optimized',
            
            // Testimonials Settings
            'testimonials_enabled' => true,
            'testimonials_order' => 6,
            'testimonials_title' => 'Đánh giá từ học viên',
            'testimonials_description' => 'Chia sẻ từ những học viên đã tham gia khóa học',
            'testimonials_bg_color' => 'bg-white',
            'testimonials_animation_class' => 'animate-fade-in-optimized',
            
            // FAQ Settings
            'faq_enabled' => true,
            'faq_order' => 7,
            'faq_title' => 'Câu hỏi thường gặp',
            'faq_description' => 'Giải đáp những thắc mắc phổ biến về khóa học',
            'faq_bg_color' => 'bg-gray-25',
            'faq_animation_class' => 'animate-fade-in-optimized',
            
            // Partners Settings
            'partners_enabled' => true,
            'partners_order' => 8,
            'partners_title' => 'Đối tác tin cậy',
            'partners_description' => 'Những đối tác đồng hành cùng chúng tôi',
            'partners_bg_color' => 'bg-white',
            'partners_animation_class' => 'animate-fade-in-optimized',
            
            // Blog Posts Settings
            'blog_posts_enabled' => true,
            'blog_posts_order' => 9,
            'blog_posts_title' => 'Bài viết mới nhất',
            'blog_posts_description' => 'Cập nhật kiến thức và thông tin hữu ích',
            'blog_posts_bg_color' => 'bg-gray-25',
            'blog_posts_animation_class' => 'animate-fade-in-optimized',
            
            // Homepage CTA Settings
            'homepage_cta_enabled' => true,
            'homepage_cta_order' => 10,
        ]);

        // Clear cache
        Cache::forget('web_design_settings');
        \App\Providers\ViewServiceProvider::refreshCache('webdesign');

        $this->info('✅ WebDesign đã được reset về mặc định thành công!');
        $this->line('📊 Thống kê:');
        $this->line('   - Tổng sections: 10');
        $this->line('   - Sections enabled: 10');
        $this->line('   - Cache đã được clear');
        
        return 0;
    }
}
