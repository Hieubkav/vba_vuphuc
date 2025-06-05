<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;
use App\Models\Instructor;
use App\Models\Testimonial;
use App\Observers\InstructorObserver;
use App\Observers\TestimonialObserver;


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
