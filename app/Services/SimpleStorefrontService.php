<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\{Slider, Course, Post, Partner, CatCourse, Testimonial, Faq, Album, CourseGroup};

/**
 * Simple Storefront Service - KISS Principle
 * 
 * Chỉ 3 nhiệm vụ cốt lõi:
 * 1. Cache dữ liệu storefront (30 phút)
 * 2. Fallback UI helpers
 * 3. Performance optimization đơn giản
 */
class SimpleStorefrontService
{
    /**
     * Cache key prefix
     */
    private const CACHE_PREFIX = 'storefront_';
    private const CACHE_TTL = 1800; // 30 phút

    /**
     * Lấy tất cả dữ liệu storefront với cache
     */
    public static function getAllData(): array
    {
        return Cache::remember(self::CACHE_PREFIX . 'all_data', self::CACHE_TTL, function () {
            return [
                'sliders' => self::getSliders(),
                'courseCategories' => self::getCourseCategories(),
                'featuredCourses' => self::getFeaturedCourses(),
                'latestPosts' => self::getLatestPosts(),
                'partners' => self::getPartners(),
                'testimonials' => self::getTestimonials(),
                'faqs' => self::getFaqs(),
                'albums' => self::getAlbums(),
                'courseGroups' => self::getCourseGroups(),
            ];
        });
    }

    /**
     * Lấy sliders active
     */
    public static function getSliders()
    {
        return Cache::remember(self::CACHE_PREFIX . 'sliders', self::CACHE_TTL, function () {
            return Slider::where('status', 'active')
                ->select(['id', 'title', 'description', 'image_link', 'link', 'alt_text', 'order'])
                ->orderBy('order')
                ->get();
        });
    }

    /**
     * Lấy danh mục khóa học
     */
    public static function getCourseCategories()
    {
        return Cache::remember(self::CACHE_PREFIX . 'categories', self::CACHE_TTL, function () {
            return CatCourse::where('status', 'active')
                ->with(['courses' => function($query) {
                    $query->where('status', 'active')
                          ->select(['id', 'title', 'slug', 'thumbnail', 'description', 'level', 'price', 'cat_course_id'])
                          ->orderBy('order')
                          ->take(5);
                }])
                ->select(['id', 'name', 'slug', 'description', 'image', 'icon', 'order'])
                ->orderBy('order')
                ->get();
        });
    }

    /**
     * Lấy khóa học nổi bật
     */
    public static function getFeaturedCourses()
    {
        return Cache::remember(self::CACHE_PREFIX . 'featured_courses', self::CACHE_TTL, function () {
            return Course::where('status', 'active')
                ->where('is_featured', true)
                ->select(['id', 'title', 'slug', 'thumbnail', 'description', 'level', 'price'])
                ->orderBy('order')
                ->take(8)
                ->get();
        });
    }

    /**
     * Lấy bài viết mới nhất
     */
    public static function getLatestPosts()
    {
        return Cache::remember(self::CACHE_PREFIX . 'latest_posts', self::CACHE_TTL, function () {
            return Post::where('status', 'active')
                ->select(['id', 'title', 'slug', 'thumbnail', 'seo_description', 'created_at'])
                ->orderBy('created_at', 'desc')
                ->take(4) // Giới hạn 4 bài theo yêu cầu
                ->get();
        });
    }

    /**
     * Lấy đối tác
     */
    public static function getPartners()
    {
        return Cache::remember(self::CACHE_PREFIX . 'partners', self::CACHE_TTL, function () {
            return Partner::where('status', 'active')
                ->select(['id', 'name', 'logo_link', 'website_link', 'order'])
                ->orderBy('order')
                ->get();
        });
    }

    /**
     * Lấy testimonials
     */
    public static function getTestimonials()
    {
        return Cache::remember(self::CACHE_PREFIX . 'testimonials', self::CACHE_TTL, function () {
            return Testimonial::where('status', 'active')
                ->select(['id', 'name', 'position', 'company', 'content', 'avatar', 'rating'])
                ->orderBy('order')
                ->get();
        });
    }

    /**
     * Lấy FAQs
     */
    public static function getFaqs()
    {
        return Cache::remember(self::CACHE_PREFIX . 'faqs', self::CACHE_TTL, function () {
            return Faq::where('status', 'active')
                ->select(['id', 'question', 'answer', 'order'])
                ->orderBy('order')
                ->get();
        });
    }

    /**
     * Lấy albums
     */
    public static function getAlbums()
    {
        return Cache::remember(self::CACHE_PREFIX . 'albums', self::CACHE_TTL, function () {
            return Album::where('status', 'active')
                ->select(['id', 'title', 'description', 'pdf_file', 'thumbnail', 'view_count', 'created_at'])
                ->orderBy('created_at', 'desc')
                ->get();
        });
    }

    /**
     * Lấy nhóm khóa học
     */
    public static function getCourseGroups()
    {
        return Cache::remember(self::CACHE_PREFIX . 'course_groups', self::CACHE_TTL, function () {
            return CourseGroup::where('status', 'active')
                ->select(['id', 'name', 'description', 'group_type', 'group_link', 'current_members', 'max_members'])
                ->orderBy('order')
                ->take(6)
                ->get();
        });
    }

    /**
     * Clear tất cả cache storefront
     */
    public static function clearCache(): void
    {
        $keys = [
            'all_data', 'sliders', 'categories', 'featured_courses', 
            'latest_posts', 'partners', 'testimonials', 'faqs', 
            'albums', 'course_groups'
        ];

        foreach ($keys as $key) {
            Cache::forget(self::CACHE_PREFIX . $key);
        }
    }

    /**
     * Helper: Kiểm tra dữ liệu có tồn tại không
     */
    public static function hasData(string $key): bool
    {
        $data = self::getAllData();
        return isset($data[$key]) && !empty($data[$key]);
    }

    /**
     * Helper: Lấy fallback image
     */
    public static function getFallbackImage(string $type = 'default'): string
    {
        $fallbacks = [
            'course' => 'images/fallback/course-placeholder.jpg',
            'post' => 'images/fallback/post-placeholder.jpg',
            'partner' => 'images/fallback/partner-placeholder.jpg',
            'avatar' => 'images/fallback/avatar-placeholder.jpg',
            'default' => 'images/fallback/default-placeholder.jpg',
        ];

        return asset($fallbacks[$type] ?? $fallbacks['default']);
    }
}
