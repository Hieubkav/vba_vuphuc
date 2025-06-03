<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Setting;
use App\Models\CatProduct;
use App\Models\Product;
use App\Models\Post;
use App\Models\Slider;
use App\Models\Partner;
use App\Models\MenuItem;
use App\Models\Association;
use Illuminate\Support\Facades\Cache;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share data với tất cả views
        View::composer('*', function ($view) {
            $this->shareGlobalData($view);
        });

        // Share data cho storefront views
        View::composer([
            'shop.storeFront',
            'components.storefront.*',
            'storefront.products.*',
            'storefront.posts.*'
        ], function ($view) {
            $this->shareStorefrontData($view);
        });

        // Share data cho layout views
        View::composer([
            'layouts.app',
            'layouts.shop',
            'components.public.*'
        ], function ($view) {
            $this->shareLayoutData($view);
        });
    }

    /**
     * Share global data với tất cả views
     */
    private function shareGlobalData($view)
    {
        // Cache settings trong 1 giờ
        $settings = Cache::remember('global_settings', 3600, function () {
            return Setting::where('status', 'active')->first();
        });

        $view->with([
            'globalSettings' => $settings,
            'settings' => $settings // Giữ lại để tương thích với code cũ
        ]);
    }

    /**
     * Share data cho storefront views - Tối ưu performance
     */
    private function shareStorefrontData($view)
    {
        // Cache riêng biệt cho từng loại dữ liệu để tối ưu hơn
        $storefrontData = [
            // Hero Banner - Cache 1 giờ
            'sliders' => Cache::remember('storefront_sliders', 3600, function () {
                return Slider::where('status', 'active')
                    ->orderBy('order')
                    ->select(['id', 'title', 'description', 'image_link', 'link', 'alt_text', 'order'])
                    ->get();
            }),

            // Categories data - Cache 2 giờ
            'categories' => Cache::remember('storefront_categories', 7200, function () {
                return CatProduct::where('status', 'active')
                    ->whereNull('parent_id')
                    ->orderBy('order')
                    ->select(['id', 'name', 'slug', 'image', 'order'])
                    ->take(12)
                    ->get();
            }),

            // Featured Products - Cache 30 phút
            'featuredProducts' => Cache::remember('storefront_products', 1800, function () {
                return Product::where('status', 'active')
                    ->where('is_hot', true)
                    ->with(['category', 'images' => function($query) {
                        $query->where('status', 'active')
                            ->orderByRaw('is_main DESC, `order` ASC');
                    }])
                    ->select(['id', 'name', 'slug', 'price', 'compare_price', 'is_hot', 'category_id', 'seo_title', 'order'])
                    ->orderBy('order', 'asc')
                    ->take(8)
                    ->get();
            }),

            // Services data - Cache 1 giờ
            'services' => Cache::remember('storefront_services', 3600, function () {
                return Post::where('status', 'active')
                    ->where('type', 'service')
                    ->with(['category:id,name', 'images' => function($query) {
                        $query->where('status', 'active')->orderBy('order')->take(1);
                    }])
                    ->select(['id', 'title', 'slug', 'seo_description', 'thumbnail', 'category_id', 'order'])
                    ->orderBy('order')
                    ->get();
            }),

            // News Posts - Cache 30 phút
            'newsPosts' => Cache::remember('storefront_news', 1800, function () {
                return Post::where('status', 'active')
                    ->where('type', 'news')
                    ->with(['category:id,name', 'images' => function($query) {
                        $query->where('status', 'active')->orderBy('order')->take(1);
                    }])
                    ->select(['id', 'title', 'slug', 'seo_description', 'thumbnail', 'category_id', 'order', 'created_at'])
                    ->orderBy('order')
                    ->orderBy('created_at', 'desc')
                    ->take(6)
                    ->get();
            }),

            // Courses - Cache 1 giờ
            'courses' => Cache::remember('storefront_courses', 3600, function () {
                return Post::where('status', 'active')
                    ->where('type', 'course')
                    ->with(['category:id,name', 'images' => function($query) {
                        $query->where('status', 'active')->orderBy('order')->take(1);
                    }])
                    ->select(['id', 'title', 'slug', 'seo_description', 'seo_title', 'thumbnail', 'category_id', 'order', 'created_at'])
                    ->orderBy('order')
                    ->orderBy('created_at', 'desc')
                    ->take(6)
                    ->get();
            }),

            // Partners - Cache 2 giờ
            'partners' => Cache::remember('storefront_partners', 7200, function () {
                return Partner::where('status', 'active')
                    ->select(['id', 'name', 'logo_link', 'website_link', 'order'])
                    ->orderBy('order')
                    ->get();
            }),
        ];

        $view->with($storefrontData);
    }

    /**
     * Share data cho layout views
     */
    private function shareLayoutData($view)
    {
        // Cache navigation data trong 2 giờ
        $navigationData = Cache::remember('navigation_data', 7200, function () {
            return [
                // Main Categories cho navigation
                'mainCategories' => CatProduct::where('status', 'active')
                    ->whereNull('parent_id')
                    ->with(['children' => function ($query) {
                        $query->where('status', 'active')->orderBy('order');
                    }])
                    ->orderBy('order')
                    ->get(),

                // Footer Categories
                'footerCategories' => CatProduct::where('status', 'active')
                    ->whereNull('parent_id')
                    ->orderBy('order')
                    ->take(6)
                    ->get(),

                // Recent Posts cho footer
                'recentPosts' => Post::where('status', 'active')
                    ->orderBy('created_at', 'desc')
                    ->take(3)
                    ->get(),

                // Menu Items cho dynamic navigation
                'menuItems' => MenuItem::where('status', 'active')
                    ->whereNull('parent_id')
                    ->with(['children' => function ($query) {
                        $query->where('status', 'active')->orderBy('order');
                    }])
                    ->orderBy('order')
                    ->get(),

                // Associations cho footer certification images
                'associations' => Association::where('status', 'active')
                    ->orderBy('order')
                    ->get(),
            ];
        });

        $view->with($navigationData);
    }

    /**
     * Clear cache khi cần thiết
     */
    public static function clearCache()
    {
        Cache::forget('global_settings');

        // Clear storefront caches
        Cache::forget('storefront_sliders');
        Cache::forget('storefront_categories');
        Cache::forget('storefront_products');
        Cache::forget('storefront_services');
        Cache::forget('storefront_news');
        Cache::forget('storefront_courses');
        Cache::forget('storefront_partners');

        Cache::forget('navigation_data');
    }

    /**
     * Refresh specific cache
     */
    public static function refreshCache($type = 'all')
    {
        switch ($type) {
            case 'settings':
                Cache::forget('global_settings');
                break;
            case 'storefront':
                Cache::forget('storefront_sliders');
                Cache::forget('storefront_categories');
                Cache::forget('storefront_products');
                Cache::forget('storefront_services');
                Cache::forget('storefront_news');
                Cache::forget('storefront_courses');
                Cache::forget('storefront_partners');
                break;
            case 'sliders':
                Cache::forget('storefront_sliders');
                break;
            case 'navigation':
                Cache::forget('navigation_data');
                break;
            case 'all':
            default:
                self::clearCache();
                break;
        }
    }
}
