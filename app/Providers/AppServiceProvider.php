<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;
use App\Models\Instructor;
use App\Models\Testimonial;
use App\Models\CatCourse;
use App\Models\Partner;
use App\Models\Post;
use App\Models\CatPost;
use App\Observers\InstructorObserver;
use App\Observers\TestimonialObserver;
use App\Observers\CatCourseObserver;
use App\Observers\PartnerObserver;
use App\Observers\PostObserver;
use App\Observers\CatPostObserver;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Load helper functions
        if (!function_exists('simpleLazyImage')) {
            require_once app_path('Helpers/PerformanceHelper.php');
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Đăng ký Observer
        Instructor::observe(InstructorObserver::class);
        Testimonial::observe(TestimonialObserver::class);
        CatCourse::observe(CatCourseObserver::class);
        Partner::observe(PartnerObserver::class);
        Post::observe(PostObserver::class);
        CatPost::observe(CatPostObserver::class);

        // Cache Observer để clear cache ViewServiceProvider
        \App\Models\Course::observe(\App\Observers\CacheObserver::class);
        CatCourse::observe(\App\Observers\CacheObserver::class);
        \App\Models\Post::observe(\App\Observers\CacheObserver::class);
        \App\Models\CatPost::observe(\App\Observers\CacheObserver::class);
        \App\Models\Slider::observe(\App\Observers\CacheObserver::class);
        \App\Models\Setting::observe(\App\Observers\CacheObserver::class);
        \App\Models\WebDesign::observe(\App\Observers\CacheObserver::class);
        Testimonial::observe(\App\Observers\CacheObserver::class);
        \App\Models\MenuItem::observe(\App\Observers\CacheObserver::class);
        \App\Models\Student::observe(\App\Observers\CacheObserver::class);
        // Partner sử dụng PartnerObserver riêng (đã có logic clear cache)

        // Đăng ký Blade directive đơn giản cho lazy loading
        Blade::directive('simpleLazyImage', function ($expression) {
            return "<?php echo simpleLazyImage({$expression}); ?>";
        });

    //    Livewire::setScriptRoute(function ($handle) {
    //         return Route::get('/vuphuc/livewire/livewire.min.js?id=13b7c601', $handle);
    //     });

    //     Livewire::setUpdateRoute(function ($handle) {
    //         return Route::post('/vuphuc/livewire/update', $handle);
    //     });

    }
}
