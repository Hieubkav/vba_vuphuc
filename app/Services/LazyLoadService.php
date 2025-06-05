<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class LazyLoadService
{
    protected $imageOptimizationService;
    
    public function __construct(ImageOptimizationService $imageOptimizationService)
    {
        $this->imageOptimizationService = $imageOptimizationService;
    }

    /**
     * Tạo attributes cho lazy loading image - Enhanced version
     */
    public function generateLazyAttributes(string $imagePath, array $options = []): array
    {
        $alt = $options['alt'] ?? '';
        $class = $options['class'] ?? '';
        $sizes = $options['sizes'] ?? null;
        $enableBlur = $options['blur'] ?? true;
        $priority = $options['priority'] ?? false; // Above fold images
        $imageType = $options['type'] ?? 'default'; // course, news, partner, etc.

        // Build class với type-specific classes
        $lazyClass = $priority ? '' : 'lazy-loading';
        if ($imageType !== 'default') {
            $lazyClass .= ' ' . $imageType . '-image';
        }

        $attributes = [
            'alt' => $alt,
            'class' => trim($class . ' ' . $lazyClass),
            'decoding' => 'async',
            'onerror' => "if(window.storefrontLazyLoader) { window.storefrontLazyLoader.showFallback(this); }",
        ];

        // Priority images (above fold) load immediately
        if ($priority) {
            $attributes['src'] = $this->getImageUrl($imagePath);
            $attributes['loading'] = 'eager';

            // Add srcset for responsive
            if ($sizes && $this->imageOptimizationService) {
                $attributes['srcset'] = $this->imageOptimizationService->generateSrcSet($imagePath, $sizes);
                $attributes['sizes'] = $this->imageOptimizationService->generateSizes();
            }
        } else {
            // Lazy load với placeholder
            $attributes['data-src'] = $this->getImageUrl($imagePath);
            $attributes['loading'] = 'lazy';

            // Add responsive srcset
            if ($sizes && $this->imageOptimizationService) {
                $attributes['data-srcset'] = $this->imageOptimizationService->generateSrcSet($imagePath, $sizes);
                $attributes['sizes'] = $this->imageOptimizationService->generateSizes();
            }

            // Style cho lazy loading
            $attributes['style'] = $this->getLazyStyle($enableBlur);

            // Placeholder src
            $attributes['src'] = $this->getPlaceholderDataUrl();
        }

        return $attributes;
    }

    /**
     * Lấy URL ảnh an toàn
     */
    protected function getImageUrl(string $imagePath): string
    {
        if (empty($imagePath)) {
            return $this->getPlaceholderDataUrl();
        }

        // Nếu đã là URL đầy đủ
        if (str_starts_with($imagePath, 'http://') ||
            str_starts_with($imagePath, 'https://') ||
            str_starts_with($imagePath, 'data:')) {
            return $imagePath;
        }

        // Nếu đã có asset prefix
        if (str_starts_with($imagePath, asset('storage'))) {
            return $imagePath;
        }

        return asset('storage/' . ltrim($imagePath, '/'));
    }

    /**
     * Tạo style cho lazy loading
     */
    protected function getLazyStyle(bool $enableBlur): string
    {
        $styles = ['opacity: 0', 'transition: opacity 0.3s ease, filter 0.3s ease, transform 0.3s ease'];

        if ($enableBlur) {
            $styles[] = 'filter: blur(5px)';
            $styles[] = 'transform: scale(1.05)';
        }

        return implode('; ', $styles) . ';';
    }

    /**
     * Tạo placeholder data URL
     */
    protected function getPlaceholderDataUrl(): string
    {
        return 'data:image/svg+xml;base64,' . base64_encode(
            '<svg width="1" height="1" xmlns="http://www.w3.org/2000/svg"><rect width="1" height="1" fill="transparent"/></svg>'
        );
    }

    /**
     * Tạo lazy loading cho background images
     */
    public function generateLazyBackgroundAttributes(string $imagePath, array $options = []): array
    {
        $class = $options['class'] ?? '';
        $enableBlur = $options['blur'] ?? true;
        
        $attributes = [
            'class' => trim('lazy-bg ' . $class),
            'data-bg' => asset('storage/' . $imagePath),
        ];
        
        if ($enableBlur) {
            $blurPlaceholder = $this->imageOptimizationService->generateBlurPlaceholder($imagePath);
            $attributes['style'] = "background-image: url('{$blurPlaceholder}');";
            $attributes['class'] .= ' blur-placeholder-bg';
        }
        
        return $attributes;
    }

    /**
     * Tạo intersection observer config với timeout settings
     */
    public function getIntersectionObserverConfig(): array
    {
        return [
            'rootMargin' => '50px 0px 100px 0px', // Load 50px before entering viewport
            'threshold' => 0.01,
            'enableBlur' => true,
            'fadeInDuration' => 300,
            'retryAttempts' => 3,
            'retryDelay' => 1000,
            'loadingTimeout' => 5000, // 5 giây timeout cho mỗi ảnh
            'fastFailTimeout' => 2000, // 2 giây cho lần thử đầu tiên
            'maxConcurrentLoads' => 6, // Tối đa 6 ảnh load cùng lúc
            'timeoutMessages' => [
                'timeout' => 'Tải chậm',
                'no-src' => 'Không có ảnh',
                'stuck-loading' => 'Lỗi tải',
                'error' => 'Lỗi ảnh'
            ]
        ];
    }

    /**
     * Tạo preload hints cho critical images
     */
    public function generatePreloadHints(array $criticalImages): string
    {
        $preloadHints = [];
        
        foreach ($criticalImages as $image) {
            $imagePath = $image['path'] ?? '';
            $as = $image['as'] ?? 'image';
            $type = $image['type'] ?? 'image/webp';
            
            if ($imagePath) {
                $url = asset('storage/' . $imagePath);
                $preloadHints[] = "<link rel=\"preload\" as=\"{$as}\" href=\"{$url}\" type=\"{$type}\">";
            }
        }
        
        return implode("\n", $preloadHints);
    }

    /**
     * Tạo progressive loading cho galleries
     */
    public function generateProgressiveGalleryAttributes(array $images, array $options = []): array
    {
        $batchSize = $options['batchSize'] ?? 6; // Load 6 images at a time
        $enableThumbnails = $options['thumbnails'] ?? true;
        
        $batches = array_chunk($images, $batchSize);
        $galleryData = [];
        
        foreach ($batches as $index => $batch) {
            $batchImages = [];
            
            foreach ($batch as $image) {
                $imageData = [
                    'src' => asset('storage/' . $image['path']),
                    'alt' => $image['alt'] ?? '',
                    'lazy' => $index > 0, // First batch loads immediately
                ];
                
                // Add thumbnail for quick preview
                if ($enableThumbnails) {
                    $imageData['thumbnail'] = $this->imageOptimizationService->generateBlurPlaceholder($image['path'], 40, 30);
                }
                
                // Add responsive srcset
                if (isset($image['sizes'])) {
                    $imageData['srcset'] = $this->imageOptimizationService->generateSrcSet($image['path'], $image['sizes']);
                    $imageData['sizes'] = $this->imageOptimizationService->generateSizes();
                }
                
                $batchImages[] = $imageData;
            }
            
            $galleryData[] = [
                'batch' => $index,
                'images' => $batchImages,
                'loadOnScroll' => $index > 0,
            ];
        }
        
        return $galleryData;
    }

    /**
     * Tạo adaptive loading dựa trên connection speed
     */
    public function generateAdaptiveLoadingConfig(): array
    {
        return [
            'connectionTypes' => [
                'slow-2g' => [
                    'quality' => 60,
                    'maxWidth' => 480,
                    'enableBlur' => false,
                    'batchSize' => 2,
                ],
                '2g' => [
                    'quality' => 70,
                    'maxWidth' => 768,
                    'enableBlur' => true,
                    'batchSize' => 3,
                ],
                '3g' => [
                    'quality' => 80,
                    'maxWidth' => 1024,
                    'enableBlur' => true,
                    'batchSize' => 4,
                ],
                '4g' => [
                    'quality' => 90,
                    'maxWidth' => 1920,
                    'enableBlur' => true,
                    'batchSize' => 6,
                ],
            ],
            'fallback' => [
                'quality' => 85,
                'maxWidth' => 1200,
                'enableBlur' => true,
                'batchSize' => 4,
            ]
        ];
    }

    /**
     * Simple placeholder cho trường hợp không dùng blur
     */
    protected function getSimplePlaceholder(): string
    {
        return 'data:image/svg+xml;base64,' . base64_encode(
            '<svg width="400" height="300" xmlns="http://www.w3.org/2000/svg">
                <rect width="100%" height="100%" fill="#f3f4f6"/>
                <circle cx="200" cy="150" r="20" fill="#d1d5db"/>
                <text x="200" y="180" text-anchor="middle" fill="#9ca3af" font-family="Arial" font-size="12">Đang tải...</text>
            </svg>'
        );
    }

    /**
     * Tạo skeleton loading cho image cards
     */
    public function generateSkeletonAttributes(array $options = []): array
    {
        $width = $options['width'] ?? '100%';
        $height = $options['height'] ?? '200px';
        $borderRadius = $options['borderRadius'] ?? '8px';
        
        return [
            'class' => 'skeleton-image animate-pulse',
            'style' => "width: {$width}; height: {$height}; border-radius: {$borderRadius}; background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%); background-size: 200% 100%; animation: shimmer 1.5s infinite;",
        ];
    }

    /**
     * Cache lazy loading configuration
     */
    public function cacheLazyConfig(string $key, array $config, int $ttl = 3600): void
    {
        Cache::put('lazy_config_' . $key, $config, $ttl);
    }

    /**
     * Get cached lazy loading configuration
     */
    public function getCachedLazyConfig(string $key, array $default = []): array
    {
        return Cache::get('lazy_config_' . $key, $default);
    }
}
