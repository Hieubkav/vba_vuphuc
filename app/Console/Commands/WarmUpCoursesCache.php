<?php

namespace App\Console\Commands;

use App\Services\CoursesOverviewService;
use Illuminate\Console\Command;

class WarmUpCoursesCache extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'courses:warm-cache 
                            {--clear : Clear existing cache before warming up}
                            {--stats : Show cache statistics}';

    /**
     * The console command description.
     */
    protected $description = 'Warm up courses overview cache for better performance';

    /**
     * Execute the console command.
     */
    public function handle(CoursesOverviewService $coursesService): int
    {
        $this->info('🚀 Starting courses cache warm-up...');

        try {
            // Show current cache stats if requested
            if ($this->option('stats')) {
                $this->showCacheStats($coursesService);
            }

            // Clear cache if requested
            if ($this->option('clear')) {
                $this->info('🧹 Clearing existing cache...');
                $coursesService->clearCache();
                $this->info('✅ Cache cleared successfully');
            }

            // Warm up cache
            $this->info('🔥 Warming up cache...');
            $startTime = microtime(true);
            
            $data = $coursesService->warmUpCache();
            
            $endTime = microtime(true);
            $executionTime = round(($endTime - $startTime) * 1000, 2);

            // Show results
            $this->info("✅ Cache warmed up successfully in {$executionTime}ms");
            $this->info("📊 Cached {$data->count()} course categories");

            // Show updated cache stats
            if ($this->option('stats')) {
                $this->line('');
                $this->info('📈 Updated cache statistics:');
                $this->showCacheStats($coursesService);
            }

            // Show critical images
            $criticalImages = $coursesService->getCriticalImages();
            if (!empty($criticalImages)) {
                $this->info("🖼️  Found {" . count($criticalImages) . "} critical images for preloading");
            }

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('❌ Failed to warm up cache: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * Show cache statistics
     */
    private function showCacheStats(CoursesOverviewService $coursesService): void
    {
        $stats = $coursesService->getCacheStats();
        
        $this->table(
            ['Metric', 'Value'],
            [
                ['Has Cache', $stats['has_cache'] ? '✅ Yes' : '❌ No'],
                ['Cache Size (KB)', $stats['cache_size_kb']],
                ['TTL (seconds)', $stats['ttl_seconds']],
            ]
        );
    }
}
