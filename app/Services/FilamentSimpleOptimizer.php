<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

/**
 * Simple Filament Optimizer - KISS Principle
 * 
 * Chỉ 3 tối ưu hóa cơ bản nhất, hiệu quả nhất:
 * 1. Eager Loading để tránh N+1 queries
 * 2. Select chỉ cột cần thiết
 * 3. Cache đơn giản cho dropdown options
 */
class FilamentSimpleOptimizer
{
    /**
     * Tối ưu query cho table listing - Chỉ select cột cần thiết
     */
    public static function optimizeTableQuery(Builder $query, array $columns = []): Builder
    {
        // 1. Select chỉ cột cần thiết (quan trọng nhất)
        if (!empty($columns)) {
            $query->select(array_merge(['id'], $columns));
        }

        // 2. Default ordering nếu chưa có
        if (!$query->getQuery()->orders) {
            $query->latest('id');
        }

        return $query;
    }

    /**
     * Eager load relationships để tránh N+1 queries
     */
    public static function eagerLoad(Builder $query, array $relationships): Builder
    {
        if (!empty($relationships)) {
            $query->with($relationships);
        }

        return $query;
    }

    /**
     * Cache đơn giản cho dropdown options (10 phút)
     */
    public static function cacheDropdownOptions(string $model, string $key = 'name', int $minutes = 10): array
    {
        $cacheKey = "dropdown_{$model}_{$key}";
        
        return Cache::remember($cacheKey, $minutes * 60, function() use ($model, $key) {
            return $model::query()
                ->select(['id', $key])
                ->where('status', 'active')
                ->orderBy($key)
                ->pluck($key, 'id')
                ->toArray();
        });
    }

    /**
     * Clear cache đơn giản
     */
    public static function clearCache(string $pattern = 'dropdown_'): void
    {
        // Chỉ clear cache dropdown, đơn giản nhất
        if (config('cache.default') === 'redis') {
            $keys = \Illuminate\Support\Facades\Redis::keys("*{$pattern}*");
            if (!empty($keys)) {
                \Illuminate\Support\Facades\Redis::del($keys);
            }
        } else {
            // File cache - clear toàn bộ (đơn giản)
            Cache::flush();
        }
    }
}
