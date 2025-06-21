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
            case 'App\Models\Post':
            case 'App\Models\Partner':
            case 'App\Models\Slider':
                ViewServiceProvider::refreshCache('storefront');
                ViewServiceProvider::refreshCache('navigation');
                break;

            case 'App\Models\Course':
            case 'App\Models\CatCourse':
            case 'App\Models\CourseGroup':
            case 'App\Models\CourseMaterial':
            case 'App\Models\CourseImage':
            case 'App\Models\Instructor':
                ViewServiceProvider::refreshCache('storefront');
                ViewServiceProvider::refreshCache('navigation');
                ViewServiceProvider::clearCourseDetailCaches();
                break;

            default:
                ViewServiceProvider::refreshCache('all');
                break;
        }
    }
}
