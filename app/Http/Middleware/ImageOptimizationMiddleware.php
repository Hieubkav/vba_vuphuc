<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\ImageOptimizationService;
use App\Services\LazyLoadService;

class ImageOptimizationMiddleware
{
    protected $imageOptimizationService;
    protected $lazyLoadService;
    
    public function __construct(
        ImageOptimizationService $imageOptimizationService,
        LazyLoadService $lazyLoadService
    ) {
        $this->imageOptimizationService = $imageOptimizationService;
        $this->lazyLoadService = $lazyLoadService;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        // Chỉ xử lý HTML responses
        if (!$this->shouldOptimize($request, $response)) {
            return $response;
        }
        
        $content = $response->getContent();
        
        // Tối ưu hóa images trong HTML
        $optimizedContent = $this->optimizeImagesInHtml($content, $request);
        
        // Thêm preload hints cho critical resources
        $optimizedContent = $this->addPreloadHints($optimizedContent, $request);
        
        // Thêm performance hints
        $optimizedContent = $this->addPerformanceHints($optimizedContent);
        
        $response->setContent($optimizedContent);
        
        return $response;
    }
    
    protected function shouldOptimize(Request $request, $response): bool
    {
        // Chỉ tối ưu cho GET requests
        if (!$request->isMethod('GET')) {
            return false;
        }
        
        // Chỉ tối ưu cho HTML responses
        $contentType = $response->headers->get('Content-Type', '');
        if (!str_contains($contentType, 'text/html')) {
            return false;
        }
        
        // Bỏ qua admin routes
        if ($request->is('admin/*') || $request->is('filament/*')) {
            return false;
        }
        
        // Bỏ qua AJAX requests
        if ($request->ajax()) {
            return false;
        }
        
        return true;
    }
    
    protected function optimizeImagesInHtml(string $content, Request $request): string
    {
        // Tối ưu img tags
        $content = preg_replace_callback(
            '/<img([^>]+)>/i',
            function ($matches) use ($request) {
                return $this->optimizeImageTag($matches[0], $request);
            },
            $content
        );
        
        // Tối ưu background images trong style attributes
        $content = preg_replace_callback(
            '/style\s*=\s*["\']([^"\']*background-image\s*:\s*url\([^)]+\)[^"\']*)["\']/',
            function ($matches) {
                return $this->optimizeBackgroundImage($matches[0]);
            },
            $content
        );
        
        return $content;
    }
    
    protected function optimizeImageTag(string $imgTag, Request $request): string
    {
        // Parse img attributes
        preg_match_all('/(\w+)\s*=\s*["\']([^"\']*)["\']/', $imgTag, $matches, PREG_SET_ORDER);
        
        $attributes = [];
        foreach ($matches as $match) {
            $attributes[$match[1]] = $match[2];
        }
        
        $src = $attributes['src'] ?? '';
        $alt = $attributes['alt'] ?? '';
        $class = $attributes['class'] ?? '';
        
        // Chỉ tối ưu images từ storage
        if (!str_contains($src, '/storage/')) {
            return $imgTag;
        }
        
        // Kiểm tra nếu đã được tối ưu hoặc là hero banner hoặc có onload/onerror handlers
        if (str_contains($class, 'lazy-image') ||
            str_contains($class, 'smart-image') ||
            str_contains($class, 'hero-image-main') ||
            str_contains($imgTag, 'hero-image-main') ||
            str_contains($imgTag, 'onload=') ||
            str_contains($imgTag, 'onerror=')) {
            return $imgTag;
        }
        
        // Xác định priority (above fold)
        $priority = $this->isAboveFold($imgTag, $request);
        
        // Extract image path
        $imagePath = str_replace(asset('storage/'), '', $src);
        
        // Generate lazy attributes
        $lazyAttributes = $this->lazyLoadService->generateLazyAttributes($imagePath, [
            'alt' => $alt,
            'class' => $class,
            'priority' => $priority,
            'blur' => !$priority,
            'sizes' => [320, 480, 768, 1024, 1200]
        ]);
        
        // Rebuild img tag
        $newImgTag = '<img';
        foreach ($lazyAttributes as $attr => $value) {
            $newImgTag .= ' ' . $attr . '="' . htmlspecialchars($value) . '"';
        }
        
        // Preserve other attributes
        foreach ($attributes as $attr => $value) {
            if (!isset($lazyAttributes[$attr]) && !in_array($attr, ['src', 'alt', 'class'])) {
                $newImgTag .= ' ' . $attr . '="' . htmlspecialchars($value) . '"';
            }
        }
        
        $newImgTag .= '>';
        
        return $newImgTag;
    }
    
    protected function optimizeBackgroundImage(string $styleAttr): string
    {
        // Extract background-image URL
        preg_match('/background-image\s*:\s*url\(["\']?([^"\']+)["\']?\)/', $styleAttr, $matches);
        
        if (!isset($matches[1])) {
            return $styleAttr;
        }
        
        $imageUrl = $matches[1];
        
        // Chỉ tối ưu images từ storage
        if (!str_contains($imageUrl, '/storage/')) {
            return $styleAttr;
        }
        
        // Convert to data-bg attribute for lazy loading
        $imagePath = str_replace(asset('storage/'), '', $imageUrl);
        
        return str_replace(
            $matches[0],
            '',
            $styleAttr
        ) . '" data-bg="' . asset('storage/' . $imagePath);
    }
    
    protected function isAboveFold(string $imgTag, Request $request): bool
    {
        // Heuristics để xác định ảnh above fold
        $aboveFoldIndicators = [
            'hero',
            'banner',
            'logo',
            'header',
            'featured',
            'main-image'
        ];
        
        foreach ($aboveFoldIndicators as $indicator) {
            if (str_contains(strtolower($imgTag), $indicator)) {
                return true;
            }
        }
        
        return false;
    }
    
    protected function addPreloadHints(string $content, Request $request): string
    {
        // Tìm critical images để preload
        $criticalImages = $this->findCriticalImages($content);
        
        if (empty($criticalImages)) {
            return $content;
        }
        
        $preloadHints = $this->lazyLoadService->generatePreloadHints($criticalImages);
        
        // Inject vào head
        $content = str_replace(
            '</head>',
            $preloadHints . "\n</head>",
            $content
        );
        
        return $content;
    }
    
    protected function findCriticalImages(string $content): array
    {
        $criticalImages = [];
        
        // Tìm hero/banner images
        preg_match_all('/<img[^>]+class="[^"]*(?:hero|banner|featured)[^"]*"[^>]+src="([^"]+)"[^>]*>/i', $content, $matches);
        
        foreach ($matches[1] as $src) {
            if (str_contains($src, '/storage/')) {
                $criticalImages[] = [
                    'path' => str_replace(asset('storage/'), '', $src),
                    'as' => 'image',
                    'type' => 'image/webp'
                ];
            }
        }
        
        return array_slice($criticalImages, 0, 3); // Limit to 3 critical images
    }
    
    protected function addPerformanceHints(string $content): string
    {
        $hints = [
            '<link rel="dns-prefetch" href="//fonts.googleapis.com">',
            '<link rel="dns-prefetch" href="//cdn.jsdelivr.net">',
            '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>',
        ];
        
        $hintsHtml = implode("\n", $hints);
        
        // Inject vào head
        $content = str_replace(
            '<head>',
            "<head>\n" . $hintsHtml,
            $content
        );
        
        return $content;
    }
}
