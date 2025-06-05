<?php

namespace App\Traits;

use App\Services\FilamentOptimizationService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait để tối ưu hóa Filament Resources
 * 
 * Sử dụng trait này trong các Resource để tự động áp dụng
 * các tối ưu hóa hiệu suất mà không cần viết lại code
 */
trait OptimizedFilamentResource
{
    protected static ?FilamentOptimizationService $optimizationService = null;

    /**
     * Lấy instance của optimization service
     */
    protected static function getOptimizationService(): FilamentOptimizationService
    {
        if (static::$optimizationService === null) {
            static::$optimizationService = app(FilamentOptimizationService::class);
        }

        return static::$optimizationService;
    }

    /**
     * Tối ưu hóa query cho table listing
     */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        
        // Áp dụng tối ưu hóa cơ bản
        $optimizationService = static::getOptimizationService();
        
        // Lấy các cột cần thiết cho table
        $tableColumns = static::getTableColumns();
        
        return $optimizationService->optimizeTableQuery($query, $tableColumns);
    }

    /**
     * Tối ưu hóa form query
     * Chỉ sử dụng khi Resource có method này
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Tối ưu hóa relationships cho form
        if (isset($this->record) && $this->record instanceof Model) {
            $optimizationService = static::getOptimizationService();
            $relationships = static::getFormRelationships();

            if (!empty($relationships)) {
                $optimizationService->optimizeRelationships($this->record, $relationships);
            }
        }

        return method_exists(get_parent_class(), 'mutateFormDataBeforeFill')
            ? parent::mutateFormDataBeforeFill($data)
            : $data;
    }

    /**
     * Tối ưu hóa table query với caching
     * Override method này trong Resource nếu cần custom
     */
    protected static function optimizeTableQuery(Builder $query): Builder
    {
        $optimizationService = static::getOptimizationService();

        // Lấy các cột cần thiết cho table
        $tableColumns = static::getTableColumns();

        return $optimizationService->optimizeTableQuery($query, $tableColumns);
    }

    /**
     * Lấy danh sách cột cần thiết cho table
     * Override trong resource để tối ưu
     */
    protected static function getTableColumns(): array
    {
        return [
            'id',
            'name', 
            'title',
            'status',
            'order',
            'created_at',
            'updated_at'
        ];
    }

    /**
     * Lấy relationships cần thiết cho form
     * Override trong resource để tối ưu
     */
    protected static function getFormRelationships(): array
    {
        return [];
    }

    /**
     * Tối ưu hóa select options cho form
     */
    protected function getSelectOptions(string $model, string $valueColumn = 'id', string $labelColumn = 'name'): array
    {
        $optimizationService = static::getOptimizationService();
        $query = $model::query();
        
        return $optimizationService->optimizeSelectOptions($query, $valueColumn, $labelColumn)->toArray();
    }

    /**
     * Tối ưu hóa search trong table
     */
    protected function applySearchToTableQuery(Builder $query): Builder
    {
        $search = $this->tableSearch;
        
        if (empty($search)) {
            return $query;
        }

        $optimizationService = static::getOptimizationService();
        $searchColumns = static::getSearchableColumns();
        
        return $optimizationService->optimizeSearchQuery($query, $search, $searchColumns);
    }

    /**
     * Lấy các cột có thể search
     * Override trong resource
     */
    protected static function getSearchableColumns(): array
    {
        return ['name', 'title'];
    }

    /**
     * Clear cache cho resource này
     */
    public static function clearResourceCache(): bool
    {
        $optimizationService = static::getOptimizationService();
        $pattern = class_basename(static::class);
        
        return $optimizationService->clearCache($pattern);
    }

    /**
     * Tối ưu hóa memory sau khi xử lý
     */
    protected function afterSave(): void
    {
        parent::afterSave();
        
        // Clear cache và tối ưu memory
        static::clearResourceCache();
        static::getOptimizationService()->optimizeMemory();
    }

    /**
     * Tối ưu hóa memory sau khi xóa
     */
    protected function afterDelete(): void
    {
        parent::afterDelete();
        
        // Clear cache và tối ưu memory
        static::clearResourceCache();
        static::getOptimizationService()->optimizeMemory();
    }

    /**
     * Lấy thống kê hiệu suất cho resource
     */
    public static function getPerformanceStats(): array
    {
        $optimizationService = static::getOptimizationService();
        $stats = $optimizationService->getPerformanceStats();
        
        $stats['resource'] = static::class;
        $stats['model'] = static::getModel();
        
        return $stats;
    }

    /**
     * Tối ưu hóa pagination
     */
    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [10, 25, 50, 100];
    }

    /**
     * Tối ưu hóa default sort
     */
    protected function getDefaultTableSortColumn(): ?string
    {
        return 'id';
    }

    protected function getDefaultTableSortDirection(): ?string
    {
        return 'desc';
    }

    /**
     * Tối ưu hóa bulk actions
     */
    protected function getTableBulkActions(): array
    {
        // Giới hạn bulk actions để tránh timeout
        return array_slice(parent::getTableBulkActions(), 0, 5);
    }
}
