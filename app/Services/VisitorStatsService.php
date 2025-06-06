<?php

namespace App\Services;

use App\Models\Visitor;
use App\Models\Course;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Visitor Statistics Service - KISS Principle
 * 
 * Chỉ 4 nhiệm vụ cốt lõi:
 * 1. Đếm unique visitors
 * 2. Đếm total page views
 * 3. Top 3 khóa học được xem nhiều nhất
 * 4. Cache kết quả 5 phút
 */
class VisitorStatsService
{
    private const CACHE_TTL = 300; // 5 phút

    /**
     * Lấy tất cả thống kê realtime
     */
    public function getRealtimeStats(): array
    {
        return Cache::remember('visitor_realtime_stats', self::CACHE_TTL, function () {
            return [
                'unique_visitors_today' => $this->getUniqueVisitorsToday(),
                'total_page_views_today' => $this->getTotalPageViewsToday(),
                'unique_visitors_total' => $this->getUniqueVisitorsTotal(),
                'total_page_views_total' => $this->getTotalPageViewsTotal(),
                'top_courses' => $this->getTopCourses(),
            ];
        });
    }

    /**
     * Unique visitors hôm nay
     */
    private function getUniqueVisitorsToday(): int
    {
        return Visitor::today()
            ->distinct('ip_address')
            ->count('ip_address');
    }

    /**
     * Tổng page views hôm nay
     */
    private function getTotalPageViewsToday(): int
    {
        return Visitor::today()->count();
    }

    /**
     * Tổng unique visitors
     */
    private function getUniqueVisitorsTotal(): int
    {
        return Visitor::distinct('ip_address')->count('ip_address');
    }

    /**
     * Tổng page views
     */
    private function getTotalPageViewsTotal(): int
    {
        return Visitor::count();
    }

    /**
     * Top 3 khóa học được xem nhiều nhất
     */
    private function getTopCourses(): array
    {
        return Visitor::select('course_id', DB::raw('COUNT(*) as views'))
            ->whereNotNull('course_id')
            ->with(['course:id,title,slug,thumbnail'])
            ->groupBy('course_id')
            ->orderBy('views', 'desc')
            ->take(3)
            ->get()
            ->map(function ($item) {
                return [
                    'course' => $item->course,
                    'views' => $item->views,
                ];
            })
            ->toArray();
    }

    /**
     * Lấy thống kê cho một khóa học cụ thể
     */
    public function getCourseStats(int $courseId): array
    {
        return Cache::remember("course_stats_{$courseId}", self::CACHE_TTL, function () use ($courseId) {
            return [
                'total_views' => Visitor::where('course_id', $courseId)->count(),
                'unique_visitors' => Visitor::where('course_id', $courseId)
                    ->distinct('ip_address')
                    ->count('ip_address'),
                'views_today' => Visitor::where('course_id', $courseId)
                    ->today()
                    ->count(),
                'views_this_week' => Visitor::where('course_id', $courseId)
                    ->thisWeek()
                    ->count(),
                'views_this_month' => Visitor::where('course_id', $courseId)
                    ->thisMonth()
                    ->count(),
            ];
        });
    }

    /**
     * Clear cache khi cần
     */
    public function clearCache(): void
    {
        Cache::forget('visitor_realtime_stats');
        
        // Clear cache cho từng khóa học
        $courseIds = Course::pluck('id');
        foreach ($courseIds as $courseId) {
            Cache::forget("course_stats_{$courseId}");
        }
    }

    /**
     * Lấy thống kê theo thời gian (cho chart)
     */
    public function getVisitorTrends(int $days = 7): array
    {
        return Cache::remember("visitor_trends_{$days}", self::CACHE_TTL, function () use ($days) {
            $data = [];
            
            for ($i = $days - 1; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $data[] = [
                    'date' => $date->format('Y-m-d'),
                    'label' => $date->format('d/m'),
                    'unique_visitors' => Visitor::whereDate('visited_at', $date)
                        ->distinct('ip_address')
                        ->count('ip_address'),
                    'page_views' => Visitor::whereDate('visited_at', $date)->count(),
                ];
            }
            
            return $data;
        });
    }
}
