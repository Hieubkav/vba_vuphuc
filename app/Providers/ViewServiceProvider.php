<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Setting;
use App\Models\Course;
use App\Models\CatPost;
use App\Models\Post;
use App\Models\Slider;
use App\Models\MenuItem;
use App\Models\Association;
use App\Models\Student;
use App\Models\CatCourse;
use App\Models\CourseGroup;
use App\Models\Testimonial;
use App\Models\WebDesign;
use App\Models\Faq;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

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
            'courses.*',
            'students.*',
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

        // Cache web design settings trong 1 giờ
        $webDesign = Cache::remember('web_design_settings', 3600, function () {
            return WebDesign::first();
        });

        $view->with([
            'globalSettings' => $settings,
            'settings' => $settings, // Giữ lại để tương thích với code cũ
            'webDesign' => $webDesign
        ]);
    }

    /**
     * Share data cho storefront views - Tối ưu performance cho website khóa học
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

            // Course Categories với khóa học mới nhất - Cache 2 giờ (Hiển thị tất cả danh mục có khóa học)
            'courseCategories' => Cache::remember('storefront_course_categories', 7200, function () {
                // Lấy tất cả danh mục có trạng thái active và có khóa học
                $categories = CatCourse::where('status', 'active')
                    ->whereHas('courses', function($query) {
                        $query->where('status', 'active');
                    })
                    ->select(['id', 'name', 'slug', 'description', 'image', 'order'])
                    ->orderBy('order')
                    ->get();

                // Lấy khóa học mới nhất cho từng danh mục riêng biệt để tránh bug eager loading
                return $categories->map(function ($category) {
                    $latestCourse = \App\Models\Course::where('cat_course_id', $category->id)
                        ->where('status', 'active')
                        ->with(['instructor:id,name', 'courseGroup:id,group_link'])
                        ->select([
                            'id', 'title', 'slug', 'description',
                            'duration_hours', 'level', 'start_date', 'end_date', 'thumbnail',
                            'instructor_id', 'gg_form', 'show_form_link',
                            'show_group_link', 'cat_course_id', 'course_group_id', 'created_at', 'seo_title', 'seo_description'
                        ])
                        ->orderBy('created_at', 'desc')
                        ->first(); // Lấy khóa học mới nhất

                    // Gán khóa học mới nhất vào category
                    $category->latest_course = $latestCourse;

                    return $category;
                })->filter(function ($category) {
                    // Chỉ giữ lại các danh mục có khóa học
                    return $category->latest_course !== null;
                });
            }),

            // Featured Courses - Cache 30 phút
            'featuredCourses' => Cache::remember('storefront_featured_courses', 1800, function () {
                return Course::where('status', 'active')
                    ->where('is_featured', true)
                    ->with(['courseCategory:id,name,slug', 'instructor:id,name'])
                    ->select([
                        'id', 'title', 'slug', 'duration_hours',
                        'level', 'is_featured', 'cat_course_id', 'instructor_id', 'seo_title', 'seo_description', 'thumbnail',
                        'order', 'max_students'
                    ])
                    ->orderBy('order', 'asc')
                    ->take(8)
                    ->get();
            }),

            // Latest Courses - Cache 30 phút
            'latestCourses' => Cache::remember('storefront_latest_courses', 1800, function () {
                return Course::where('status', 'active')
                    ->with(['courseCategory:id,name,slug', 'instructor:id,name'])
                    ->select([
                        'id', 'title', 'slug', 'duration_hours',
                        'level', 'cat_course_id', 'instructor_id', 'seo_title', 'seo_description', 'thumbnail', 'created_at'
                    ])
                    ->orderBy('created_at', 'desc')
                    ->take(6)
                    ->get();
            }),



            // Latest Posts - Cache 30 phút với tối ưu performance
            'latestPosts' => Cache::remember('storefront_latest_posts', 1800, function () {
                return Post::where('status', 'active')
                    ->with(['category:id,name,slug'])
                    ->select(['id', 'title', 'slug', 'content', 'thumbnail', 'category_id', 'order', 'created_at'])
                    ->orderBy('order')
                    ->orderBy('created_at', 'desc')
                    ->take(4) // Giới hạn 4 bài viết: 1 chính + 3 phụ
                    ->get()
                    ->map(function ($post) {
                        // Pre-check image existence để tránh check lại trong view
                        $post->has_valid_image = !empty($post->thumbnail) && \Illuminate\Support\Facades\Storage::exists('public/' . $post->thumbnail);
                        return $post;
                    });
            }),

            // Course Statistics - Cache 1 giờ
            'courseStats' => Cache::remember('storefront_course_stats', 3600, function () {
                return [
                    'total_courses' => Course::where('status', 'active')->count(),
                    'total_students' => Student::where('status', 'active')->count(),
                    'total_instructors' => \App\Models\Instructor::where('status', 'active')->count(),
                    'completion_rate' => $this->calculateCompletionRate(),
                ];
            }),

            // Testimonials - Cache 2 giờ
            'testimonials' => Cache::remember('storefront_testimonials', 7200, function () {
                return Testimonial::where('status', 'active')
                    ->select([
                        'id', 'name', 'position', 'company', 'location', 'content',
                        'rating', 'avatar', 'order'
                    ])
                    ->orderBy('order')
                    ->orderBy('created_at', 'desc')
                    ->get();
            }),

            // FAQs - Cache 2 giờ
            'faqs' => Cache::remember('storefront_faqs', 7200, function () {
                return Faq::where('status', 'active')
                    ->select(['id', 'question', 'answer', 'category', 'order'])
                    ->orderBy('order')
                    ->orderBy('created_at', 'desc')
                    ->get();
            }),

            // Course Categories Grid - Cache 2 giờ
            'courseCategoriesGrid' => Cache::remember('storefront_course_categories_grid', 7200, function () {
                return CatCourse::where('status', 'active')
                    ->with(['courses' => function($query) {
                        $query->where('status', 'active')
                            ->with(['courseCategory', 'instructor'])
                            ->orderBy('is_featured', 'desc')
                            ->orderBy('order')
                            ->orderBy('created_at', 'desc');
                    }])
                    ->withCount(['courses' => function($query) {
                        $query->where('status', 'active');
                    }])
                    ->orderBy('order')
                    ->orderBy('name')
                    ->get();
            }),

            // Course Groups - Nhóm khóa học Facebook/Zalo - Cache 1 giờ (Tối đa 6 nhóm)
            'courseGroups' => Cache::remember('storefront_course_groups', 3600, function () {
                try {
                    return CourseGroup::where('status', 'active')
                        ->whereNotNull('group_link')
                        ->select([
                            'id', 'name', 'description',
                            'group_link', 'group_type', 'max_members', 'current_members',
                            'order'
                        ])
                        ->orderBy('order', 'asc')
                        ->orderBy('created_at', 'desc')
                        ->take(6) // Giới hạn tối đa 6 nhóm để layout đẹp mắt
                        ->get();
                } catch (\Exception $e) {
                    // Fallback nếu bảng course_groups chưa tồn tại
                    return collect();
                }
            }),

            // Albums - Album timeline với PDF hoặc ảnh đơn - Cache 2 giờ
            'albums' => Cache::remember('storefront_albums', 7200, function () {
                try {
                    $albums = \App\Models\Album::where('status', 'active')
                        ->where('published_date', '<=', now())
                        ->where('featured', true) // Chỉ lấy album nổi bật
                        ->select([
                            'id', 'title', 'description', 'slug', 'pdf_file', 'media_type', 'thumbnail',
                            'published_date', 'featured', 'total_pages', 'file_size',
                            'download_count', 'view_count', 'created_at', 'order'
                        ])
                        ->orderBy('order', 'asc')
                        ->orderBy('published_date', 'desc')
                        ->orderBy('id', 'asc') // Fallback để đảm bảo thứ tự nhất quán
                        ->take(10)
                        ->get()
                        ->filter(function ($album) {
                            // Chỉ lấy albums có nội dung phù hợp với media_type
                            if ($album->media_type === 'pdf') {
                                return !empty($album->pdf_file);
                            } elseif ($album->media_type === 'images') {
                                return !empty($album->thumbnail);
                            }
                            return false;
                        });

                    return $albums;
                } catch (\Exception $e) {
                    // Fallback nếu bảng albums chưa tồn tại
                    return collect();
                }
            }),

            // Partners - Đối tác tin cậy - Cache 2 giờ
            'partners' => Cache::remember('storefront_partners', 7200, function () {
                try {
                    return \App\Models\Partner::where('status', 'active')
                        ->select(['id', 'name', 'logo_link', 'website_link', 'order'])
                        ->orderBy('order')
                        ->orderBy('name')
                        ->get();
                } catch (\Exception $e) {
                    // Fallback nếu bảng partners chưa tồn tại
                    return collect();
                }
            }),
        ];

        $view->with($storefrontData);
    }

    /**
     * Tính tỷ lệ hoàn thành khóa học
     */
    private function calculateCompletionRate()
    {
        $totalEnrollments = DB::table('course_student')
            ->where('status', '!=', 'dropped')
            ->count();

        if ($totalEnrollments === 0) {
            return 0;
        }

        $completedEnrollments = DB::table('course_student')
            ->where('status', 'completed')
            ->count();

        return round(($completedEnrollments / $totalEnrollments) * 100, 1);
    }

    /**
     * Share data cho layout views - Cập nhật cho website khóa học
     */
    private function shareLayoutData($view)
    {
        // Cache navigation data trong 2 giờ
        $navigationData = Cache::remember('navigation_data', 7200, function () {
            return [
                // Course Categories cho navigation
                'courseCategories' => CatCourse::where('status', 'active')
                    ->whereHas('courses', function($query) {
                        $query->where('status', 'active');
                    })
                    ->withCount(['courses' => function($query) {
                        $query->where('status', 'active');
                    }])
                    ->orderBy('order')
                    ->get(),

                // Post Categories cho navigation
                'postCategories' => CatPost::where('status', 'active')
                    ->whereHas('postsMany', function($query) {
                        $query->where('status', 'active');
                    })
                    ->withCount(['postsMany' => function($query) {
                        $query->where('status', 'active');
                    }])
                    ->orderBy('order')
                    ->take(6)
                    ->get(),

                // Recent Posts cho footer
                'recentPosts' => Post::where('status', 'active')
                    ->select(['id', 'title', 'slug', 'created_at', 'thumbnail'])
                    ->orderBy('created_at', 'desc')
                    ->take(3)
                    ->get(),

                // Recent Courses cho footer
                'recentCourses' => Course::where('status', 'active')
                    ->select(['id', 'title', 'slug', 'level', 'created_at'])
                    ->orderBy('created_at', 'desc')
                    ->take(3)
                    ->get(),

                // Menu Items cho dynamic navigation
                'menuItems' => MenuItem::where('status', 'active')
                    ->whereNull('parent_id')
                    ->with([
                        'children' => function ($query) {
                            $query->where('status', 'active')->orderBy('order');
                        },
                        'category:id,name,slug',
                        'post:id,title,slug',
                        'course:id,title,slug'
                    ])
                    ->orderBy('order')
                    ->get(),

                // Associations cho footer certification images
                'associations' => Association::where('status', 'active')
                    ->select(['id', 'name', 'image_link', 'website_link', 'order'])
                    ->orderBy('order')
                    ->get(),

                // Quick Stats cho footer
                'quickStats' => [
                    'total_courses' => Course::where('status', 'active')->count(),
                    'total_students' => Student::where('status', 'active')->count(),
                    'total_posts' => Post::where('status', 'active')->count(),
                    'total_categories' => CatPost::where('status', 'active')->count(),
                ],
            ];
        });

        $view->with($navigationData);
    }

    /**
     * Clear cache khi cần thiết - Cập nhật cho website khóa học
     */
    public static function clearCache()
    {
        Cache::forget('global_settings');

        // Clear storefront caches
        Cache::forget('storefront_sliders');
        Cache::forget('storefront_course_categories');
        Cache::forget('storefront_featured_courses');
        Cache::forget('storefront_latest_courses');
        Cache::forget('storefront_latest_posts');
        Cache::forget('storefront_course_stats');
        Cache::forget('storefront_testimonials');
        Cache::forget('storefront_course_groups');
        Cache::forget('storefront_albums');
        Cache::forget('storefront_partners');
        Cache::forget('storefront_faqs');

        Cache::forget('navigation_data');

        // Clear course detail caches
        self::clearCourseDetailCaches();
    }

    /**
     * Clear course detail caches
     */
    public static function clearCourseDetailCaches()
    {
        try {
            // Nếu sử dụng Redis cache
            if (config('cache.default') === 'redis') {
                $cacheKeys = Cache::getRedis()->keys('*course_detail_*');
                foreach ($cacheKeys as $key) {
                    Cache::forget(str_replace(config('cache.prefix') . ':', '', $key));
                }

                $relatedKeys = Cache::getRedis()->keys('*related_courses_*');
                foreach ($relatedKeys as $key) {
                    Cache::forget(str_replace(config('cache.prefix') . ':', '', $key));
                }
            } else {
                // Fallback: Clear cache bằng cách flush toàn bộ (cho file/array cache)
                Cache::flush();
            }
        } catch (\Exception $e) {
            // Fallback: Clear cache bằng cách flush toàn bộ
            Cache::flush();
        }
    }

    /**
     * Refresh specific cache - Cập nhật cho website khóa học
     */
    public static function refreshCache($type = 'all')
    {
        switch ($type) {
            case 'settings':
                Cache::forget('global_settings');
                break;
            case 'storefront':
                Cache::forget('storefront_sliders');
                Cache::forget('storefront_course_categories');
                Cache::forget('storefront_featured_courses');
                Cache::forget('storefront_latest_courses');
                Cache::forget('storefront_services');
                Cache::forget('storefront_news');
                Cache::forget('storefront_course_stats');
                Cache::forget('storefront_testimonials');
                Cache::forget('storefront_faqs');
                Cache::forget('storefront_course_groups');
                Cache::forget('storefront_albums');
                Cache::forget('storefront_partners');
                self::clearCourseDetailCaches();
                break;
            case 'webdesign':
                Cache::forget('web_design_settings');
                break;
            case 'courses':
                Cache::forget('storefront_featured_courses');
                Cache::forget('storefront_latest_courses');
                Cache::forget('storefront_course_stats');
                Cache::forget('storefront_course_categories');
                Cache::forget('storefront_course_groups');
                break;
            case 'sliders':
                Cache::forget('storefront_sliders');
                break;
            case 'navigation':
                Cache::forget('navigation_data');
                break;
            case 'posts':
                Cache::forget('storefront_latest_posts');
                break;
            case 'testimonials':
                Cache::forget('storefront_testimonials');
                break;
            case 'albums':
                Cache::forget('storefront_albums');
                break;
            case 'all':
            default:
                self::clearCache();
                break;
        }
    }

    /**
     * Clear albums cache specifically - Auto trigger method
     */
    public static function clearAlbumsCache(): void
    {
        try {
            // Clear albums cache
            Cache::forget('storefront_albums');

            // Also clear related caches that might contain album data
            Cache::forget('storefront_latest_posts');
            Cache::forget('navigation_data');

            // Clear any pattern-based caches if using Redis
            if (config('cache.default') === 'redis') {
                try {
                    $albumKeys = Cache::getRedis()->keys('*album*');
                    foreach ($albumKeys as $key) {
                        Cache::forget(str_replace(config('cache.prefix') . ':', '', $key));
                    }
                } catch (\Exception $redisException) {
                    // Continue if Redis operations fail
                }
            }

        } catch (\Exception $e) {
            // Fallback: Clear all cache if specific clearing fails
            Cache::flush();
        }
    }
}
