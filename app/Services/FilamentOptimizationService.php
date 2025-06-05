<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Service tối ưu hóa hiệu suất Filament Admin Panel
 * 
 * Tối ưu hóa toàn diện cho Filament để cải thiện tốc độ load
 * mà không gây lỗi dự án, có thể tái sử dụng cho nhiều dự án
 */
class FilamentOptimizationService
{
    protected array $config;
    protected array $queryCache = [];
    protected array $relationshipCache = [];

    public function __construct()
    {
        $this->config = config('filament.optimization', [
            'enable_query_caching' => true,
            'cache_duration' => 300,
            'enable_eager_loading' => true,
            'pagination_size' => 25,
            'enable_asset_optimization' => true,
            'enable_memory_optimization' => true,
        ]);
    }

    /**
     * Tối ưu hóa query cho Filament Resource
     */
    public function optimizeResourceQuery(Builder $query, array $options = []): Builder
    {
        if (!$this->config['enable_query_caching']) {
            return $query;
        }

        // Tối ưu select chỉ các cột cần thiết
        if (isset($options['select']) && is_array($options['select'])) {
            $query->select($options['select']);
        }

        // Eager loading relationships để tránh N+1
        if ($this->config['enable_eager_loading'] && isset($options['with'])) {
            $query->with($options['with']);
        }

        // Tối ưu pagination
        if (isset($options['paginate'])) {
            $perPage = min($options['paginate'], $this->config['pagination_size']);
            $query->limit($perPage);
        }

        return $query;
    }

    /**
     * Cache kết quả query với key thông minh
     */
    public function cacheQuery(string $key, callable $callback, ?int $duration = null): mixed
    {
        if (!$this->config['enable_query_caching']) {
            return $callback();
        }

        $duration = $duration ?? $this->config['cache_duration'];
        $cacheKey = $this->generateCacheKey($key);

        return Cache::remember($cacheKey, $duration, $callback);
    }

    /**
     * Tối ưu hóa relationship loading
     */
    public function optimizeRelationships(Model $model, array $relationships): Model
    {
        if (!$this->config['enable_eager_loading']) {
            return $model;
        }

        $optimizedRelationships = [];
        
        foreach ($relationships as $relation => $constraints) {
            if (is_string($constraints)) {
                $optimizedRelationships[$constraints] = function($query) {
                    $this->applyBasicOptimizations($query);
                };
            } else {
                $optimizedRelationships[$relation] = function($query) use ($constraints) {
                    $this->applyBasicOptimizations($query);
                    if (is_callable($constraints)) {
                        $constraints($query);
                    }
                };
            }
        }

        return $model->load($optimizedRelationships);
    }

    /**
     * Tối ưu hóa cho Filament Table
     */
    public function optimizeTableQuery(Builder $query, array $columns = []): Builder
    {
        // Chỉ select các cột cần thiết cho table
        if (!empty($columns)) {
            $query->select(array_merge(['id'], $columns));
        }

        // Tối ưu ordering
        if (!$query->getQuery()->orders) {
            $query->orderBy('id', 'desc');
        }

        return $query;
    }

    /**
     * Tối ưu hóa cho Filament Form
     */
    public function optimizeFormQuery(Builder $query, string $modelClass): Builder
    {
        // Eager load relationships thường dùng trong form
        $commonRelations = $this->getCommonFormRelationships($modelClass);
        
        if (!empty($commonRelations)) {
            $query->with($commonRelations);
        }

        return $query;
    }

    /**
     * Clear cache theo pattern
     */
    public function clearCache(?string $pattern = null): bool
    {
        try {
            // Kiểm tra xem có sử dụng Redis không
            if (config('cache.default') === 'redis') {
                if ($pattern) {
                    $keys = Cache::getRedis()->keys("filament_opt_{$pattern}*");
                    if (!empty($keys)) {
                        Cache::getRedis()->del($keys);
                    }
                } else {
                    Cache::getRedis()->flushdb();
                }
            } else {
                // Fallback cho cache driver khác (file, array, etc.)
                if ($pattern) {
                    // Với file cache, chúng ta chỉ có thể clear toàn bộ
                    Cache::flush();
                } else {
                    Cache::flush();
                }
            }

            return true;
        } catch (\Exception $e) {
            Log::warning('Failed to clear Filament optimization cache', [
                'pattern' => $pattern,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Tối ưu hóa memory usage
     */
    public function optimizeMemory(): void
    {
        if (!$this->config['enable_memory_optimization']) {
            return;
        }

        // Clear query cache trong memory
        $this->queryCache = [];
        $this->relationshipCache = [];

        // Force garbage collection
        if (function_exists('gc_collect_cycles')) {
            gc_collect_cycles();
        }
    }

    /**
     * Lấy thống kê hiệu suất
     */
    public function getPerformanceStats(): array
    {
        return [
            'query_cache_hits' => Cache::get('filament_opt_cache_hits', 0),
            'query_cache_misses' => Cache::get('filament_opt_cache_misses', 0),
            'memory_usage' => memory_get_usage(true),
            'peak_memory' => memory_get_peak_usage(true),
            'cache_size' => count($this->queryCache),
            'config' => $this->config,
        ];
    }

    /**
     * Tạo cache key thông minh
     */
    protected function generateCacheKey(string $key): string
    {
        return 'filament_opt_' . md5($key . serialize($this->config));
    }

    /**
     * Áp dụng tối ưu hóa cơ bản cho query
     */
    protected function applyBasicOptimizations(Builder $query): void
    {
        // Tránh select * khi không cần thiết
        if (empty($query->getQuery()->columns)) {
            // Lấy tên bảng để xác định cột phù hợp
            $table = $query->getModel()->getTable();
            $columns = ['id', 'created_at'];

            // Thêm cột phù hợp theo từng bảng
            if (in_array($table, ['posts', 'courses', 'cat_courses', 'cat_posts', 'instructors', 'students', 'course_groups', 'partners', 'associations'])) {
                $columns[] = 'name';
            }

            if (in_array($table, ['posts', 'courses', 'course_materials', 'sliders', 'faqs'])) {
                $columns[] = 'title';
            }

            if (in_array($table, ['posts', 'courses', 'cat_courses', 'cat_posts', 'instructors', 'students', 'course_groups', 'partners', 'associations', 'course_materials', 'sliders', 'faqs'])) {
                $columns[] = 'status';
            }

            $query->select(array_unique($columns));
        }

        // Limit kết quả nếu chưa có
        if (!$query->getQuery()->limit) {
            $query->limit(100);
        }
    }

    /**
     * Lấy relationships thường dùng trong form
     */
    protected function getCommonFormRelationships(string $modelClass): array
    {
        $commonRelations = [
            'App\Models\Post' => ['category', 'images'],
            'App\Models\Course' => ['category', 'instructor', 'images'],
            'App\Models\Album' => ['images'],
            'App\Models\Student' => ['courses'],
        ];

        return $commonRelations[$modelClass] ?? [];
    }

    /**
     * Tối ưu hóa cho dropdown/select options
     */
    public function optimizeSelectOptions(Builder $query, string $valueColumn = 'id', string $labelColumn = 'name'): Collection
    {
        return $this->cacheQuery(
            "select_options_{$query->getModel()->getTable()}_{$valueColumn}_{$labelColumn}",
            function() use ($query, $valueColumn, $labelColumn) {
                return $query->select([$valueColumn, $labelColumn])
                    ->where('status', 'active')
                    ->orderBy($labelColumn)
                    ->limit(1000)
                    ->pluck($labelColumn, $valueColumn);
            },
            600 // Cache 10 phút cho select options
        );
    }

    /**
     * Tối ưu hóa cho search/filter
     */
    public function optimizeSearchQuery(Builder $query, string $searchTerm, array $searchColumns): Builder
    {
        if (empty($searchTerm) || empty($searchColumns)) {
            return $query;
        }

        return $query->where(function($q) use ($searchTerm, $searchColumns) {
            foreach ($searchColumns as $column) {
                $q->orWhere($column, 'LIKE', "%{$searchTerm}%");
            }
        });
    }
}
