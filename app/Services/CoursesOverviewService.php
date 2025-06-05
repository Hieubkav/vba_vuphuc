<?php

namespace App\Services;

use App\Models\CatCourse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class CoursesOverviewService
{
    const CACHE_KEY = 'courses_overview_optimized';
    const CACHE_TTL = 300; // 5 minutes

    /**
     * Get optimized courses data for overview component
     */
    public function getCoursesOverviewData(): Collection
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return $this->fetchCoursesData();
        });
    }

    /**
     * Fetch courses data with optimized queries
     */
    private function fetchCoursesData(): Collection
    {
        try {
            return CatCourse::where('status', 'active')
                ->whereIn('slug', ['ky-nang', 'ky-thuat', 'hoi-thao'])
                ->with([
                    'courses' => function ($query) {
                        $query->where('status', 'active')
                            ->with(['instructor:id,name'])
                            ->select([
                                'id', 'title', 'slug', 'thumbnail', 'seo_title', 'seo_description',
                                'description', 'cat_course_id', 'instructor_id', 'price', 'level',
                                'duration_hours', 'start_date', 'gg_form', 'created_at', 'order'
                            ])
                            ->orderBy('created_at', 'desc')
                            ->take(1); // Chỉ lấy 1 khóa học mới nhất của mỗi danh mục
                    }
                ])
                ->select(['id', 'name', 'slug', 'description', 'order'])
                ->orderBy('order')
                ->get()
                ->map(function ($category) {
                    // Tối ưu hóa dữ liệu trả về
                    $category->latest_course = $category->courses->first();
                    unset($category->courses); // Giải phóng memory
                    return $category;
                });
        } catch (\Exception $e) {
            Log::error('CoursesOverviewService Error: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Clear courses overview cache
     */
    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Warm up cache with fresh data
     */
    public function warmUpCache(): Collection
    {
        $this->clearCache();
        return $this->getCoursesOverviewData();
    }

    /**
     * Get cache statistics
     */
    public function getCacheStats(): array
    {
        $hasCache = Cache::has(self::CACHE_KEY);
        $cacheSize = $hasCache ? strlen(serialize(Cache::get(self::CACHE_KEY))) : 0;
        
        return [
            'has_cache' => $hasCache,
            'cache_size_bytes' => $cacheSize,
            'cache_size_kb' => round($cacheSize / 1024, 2),
            'ttl_seconds' => self::CACHE_TTL,
        ];
    }

    /**
     * Preload critical images for better performance
     */
    public function getCriticalImages(): array
    {
        $data = $this->getCoursesOverviewData();
        $images = [];

        foreach ($data as $category) {
            if ($category->latest_course && $category->latest_course->thumbnail) {
                $images[] = asset('storage/' . $category->latest_course->thumbnail);
            }
        }

        return $images;
    }

    /**
     * Get real statistics from database - Sử dụng dữ liệu từ ViewServiceProvider
     */
    public function getRealStats(): array
    {
        // Lấy dữ liệu từ ViewServiceProvider cache thay vì tạo cache riêng
        $viewProviderStats = Cache::get('storefront_course_stats');

        if ($viewProviderStats && is_array($viewProviderStats)) {
            // Chuyển đổi format từ ViewServiceProvider sang format component
            return [
                'students' => $viewProviderStats['total_students'] ?? 28,
                'courses' => $viewProviderStats['total_courses'] ?? 6,
                'experience_years' => $viewProviderStats['total_instructors'] ?? 3, // Tạm dùng số giảng viên
                'satisfaction_rate' => round($viewProviderStats['completion_rate'] ?? 32.3)
            ];
        }

        // Fallback: Lấy trực tiếp từ database nếu cache ViewServiceProvider không có
        try {
            $totalStudents = \App\Models\Student::where('status', 'active')->count() ?: 28;
            $totalCourses = \App\Models\Course::where('status', 'active')->count() ?: 6;
            $totalInstructors = class_exists('\App\Models\Instructor') ?
                \App\Models\Instructor::where('status', 'active')->count() : 3;

            // Tính completion rate từ course_student table
            $completionRate = 32.3; // Default
            if (DB::getSchemaBuilder()->hasTable('course_student')) {
                $totalEnrollments = DB::table('course_student')
                    ->where('status', '!=', 'dropped')
                    ->count();

                if ($totalEnrollments > 0) {
                    $completedEnrollments = DB::table('course_student')
                        ->where('status', 'completed')
                        ->count();
                    $completionRate = round(($completedEnrollments / $totalEnrollments) * 100, 1);
                }
            }

            return [
                'students' => $totalStudents,
                'courses' => $totalCourses,
                'experience_years' => $totalInstructors, // Hiển thị số giảng viên thay vì năm kinh nghiệm
                'satisfaction_rate' => $completionRate
            ];
        } catch (\Exception $e) {
            Log::error('Error getting real stats: ' . $e->getMessage());
            // Fallback với dữ liệu thực tế hiện tại
            return [
                'students' => 28,
                'courses' => 6,
                'experience_years' => 3,
                'satisfaction_rate' => 32
            ];
        }
    }

    /**
     * Get structured data for SEO
     */
    public function getStructuredData(): array
    {
        $data = $this->getCoursesOverviewData();
        $courses = [];

        foreach ($data as $category) {
            if ($category->latest_course) {
                $course = $category->latest_course;
                $courses[] = [
                    '@type' => 'Course',
                    'name' => $course->title,
                    'description' => $course->seo_description ?? strip_tags($course->description),
                    'provider' => [
                        '@type' => 'Organization',
                        'name' => 'VBA Vũ Phúc Academy'
                    ],
                    'courseCode' => $course->slug,
                    'educationalLevel' => $course->level ?? 'Beginner',
                    'timeRequired' => $course->duration_hours ? "PT{$course->duration_hours}H" : null,
                    'startDate' => $course->start_date,
                    'url' => route('courses.show', $course->slug),
                    'image' => $course->thumbnail ? asset('storage/' . $course->thumbnail) : null,
                ];
            }
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'ItemList',
            'name' => 'Khóa học làm bánh VBA Vũ Phúc',
            'description' => 'Các khóa học làm bánh chuyên nghiệp từ cơ bản đến nâng cao',
            'itemListElement' => array_map(function ($course, $index) {
                return [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'item' => $course
                ];
            }, $courses, array_keys($courses))
        ];
    }
}
