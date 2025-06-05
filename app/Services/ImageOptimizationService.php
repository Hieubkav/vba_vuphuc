<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageOptimizationService
{
    protected $manager;
    protected $defaultQuality = 85;
    protected $webpQuality = 90;
    protected $breakpoints = [320, 480, 768, 1024, 1200, 1920];
    
    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Tạo responsive images với nhiều kích thước
     */
    public function generateResponsiveImages(string $imagePath, array $sizes = null): array
    {
        $sizes = $sizes ?? $this->breakpoints;
        $cacheKey = 'responsive_images_' . md5($imagePath . serialize($sizes));
        
        return Cache::remember($cacheKey, 3600, function () use ($imagePath, $sizes) {
            $responsiveImages = [];
            
            if (!Storage::exists('public/' . $imagePath)) {
                return $responsiveImages;
            }
            
            $originalPath = storage_path('app/public/' . $imagePath);
            $pathInfo = pathinfo($imagePath);
            $directory = $pathInfo['dirname'];
            $filename = $pathInfo['filename'];
            
            foreach ($sizes as $width) {
                $optimizedFilename = $filename . '_' . $width . 'w.webp';
                $optimizedPath = $directory . '/' . $optimizedFilename;
                $fullOptimizedPath = storage_path('app/public/' . $optimizedPath);
                
                // Tạo ảnh tối ưu nếu chưa tồn tại
                if (!Storage::exists('public/' . $optimizedPath)) {
                    try {
                        $image = $this->manager->read($originalPath);
                        
                        // Resize với giữ tỷ lệ
                        $image->scaleDown(width: $width);
                        
                        // Tối ưu và lưu dưới dạng WebP
                        $encoded = $image->toWebp($this->webpQuality);
                        Storage::put('public/' . $optimizedPath, $encoded);
                        
                        $responsiveImages[$width] = [
                            'url' => asset('storage/' . $optimizedPath),
                            'width' => $width,
                            'format' => 'webp'
                        ];
                    } catch (\Exception $e) {
                        \Log::error('Error generating responsive image: ' . $e->getMessage());
                        continue;
                    }
                } else {
                    $responsiveImages[$width] = [
                        'url' => asset('storage/' . $optimizedPath),
                        'width' => $width,
                        'format' => 'webp'
                    ];
                }
            }
            
            return $responsiveImages;
        });
    }

    /**
     * Tạo srcset string cho responsive images
     */
    public function generateSrcSet(string $imagePath, array $sizes = null): string
    {
        $responsiveImages = $this->generateResponsiveImages($imagePath, $sizes);
        
        $srcset = [];
        foreach ($responsiveImages as $width => $imageData) {
            $srcset[] = $imageData['url'] . ' ' . $width . 'w';
        }
        
        return implode(', ', $srcset);
    }

    /**
     * Tạo sizes attribute thông minh
     */
    public function generateSizes(array $breakpoints = null): string
    {
        $breakpoints = $breakpoints ?? [
            '(max-width: 320px)' => '100vw',
            '(max-width: 480px)' => '100vw', 
            '(max-width: 768px)' => '50vw',
            '(max-width: 1024px)' => '33vw',
            '(max-width: 1200px)' => '25vw',
            'default' => '20vw'
        ];
        
        $sizes = [];
        foreach ($breakpoints as $condition => $size) {
            if ($condition === 'default') {
                $sizes[] = $size;
            } else {
                $sizes[] = $condition . ' ' . $size;
            }
        }
        
        return implode(', ', $sizes);
    }

    /**
     * Tạo placeholder blur base64
     */
    public function generateBlurPlaceholder(string $imagePath, int $width = 20, int $height = 20): string
    {
        $cacheKey = 'blur_placeholder_' . md5($imagePath . $width . $height);
        
        return Cache::remember($cacheKey, 86400, function () use ($imagePath, $width, $height) {
            try {
                if (!Storage::exists('public/' . $imagePath)) {
                    return $this->getDefaultPlaceholder();
                }
                
                $originalPath = storage_path('app/public/' . $imagePath);
                $image = $this->manager->read($originalPath);
                
                // Resize xuống rất nhỏ và blur
                $image->resize($width, $height);
                $image->blur(5);
                
                // Convert to base64
                $encoded = $image->toJpeg(60);
                return 'data:image/jpeg;base64,' . base64_encode($encoded);
                
            } catch (\Exception $e) {
                return $this->getDefaultPlaceholder();
            }
        });
    }

    /**
     * Placeholder mặc định
     */
    protected function getDefaultPlaceholder(): string
    {
        // Tạo placeholder gradient đơn giản
        return 'data:image/svg+xml;base64,' . base64_encode(
            '<svg width="400" height="300" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="grad" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#f3f4f6;stop-opacity:1" />
                        <stop offset="100%" style="stop-color:#e5e7eb;stop-opacity:1" />
                    </linearGradient>
                </defs>
                <rect width="100%" height="100%" fill="url(#grad)"/>
                <text x="50%" y="50%" text-anchor="middle" dy=".3em" fill="#9ca3af" font-family="Arial" font-size="14">Đang tải...</text>
            </svg>'
        );
    }

    /**
     * Kiểm tra hỗ trợ WebP
     */
    public function supportsWebP(): bool
    {
        return function_exists('imagewebp');
    }

    /**
     * Tối ưu ảnh hiện có
     */
    public function optimizeExistingImage(string $imagePath, array $options = []): bool
    {
        $quality = $options['quality'] ?? $this->defaultQuality;
        $generateResponsive = $options['responsive'] ?? true;
        
        try {
            if (!Storage::exists('public/' . $imagePath)) {
                return false;
            }
            
            $originalPath = storage_path('app/public/' . $imagePath);
            $image = $this->manager->read($originalPath);
            
            // Tối ưu ảnh gốc
            $pathInfo = pathinfo($imagePath);
            $optimizedPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '_optimized.webp';
            
            $encoded = $image->toWebp($quality);
            Storage::put('public/' . $optimizedPath, $encoded);
            
            // Tạo responsive images nếu cần
            if ($generateResponsive) {
                $this->generateResponsiveImages($imagePath);
            }
            
            return true;
            
        } catch (\Exception $e) {
            \Log::error('Error optimizing image: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Xóa cache responsive images
     */
    public function clearImageCache(string $imagePath = null): void
    {
        if ($imagePath) {
            $cacheKey = 'responsive_images_' . md5($imagePath . serialize($this->breakpoints));
            Cache::forget($cacheKey);
            
            $blurCacheKey = 'blur_placeholder_' . md5($imagePath . '20' . '20');
            Cache::forget($blurCacheKey);
        } else {
            // Clear all image cache
            Cache::flush();
        }
    }
}
