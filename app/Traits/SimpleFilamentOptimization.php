<?php

namespace App\Traits;

use App\Services\FilamentSimpleOptimizer;
use Illuminate\Database\Eloquent\Builder;

/**
 * Simple Filament Optimization Trait
 * 
 * Chỉ override 2 method quan trọng nhất:
 * 1. getEloquentQuery() - Tối ưu table listing
 * 2. Cung cấp helper methods đơn giản
 */
trait SimpleFilamentOptimization
{
    /**
     * Tối ưu query cho table listing
     * Override method này trong Resource để tự động tối ưu
     */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        
        // Áp dụng tối ưu hóa đơn giản
        $columns = static::getTableColumns();
        $relationships = static::getTableRelationships();
        
        // 1. Select chỉ cột cần thiết
        FilamentSimpleOptimizer::optimizeTableQuery($query, $columns);
        
        // 2. Eager load relationships
        FilamentSimpleOptimizer::eagerLoad($query, $relationships);
        
        return $query;
    }

    /**
     * Định nghĩa cột cần thiết cho table
     * Override trong từng Resource
     */
    protected static function getTableColumns(): array
    {
        return ['title', 'status', 'created_at']; // Default columns
    }

    /**
     * Định nghĩa relationships cần eager load
     * Override trong từng Resource
     */
    protected static function getTableRelationships(): array
    {
        return []; // Default: không có relationships
    }

    /**
     * Helper: Lấy dropdown options với cache
     */
    protected function getCachedOptions(string $model, string $key = 'name'): array
    {
        return FilamentSimpleOptimizer::cacheDropdownOptions($model, $key);
    }

    /**
     * Helper: Clear cache khi save/delete
     */
    protected function clearResourceCache(): void
    {
        FilamentSimpleOptimizer::clearCache();
    }
}
