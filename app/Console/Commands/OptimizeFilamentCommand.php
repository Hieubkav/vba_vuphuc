<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FilamentOptimizationService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

/**
 * Command để tối ưu hóa Filament Admin Panel
 * 
 * Chạy các tác vụ tối ưu hóa để cải thiện hiệu suất Filament
 */
class OptimizeFilamentCommand extends Command
{
    protected $signature = 'filament:optimize 
                            {--clear-cache : Clear all optimization cache}
                            {--analyze : Analyze performance issues}
                            {--fix : Auto fix common performance issues}
                            {--stats : Show performance statistics}';

    protected $description = 'Tối ưu hóa hiệu suất Filament Admin Panel';

    protected FilamentOptimizationService $optimizationService;

    public function __construct(FilamentOptimizationService $optimizationService)
    {
        parent::__construct();
        $this->optimizationService = $optimizationService;
    }

    public function handle(): int
    {
        $this->info('🚀 Bắt đầu tối ưu hóa Filament Admin Panel...');

        if ($this->option('clear-cache')) {
            $this->clearCache();
        }

        if ($this->option('analyze')) {
            $this->analyzePerformance();
        }

        if ($this->option('fix')) {
            $this->autoFix();
        }

        if ($this->option('stats')) {
            $this->showStats();
        }

        if (!$this->hasOption('clear-cache') && !$this->hasOption('analyze') && 
            !$this->hasOption('fix') && !$this->hasOption('stats')) {
            $this->runFullOptimization();
        }

        $this->info('✅ Hoàn thành tối ưu hóa Filament!');
        return 0;
    }

    protected function clearCache(): void
    {
        $this->info('🧹 Đang xóa cache tối ưu hóa...');
        
        if ($this->optimizationService->clearCache()) {
            $this->info('✅ Đã xóa cache tối ưu hóa');
        } else {
            $this->warn('⚠️ Không thể xóa một số cache');
        }

        // Clear Filament cache
        try {
            Artisan::call('filament:cache-components');
            $this->info('✅ Đã cache lại Filament components');
        } catch (\Exception $e) {
            $this->warn('⚠️ Không thể cache Filament components: ' . $e->getMessage());
        }
    }

    protected function analyzePerformance(): void
    {
        $this->info('🔍 Đang phân tích hiệu suất...');

        // Kiểm tra database
        $this->checkDatabasePerformance();

        // Kiểm tra cache
        $this->checkCachePerformance();

        // Kiểm tra memory
        $this->checkMemoryUsage();

        // Kiểm tra slow queries
        $this->checkSlowQueries();
    }

    protected function autoFix(): void
    {
        $this->info('🔧 Đang tự động sửa các vấn đề hiệu suất...');

        // Tối ưu database
        $this->optimizeDatabase();

        // Tối ưu cache
        $this->optimizeCache();

        // Tối ưu memory
        $this->optimizeMemory();

        $this->info('✅ Đã hoàn thành auto-fix');
    }

    protected function showStats(): void
    {
        $this->info('📊 Thống kê hiệu suất Filament:');

        $stats = $this->optimizationService->getPerformanceStats();

        $this->table(
            ['Metric', 'Value'],
            [
                ['Cache Hits', number_format($stats['query_cache_hits'])],
                ['Cache Misses', number_format($stats['query_cache_misses'])],
                ['Memory Usage', $this->formatBytes($stats['memory_usage'])],
                ['Peak Memory', $this->formatBytes($stats['peak_memory'])],
                ['Cache Size', number_format($stats['cache_size'])],
                ['Query Caching', $stats['config']['enable_query_caching'] ? '✅ Enabled' : '❌ Disabled'],
                ['Eager Loading', $stats['config']['enable_eager_loading'] ? '✅ Enabled' : '❌ Disabled'],
            ]
        );
    }

    protected function runFullOptimization(): void
    {
        $this->info('🚀 Chạy tối ưu hóa toàn diện...');

        // 1. Clear cache cũ
        $this->clearCache();

        // 2. Phân tích vấn đề
        $this->analyzePerformance();

        // 3. Auto fix
        $this->autoFix();

        // 4. Hiển thị stats
        $this->showStats();
    }

    protected function checkDatabasePerformance(): void
    {
        $this->info('📊 Kiểm tra hiệu suất database...');

        try {
            // Kiểm tra số lượng bảng
            $tables = DB::select('SHOW TABLES');
            $this->line("Số bảng: " . count($tables));

            // Kiểm tra kích thước database
            $size = DB::select("
                SELECT 
                    ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS 'DB Size in MB' 
                FROM information_schema.tables 
                WHERE table_schema = DATABASE()
            ");
            
            if (!empty($size)) {
                $this->line("Kích thước DB: " . $size[0]->{'DB Size in MB'} . " MB");
            }

        } catch (\Exception $e) {
            $this->warn('⚠️ Không thể kiểm tra database: ' . $e->getMessage());
        }
    }

    protected function checkCachePerformance(): void
    {
        $this->info('💾 Kiểm tra hiệu suất cache...');

        try {
            $cacheDriver = config('cache.default');
            $this->line("Cache driver: " . $cacheDriver);

            if ($cacheDriver === 'redis') {
                $redis = Cache::getRedis();
                $info = $redis->info();
                $this->line("Redis memory: " . ($info['used_memory_human'] ?? 'N/A'));
            }

        } catch (\Exception $e) {
            $this->warn('⚠️ Không thể kiểm tra cache: ' . $e->getMessage());
        }
    }

    protected function checkMemoryUsage(): void
    {
        $this->info('🧠 Kiểm tra sử dụng memory...');

        $current = memory_get_usage(true);
        $peak = memory_get_peak_usage(true);
        $limit = ini_get('memory_limit');

        $this->line("Memory hiện tại: " . $this->formatBytes($current));
        $this->line("Memory peak: " . $this->formatBytes($peak));
        $this->line("Memory limit: " . $limit);

        if ($current > (1024 * 1024 * 100)) { // > 100MB
            $this->warn('⚠️ Memory usage cao, cần tối ưu');
        }
    }

    protected function checkSlowQueries(): void
    {
        $this->info('🐌 Kiểm tra slow queries...');

        try {
            DB::enableQueryLog();
            
            // Simulate some queries
            DB::table('posts')->count();
            DB::table('courses')->count();
            
            $queries = DB::getQueryLog();
            
            foreach ($queries as $query) {
                if ($query['time'] > 100) { // > 100ms
                    $this->warn("Slow query: " . $query['query'] . " ({$query['time']}ms)");
                }
            }

        } catch (\Exception $e) {
            $this->warn('⚠️ Không thể kiểm tra slow queries: ' . $e->getMessage());
        }
    }

    protected function optimizeDatabase(): void
    {
        $this->info('🗄️ Tối ưu database...');

        try {
            // Optimize tables
            $tables = ['posts', 'courses', 'categories', 'users'];
            
            foreach ($tables as $table) {
                try {
                    DB::statement("OPTIMIZE TABLE {$table}");
                    $this->line("✅ Optimized table: {$table}");
                } catch (\Exception $e) {
                    $this->warn("⚠️ Cannot optimize table {$table}: " . $e->getMessage());
                }
            }

        } catch (\Exception $e) {
            $this->warn('⚠️ Database optimization failed: ' . $e->getMessage());
        }
    }

    protected function optimizeCache(): void
    {
        $this->info('💾 Tối ưu cache...');

        // Warm up important caches
        $this->optimizationService->cacheQuery('posts_count', function() {
            return DB::table('posts')->where('status', 'active')->count();
        });

        $this->optimizationService->cacheQuery('courses_count', function() {
            return DB::table('courses')->where('status', 'active')->count();
        });

        $this->line('✅ Cache warmed up');
    }

    protected function optimizeMemory(): void
    {
        $this->info('🧠 Tối ưu memory...');

        $this->optimizationService->optimizeMemory();
        
        $this->line('✅ Memory optimized');
    }

    protected function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
