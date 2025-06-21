<?php

namespace App\Services;

class StorefrontSectionService
{
    /**
     * Get default sections configuration
     */
    public static function getDefaultSections(): array
    {
        return [
            'hero_banner' => [
                'enabled' => true,
                'order' => 1,
                'component' => 'components.storefront.hero-banner',
                'type' => 'include'
            ],
            'courses_overview' => [
                'enabled' => true,
                'order' => 2,
                'title' => 'Khóa học VBA Excel chuyên nghiệp',
                'description' => 'Nâng cao kỹ năng Excel với các khóa học VBA từ cơ bản đến nâng cao',
                'bg_color' => 'bg-white',
                'animation_class' => 'animate-fade-in-optimized',
                'component' => 'courses-overview',
                'type' => 'livewire'
            ],
            'album_timeline' => [
                'enabled' => true,
                'order' => 3,
                'title' => 'Thư viện tài liệu',
                'description' => 'Tài liệu và hình ảnh từ các khóa học đã diễn ra',
                'bg_color' => 'bg-gray-25',
                'animation_class' => 'animate-fade-in-optimized',
                'component' => 'components.storefront.album-timeline',
                'type' => 'include',
                'empty_icon' => 'fas fa-images',
                'empty_message' => 'Chưa có album nào được tải lên'
            ],
            'course_groups' => [
                'enabled' => true,
                'order' => 4,
                'title' => 'Nhóm học tập',
                'description' => 'Tham gia các nhóm Facebook/Zalo để học hỏi và trao đổi kinh nghiệm',
                'bg_color' => 'bg-white',
                'animation_class' => 'animate-fade-in-optimized',
                'component' => 'components.storefront.course-groups',
                'type' => 'include',
                'empty_icon' => 'fas fa-users',
                'empty_message' => 'Chưa có nhóm khóa học nào'
            ],
            'course_categories' => [
                'enabled' => true,
                'order' => 5,
                'title' => 'Khóa học theo chuyên mục',
                'description' => 'Khám phá các khóa học được phân loại theo từng chuyên mục',
                'bg_color' => 'bg-gray-25',
                'animation_class' => 'animate-fade-in-optimized',
                'component' => 'components.storefront.course-categories-sections',
                'type' => 'include',
                'empty_icon' => 'fas fa-graduation-cap',
                'empty_message' => 'Chưa có khóa học nào'
            ],
            'testimonials' => [
                'enabled' => true,
                'order' => 6,
                'title' => 'Đánh giá từ học viên',
                'description' => 'Chia sẻ từ những học viên đã tham gia khóa học',
                'bg_color' => 'bg-white',
                'animation_class' => 'animate-fade-in-optimized',
                'component' => 'components.storefront.testimonials',
                'type' => 'include',
                'empty_icon' => 'fas fa-star',
                'empty_message' => 'Chưa có đánh giá nào'
            ],
            'faq' => [
                'enabled' => true,
                'order' => 7,
                'title' => 'Câu hỏi thường gặp',
                'description' => 'Giải đáp những thắc mắc phổ biến về khóa học',
                'bg_color' => 'bg-gray-25',
                'animation_class' => 'animate-fade-in-optimized',
                'component' => 'components.storefront.faq-section',
                'type' => 'include',
                'empty_icon' => 'fas fa-question-circle',
                'empty_message' => 'Chưa có câu hỏi nào'
            ],
            'partners' => [
                'enabled' => true,
                'order' => 8,
                'title' => 'Đối tác tin cậy',
                'description' => 'Những đối tác đồng hành cùng chúng tôi',
                'bg_color' => 'bg-white',
                'animation_class' => 'animate-fade-in-optimized',
                'component' => 'components.storefront.partners',
                'type' => 'include',
                'empty_icon' => 'fas fa-handshake',
                'empty_message' => 'Chưa có đối tác nào'
            ],
            'blog_posts' => [
                'enabled' => true,
                'order' => 9,
                'title' => 'Bài viết mới nhất',
                'description' => 'Cập nhật kiến thức và thông tin hữu ích',
                'bg_color' => 'bg-gray-25',
                'animation_class' => 'animate-fade-in-optimized',
                'component' => 'components.storefront.blog-posts',
                'type' => 'include',
                'empty_icon' => 'fas fa-newspaper',
                'empty_message' => 'Chưa có bài viết nào'
            ],
            'homepage_cta' => [
                'enabled' => true,
                'order' => 10,
                'title' => 'Call to Action',
                'bg_color' => 'bg-gradient-to-r from-red-700 via-red-600 to-red-700',
                'component' => 'components.storefront.homepage-cta',
                'type' => 'include'
            ]
        ];
    }

    /**
     * Get sections with fallback
     */
    public static function getSections($webDesign): array
    {
        return $webDesign ? $webDesign->getOrderedSections() : self::getDefaultSections();
    }

    /**
     * Check if section has data
     */
    public static function hasData(string $sectionKey, array $data = []): bool
    {
        return match($sectionKey) {
            'album_timeline' => isset($data['albums']) && $data['albums']->isNotEmpty(),
            'course_groups' => isset($data['courseGroups']) && $data['courseGroups']->isNotEmpty(),
            'course_categories' => isset($data['courseCategories']) && $data['courseCategories']->isNotEmpty(),
            'testimonials' => isset($data['testimonials']) && $data['testimonials']->isNotEmpty(),
            'faq' => isset($data['faqs']) && $data['faqs']->isNotEmpty(),
            'partners' => isset($data['partners']) && $data['partners']->isNotEmpty(),
            'blog_posts' => isset($data['latestPosts']) && $data['latestPosts']->isNotEmpty(),
            default => true
        };
    }
}
