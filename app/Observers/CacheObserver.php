<?php

namespace App\Observers;

use App\Providers\ViewServiceProvider;

/**
 * Observer để clear cache ViewServiceProvider khi dữ liệu thay đổi
 * Theo nguyên tắc KISS - đơn giản và hiệu quả
 */
class CacheObserver
{
    /**
     * Clear cache khi tạo mới
     */
    public function created($model)
    {
        $this->clearRelevantCache($model);
    }

    /**
     * Clear cache khi cập nhật
     */
    public function updated($model)
    {
        $this->clearRelevantCache($model);
    }

    /**
     * Clear cache khi xóa
     */
    public function deleted($model)
    {
        $this->clearRelevantCache($model);
    }

    /**
     * Clear cache dựa trên model type
     */
    private function clearRelevantCache($model)
    {
        $modelClass = get_class($model);
        
        switch ($modelClass) {
            case 'App\Models\Course':
                ViewServiceProvider::refreshCache('storefront');
                ViewServiceProvider::refreshCache('courses');
                break;
                
            case 'App\Models\CatCourse':
                ViewServiceProvider::refreshCache('storefront');
                ViewServiceProvider::refreshCache('cat_courses');
                break;
                
            case 'App\Models\Post':
                ViewServiceProvider::refreshCache('storefront');
                ViewServiceProvider::refreshCache('posts');
                break;
                
            case 'App\Models\CatPost':
                ViewServiceProvider::refreshCache('posts');
                break;
                
            case 'App\Models\Slider':
                ViewServiceProvider::refreshCache('storefront');
                ViewServiceProvider::refreshCache('sliders');
                break;
                
            case 'App\Models\Setting':
                ViewServiceProvider::refreshCache('settings');
                break;
                
            case 'App\Models\WebDesign':
                ViewServiceProvider::refreshCache('webdesign');
                break;
                
            case 'App\Models\Testimonial':
                ViewServiceProvider::refreshCache('storefront');
                ViewServiceProvider::refreshCache('testimonials');
                break;

            case 'App\Models\Faq':
                ViewServiceProvider::refreshCache('storefront');
                ViewServiceProvider::refreshCache('faqs');
                break;

            case 'App\Models\MenuItem':
                ViewServiceProvider::refreshCache('navigation');
                break;
                
            case 'App\Models\CourseGroup':
                // Clear course groups cache specifically and storefront cache
                ViewServiceProvider::refreshCache('storefront');
                ViewServiceProvider::refreshCache('course_groups');
                break;

            case 'App\Models\Album':
                // Clear albums cache specifically and storefront cache
                ViewServiceProvider::refreshCache('storefront');
                ViewServiceProvider::refreshCache('albums');
                break;

            case 'App\Models\Partner':
                ViewServiceProvider::refreshCache('storefront');
                ViewServiceProvider::refreshCache('partners');
                break;

            case 'App\Models\Instructor':
                ViewServiceProvider::refreshCache('storefront');
                ViewServiceProvider::refreshCache('instructors');
                break;

            case 'App\Models\Association':
            case 'App\Models\Student':
                ViewServiceProvider::refreshCache('storefront');
                ViewServiceProvider::refreshCache('students');
                break;
                
            default:
                // Clear all cache cho các model khác
                ViewServiceProvider::clearCache();
                break;
        }
    }
}
