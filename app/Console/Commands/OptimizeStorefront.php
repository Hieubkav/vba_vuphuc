<?php

namespace App\Console\Commands;

use App\Providers\ViewServiceProvider;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;

class OptimizeStorefront extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storefront:optimize {--clear : Clear all caches} {--warm : Warm up caches}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimize storefront performance by managing caches';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $clear = $this->option('clear');
        $warm = $this->option('warm');

        if ($clear) {
            $this->clearCaches();
        }

        if ($warm) {
            $this->warmUpCaches();
        }

        if (!$clear && !$warm) {
            $this->info('Available options:');
            $this->line('  --clear  Clear all storefront caches');
            $this->line('  --warm   Warm up caches with fresh data');
            $this->line('');
            $this->line('Example: php artisan storefront:optimize --clear --warm');
        }

        return 0;
    }

    /**
     * Clear all caches
     */
    private function clearCaches(): void
    {
        $this->info('🧹 Clearing caches...');

        // Clear application cache
        Artisan::call('cache:clear');
        $this->line('✅ Application cache cleared');

        // Clear view cache
        Artisan::call('view:clear');
        $this->line('✅ View cache cleared');

        // Clear config cache
        Artisan::call('config:clear');
        $this->line('✅ Config cache cleared');

        // Clear route cache
        Artisan::call('route:clear');
        $this->line('✅ Route cache cleared');

        // Clear storefront specific caches
        $cacheKeys = [
            'storefront_sliders',
            'storefront_categories',
            'storefront_courses',
            'storefront_posts',
            'storefront_testimonials',
            'navigation_data',
            'global_settings'
        ];

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }
        $this->line('✅ Storefront caches cleared');

        $this->info('🎉 All caches cleared successfully!');
    }

    /**
     * Warm up caches
     */
    private function warmUpCaches(): void
    {
        $this->info('🔥 Warming up caches...');

        // Cache config
        Artisan::call('config:cache');
        $this->line('✅ Config cached');

        // Cache routes
        Artisan::call('route:cache');
        $this->line('✅ Routes cached');

        // Cache views
        Artisan::call('view:cache');
        $this->line('✅ Views cached');

        // Warm up storefront data by accessing homepage
        try {
            $this->info('🌐 Warming up storefront data...');

            // Simulate accessing homepage to trigger cache warming
            $this->warmUpStorefrontData();

            $this->line('✅ Storefront data cached');
        } catch (\Exception $e) {
            $this->error('❌ Failed to warm up storefront data: ' . $e->getMessage());
        }

        $this->info('🎉 Cache warming completed!');
    }

    /**
     * Warm up storefront specific data - Đã sửa cho website khóa học
     */
    private function warmUpStorefrontData(): void
    {
        // Warm up settings
        Cache::remember('global_settings', 3600, function () {
            return \App\Models\Setting::first();
        });

        // Warm up sliders
        Cache::remember('storefront_sliders', 3600, function () {
            return \App\Models\Slider::where('status', 'active')
                ->orderBy('order')
                ->select(['id', 'title', 'description', 'image_link', 'link', 'order'])
                ->get();
        });

        // Warm up course categories
        Cache::remember('storefront_categories', 7200, function () {
            return \App\Models\CatCourse::where('status', 'active')
                ->orderBy('order')
                ->select(['id', 'name', 'slug', 'description', 'order'])
                ->take(12)
                ->get();
        });

        // Warm up featured courses
        Cache::remember('storefront_courses', 1800, function () {
            return \App\Models\Course::where('status', 'active')
                ->where('is_featured', true)
                ->with(['category:id,name', 'instructor:id,name'])
                ->select(['id', 'title', 'slug', 'price', 'thumbnail', 'cat_course_id', 'instructor_id', 'level', 'order'])
                ->orderBy('order')
                ->take(8)
                ->get();
        });

        // Warm up posts/news
        Cache::remember('storefront_posts', 1800, function () {
            return \App\Models\Post::where('status', 'active')
                ->with(['category:id,name'])
                ->select(['id', 'title', 'slug', 'seo_description', 'thumbnail', 'cat_post_id', 'order', 'created_at'])
                ->orderBy('order')
                ->orderBy('created_at', 'desc')
                ->take(6)
                ->get();
        });

        // Warm up testimonials
        Cache::remember('storefront_testimonials', 7200, function () {
            return \App\Models\Testimonial::where('status', 'active')
                ->select(['id', 'name', 'position', 'content', 'avatar', 'order'])
                ->orderBy('order')
                ->get();
        });

        // Warm up navigation data
        Cache::remember('navigation_data', 7200, function () {
            return [
                'courseCategories' => \App\Models\CatCourse::where('status', 'active')
                    ->orderBy('order')
                    ->get(),
                'menuItems' => \App\Models\MenuItem::where('status', 'active')
                    ->whereNull('parent_id')
                    ->with(['children' => function ($query) {
                        $query->where('status', 'active')->orderBy('order');
                    }])
                    ->orderBy('order')
                    ->get(),
            ];
        });
    }
}
