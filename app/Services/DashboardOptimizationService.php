<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Student;
use App\Models\Post;
use App\Models\Instructor;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardOptimizationService
{
    /**
     * Cache duration in seconds (5 minutes)
     */
    const CACHE_DURATION = 300;

    /**
     * Get optimized dashboard statistics
     */
    public function getDashboardStats(): array
    {
        return Cache::remember('dashboard_stats_optimized', self::CACHE_DURATION, function () {
            return [
                'courses' => $this->getCourseStats(),
                'students' => $this->getStudentStats(),
                'posts' => $this->getPostStats(),
                'instructors' => $this->getInstructorStats(),
                'system' => $this->getSystemStats(),
            ];
        });
    }

    /**
     * Get course statistics
     */
    private function getCourseStats(): array
    {
        $stats = Course::selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN status = "active" THEN 1 ELSE 0 END) as active,
            SUM(CASE WHEN status = "draft" THEN 1 ELSE 0 END) as draft,
            SUM(CASE WHEN is_featured = 1 THEN 1 ELSE 0 END) as featured
        ')->first();

        return [
            'total' => $stats->total ?? 0,
            'active' => $stats->active ?? 0,
            'draft' => $stats->draft ?? 0,
            'featured' => $stats->featured ?? 0,
            'growth' => $this->calculateGrowth('courses'),
        ];
    }

    /**
     * Get student statistics
     */
    private function getStudentStats(): array
    {
        $stats = Student::selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN status = "active" THEN 1 ELSE 0 END) as active,
            SUM(CASE WHEN status = "inactive" THEN 1 ELSE 0 END) as inactive
        ')->first();

        return [
            'total' => $stats->total ?? 0,
            'active' => $stats->active ?? 0,
            'inactive' => $stats->inactive ?? 0,
            'growth' => $this->calculateGrowth('students'),
        ];
    }

    /**
     * Get post statistics
     */
    private function getPostStats(): array
    {
        $stats = Post::selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN status = "active" THEN 1 ELSE 0 END) as published,
            SUM(CASE WHEN status = "draft" THEN 1 ELSE 0 END) as draft
        ')->first();

        return [
            'total' => $stats->total ?? 0,
            'published' => $stats->published ?? 0,
            'draft' => $stats->draft ?? 0,
            'growth' => $this->calculateGrowth('posts'),
        ];
    }

    /**
     * Get instructor statistics
     */
    private function getInstructorStats(): array
    {
        $stats = Instructor::selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN status = "active" THEN 1 ELSE 0 END) as active
        ')->first();

        return [
            'total' => $stats->total ?? 0,
            'active' => $stats->active ?? 0,
            'growth' => $this->calculateGrowth('instructors'),
        ];
    }

    /**
     * Get system statistics
     */
    private function getSystemStats(): array
    {
        return [
            'laravel_version' => app()->version(),
            'php_version' => PHP_VERSION,
            'database_size' => $this->getDatabaseSize(),
            'cache_status' => $this->getCacheStatus(),
            'storage_usage' => $this->getStorageUsage(),
        ];
    }

    /**
     * Calculate growth percentage for the last 30 days
     */
    private function calculateGrowth(string $table): float
    {
        $cacheKey = "growth_{$table}_30days";
        
        return Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($table) {
            $currentMonth = DB::table($table)
                ->where('created_at', '>=', now()->subDays(30))
                ->count();

            $previousMonth = DB::table($table)
                ->where('created_at', '>=', now()->subDays(60))
                ->where('created_at', '<', now()->subDays(30))
                ->count();

            if ($previousMonth == 0) {
                return $currentMonth > 0 ? 100 : 0;
            }

            return round((($currentMonth - $previousMonth) / $previousMonth) * 100, 1);
        });
    }



    /**
     * Get database size in MB
     */
    private function getDatabaseSize(): string
    {
        try {
            $size = DB::select("
                SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb
                FROM information_schema.tables
                WHERE table_schema = DATABASE()
            ")[0]->size_mb ?? 0;

            return $size . ' MB';
        } catch (\Exception $e) {
            return 'N/A';
        }
    }

    /**
     * Get cache status
     */
    private function getCacheStatus(): string
    {
        try {
            Cache::put('test_cache', 'working', 1);
            $test = Cache::get('test_cache');
            Cache::forget('test_cache');
            
            return $test === 'working' ? 'Hoạt động' : 'Lỗi';
        } catch (\Exception $e) {
            return 'Lỗi';
        }
    }

    /**
     * Get storage usage
     */
    private function getStorageUsage(): string
    {
        try {
            $storagePath = storage_path('app/public');
            if (!is_dir($storagePath)) {
                return 'N/A';
            }

            $size = $this->getDirectorySize($storagePath);
            return $this->formatBytes($size);
        } catch (\Exception $e) {
            return 'N/A';
        }
    }

    /**
     * Get directory size recursively
     */
    private function getDirectorySize(string $directory): int
    {
        $size = 0;
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($directory)) as $file) {
            if ($file->isFile()) {
                $size += $file->getSize();
            }
        }
        return $size;
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * Clear all dashboard caches
     */
    public function clearCache(): void
    {
        $cacheKeys = [
            'dashboard_stats_optimized',
            'growth_courses_30days',
            'growth_students_30days',
            'growth_posts_30days',
            'growth_instructors_30days',
        ];

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }
    }
}
