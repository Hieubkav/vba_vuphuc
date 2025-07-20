<?php

namespace App\Traits;

use App\Providers\ViewServiceProvider;

trait ClearsViewCache
{
    /**
     * Clear view cache after model events
     */
    public static function bootClearsViewCache()
    {
        // Clear cache khi tạo mới
        static::created(function ($model) {
            static::clearRelatedCache($model);
        });

        // Clear cache khi cập nhật
        static::updated(function ($model) {
            static::clearRelatedCache($model);
        });

        // Clear cache khi xóa
        static::deleted(function ($model) {
            static::clearRelatedCache($model);
        });
    }

    /**
     * Clear cache dựa trên model type
     */
    protected static function clearRelatedCache($model)
    {
        $modelClass = get_class($model);

        switch ($modelClass) {
            case 'App\Models\Setting':
                ViewServiceProvider::refreshCache('settings');
                break;

            case 'App\Models\MenuItem':
            case 'App\Models\Association':
                ViewServiceProvider::refreshCache('navigation');
                break;

            case 'App\Models\Product':
            case 'App\Models\CatProduct':
                ViewServiceProvider::refreshCache('storefront');
                ViewServiceProvider::refreshCache('navigation');
                break;

            case 'App\Models\Post':
                ViewServiceProvider::refreshCache('storefront');
                ViewServiceProvider::refreshCache('navigation');
                ViewServiceProvider::refreshCache('posts');
                break;

            case 'App\Models\Partner':
                ViewServiceProvider::refreshCache('storefront');
                ViewServiceProvider::refreshCache('partners');
                break;

            case 'App\Models\Course':
                ViewServiceProvider::refreshCache('storefront');
                ViewServiceProvider::refreshCache('navigation');
                ViewServiceProvider::refreshCache('courses');
                ViewServiceProvider::clearCourseDetailCaches();
                break;

            case 'App\Models\CatCourse':
                ViewServiceProvider::refreshCache('storefront');
                ViewServiceProvider::refreshCache('navigation');
                ViewServiceProvider::refreshCache('cat_courses');
                ViewServiceProvider::clearCourseDetailCaches();
                break;

            case 'App\Models\Instructor':
                ViewServiceProvider::refreshCache('storefront');
                ViewServiceProvider::refreshCache('navigation');
                ViewServiceProvider::refreshCache('instructors');
                ViewServiceProvider::clearCourseDetailCaches();
                break;

            case 'App\Models\CourseMaterial':
                ViewServiceProvider::refreshCache('storefront');
                ViewServiceProvider::refreshCache('navigation');
                ViewServiceProvider::refreshCache('course_materials');
                ViewServiceProvider::clearCourseDetailCaches();
                break;

            case 'App\Models\CourseImage':
                ViewServiceProvider::refreshCache('storefront');
                ViewServiceProvider::refreshCache('navigation');
                ViewServiceProvider::clearCourseDetailCaches();
                break;

            case 'App\Models\CourseGroup':
                ViewServiceProvider::refreshCache('storefront');
                ViewServiceProvider::refreshCache('navigation');
                ViewServiceProvider::refreshCache('course_groups');
                break;

            case 'App\Models\Album':
                ViewServiceProvider::refreshCache('storefront');
                ViewServiceProvider::refreshCache('albums');
                ViewServiceProvider::clearAlbumsCache();
                break;

            case 'App\Models\Testimonial':
                ViewServiceProvider::refreshCache('storefront');
                ViewServiceProvider::refreshCache('testimonials');
                break;

            case 'App\Models\Slider':
                ViewServiceProvider::refreshCache('storefront');
                ViewServiceProvider::refreshCache('sliders');
                break;

            case 'App\Models\Faq':
                ViewServiceProvider::refreshCache('storefront');
                ViewServiceProvider::refreshCache('faqs');
                break;

            default:
                ViewServiceProvider::refreshCache('all');
                break;
        }
    }
}
