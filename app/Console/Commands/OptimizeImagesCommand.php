<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Services\ImageOptimizationService;
use App\Services\LazyLoadService;

class OptimizeImagesCommand extends Command
{
    protected $signature = 'images:optimize 
                            {--path= : Specific path to optimize}
                            {--force : Force re-optimization of existing images}
                            {--clear-cache : Clear image cache before optimization}
                            {--batch-size=50 : Number of images to process in each batch}
                            {--quality=85 : Image quality (1-100)}';

    protected $description = 'Tối ưu hóa tất cả hình ảnh trong storage để tăng tốc độ website';

    protected $imageOptimizationService;
    protected $lazyLoadService;

    public function __construct(
        ImageOptimizationService $imageOptimizationService,
        LazyLoadService $lazyLoadService
    ) {
        parent::__construct();
        $this->imageOptimizationService = $imageOptimizationService;
        $this->lazyLoadService = $lazyLoadService;
    }

    public function handle()
    {
        $this->info('🚀 Bắt đầu tối ưu hóa hình ảnh...');
        
        // Clear cache if requested
        if ($this->option('clear-cache')) {
            $this->info('🧹 Xóa cache hình ảnh...');
            $this->imageOptimizationService->clearImageCache();
        }

        $path = $this->option('path') ?: 'public';
        $force = $this->option('force');
        $batchSize = (int) $this->option('batch-size');
        $quality = (int) $this->option('quality');

        // Get all images
        $images = $this->getImageFiles($path);
        
        if (empty($images)) {
            $this->warn('❌ Không tìm thấy hình ảnh nào để tối ưu.');
            return;
        }

        $this->info("📊 Tìm thấy " . count($images) . " hình ảnh cần tối ưu.");

        // Process in batches
        $batches = array_chunk($images, $batchSize);
        $totalBatches = count($batches);
        $processedCount = 0;
        $optimizedCount = 0;
        $errorCount = 0;

        foreach ($batches as $batchIndex => $batch) {
            $this->info("📦 Xử lý batch " . ($batchIndex + 1) . "/{$totalBatches}...");
            
            $progressBar = $this->output->createProgressBar(count($batch));
            $progressBar->start();

            foreach ($batch as $imagePath) {
                try {
                    $relativePath = str_replace('public/', '', $imagePath);
                    
                    // Check if already optimized
                    if (!$force && $this->isAlreadyOptimized($relativePath)) {
                        $processedCount++;
                        $progressBar->advance();
                        continue;
                    }

                    // Optimize image
                    $result = $this->imageOptimizationService->optimizeExistingImage($relativePath, [
                        'quality' => $quality,
                        'responsive' => true
                    ]);

                    if ($result) {
                        $optimizedCount++;
                        
                        // Generate responsive images
                        $this->imageOptimizationService->generateResponsiveImages($relativePath);
                        
                        // Generate blur placeholder
                        $this->imageOptimizationService->generateBlurPlaceholder($relativePath);
                    } else {
                        $errorCount++;
                    }

                    $processedCount++;

                } catch (\Exception $e) {
                    $this->error("❌ Lỗi tối ưu {$imagePath}: " . $e->getMessage());
                    $errorCount++;
                }

                $progressBar->advance();
            }

            $progressBar->finish();
            $this->newLine();

            // Memory cleanup
            if (function_exists('gc_collect_cycles')) {
                gc_collect_cycles();
            }
        }

        // Generate optimization report
        $this->generateOptimizationReport($processedCount, $optimizedCount, $errorCount);
        
        // Update performance cache
        $this->updatePerformanceCache();

        $this->info('✅ Hoàn thành tối ưu hóa hình ảnh!');
    }

    protected function getImageFiles(string $path): array
    {
        $extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $images = [];

        // Get files in current directory
        $files = Storage::files($path);
        foreach ($files as $file) {
            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (in_array($extension, $extensions)) {
                $images[] = $file;
            }
        }

        // Get files from subdirectories recursively
        $directories = Storage::directories($path);
        foreach ($directories as $directory) {
            $subFiles = $this->getImageFiles($directory);
            $images = array_merge($images, $subFiles);
        }

        return array_unique($images);
    }

    protected function isAlreadyOptimized(string $imagePath): bool
    {
        $pathInfo = pathinfo($imagePath);
        $optimizedPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '_optimized.webp';
        
        return Storage::exists('public/' . $optimizedPath);
    }

    protected function generateOptimizationReport(int $processed, int $optimized, int $errors): void
    {
        $this->newLine();
        $this->info('📈 BÁO CÁO TỐI ƯU HÓA:');
        $this->table(
            ['Thống kê', 'Số lượng'],
            [
                ['Tổng số ảnh xử lý', $processed],
                ['Ảnh được tối ưu', $optimized],
                ['Lỗi xảy ra', $errors],
                ['Tỷ lệ thành công', $processed > 0 ? round(($optimized / $processed) * 100, 2) . '%' : '0%'],
            ]
        );

        // Calculate estimated space savings
        $estimatedSavings = $optimized * 0.3; // Estimate 30% size reduction
        $this->info("💾 Ước tính tiết kiệm dung lượng: ~{$estimatedSavings}MB");
    }

    protected function updatePerformanceCache(): void
    {
        $this->info('🔄 Cập nhật performance cache...');
        
        // Cache optimization config
        $config = [
            'last_optimization' => now(),
            'total_optimized_images' => $this->getOptimizedImageCount(),
            'optimization_settings' => [
                'quality' => $this->option('quality'),
                'responsive_enabled' => true,
                'blur_placeholder_enabled' => true,
            ]
        ];

        $this->lazyLoadService->cacheLazyConfig('optimization_status', $config, 86400);
    }

    protected function getOptimizedImageCount(): int
    {
        $optimizedImages = Storage::files('public');
        return count(array_filter($optimizedImages, function ($file) {
            return str_contains($file, '_optimized.webp') || str_contains($file, '_320w.webp');
        }));
    }
}
