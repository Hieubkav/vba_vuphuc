@props([
    'src' => '',
    'alt' => '',
    'class' => '',
    'width' => null,
    'height' => null,
    'lazy' => true,
    'priority' => false,
    'responsive' => true,
    'blur' => true,
    'aspectRatio' => null,
    'sizes' => null,
    'fallbackIcon' => 'fas fa-image',
    'fallbackType' => 'default',
    'enableSkeleton' => true,
    'skeletonHeight' => '200px'
])

@php
    use App\Services\ImageOptimizationService;
    use App\Services\LazyLoadService;
    
    $imageOptimizationService = app(ImageOptimizationService::class);
    $lazyLoadService = app(LazyLoadService::class);
    
    // Xử lý đường dẫn ảnh
    $imagePath = $src;
    if (str_starts_with($src, asset('storage/'))) {
        $imagePath = str_replace(asset('storage/'), '', $src);
    } elseif (str_starts_with($src, 'storage/')) {
        $imagePath = str_replace('storage/', '', $src);
    }
    
    // Kiểm tra ảnh tồn tại
    $imageExists = !empty($imagePath) && \Storage::exists('public/' . $imagePath);
    
    // Tạo attributes cho lazy loading
    $lazyOptions = [
        'alt' => $alt,
        'class' => $class,
        'sizes' => $responsive ? ($sizes ?? [320, 480, 768, 1024, 1200]) : null,
        'blur' => $blur,
        'priority' => $priority
    ];
    
    $imageAttributes = $imageExists 
        ? $lazyLoadService->generateLazyAttributes($imagePath, $lazyOptions)
        : [];
    
    // Thêm width/height nếu có
    if ($width) $imageAttributes['width'] = $width;
    if ($height) $imageAttributes['height'] = $height;
    
    // Thêm aspect ratio class
    $aspectRatioClass = '';
    if ($aspectRatio) {
        $aspectRatioClass = match($aspectRatio) {
            '16:9' => 'aspect-[16/9]',
            '4:3' => 'aspect-[4/3]',
            '1:1' => 'aspect-square',
            '3:2' => 'aspect-[3/2]',
            '21:9' => 'aspect-[21/9]',
            default => $aspectRatio
        };
    }
    
    $imageAttributes['class'] = trim(($imageAttributes['class'] ?? '') . ' ' . $aspectRatioClass);
    
    // Skeleton attributes
    $skeletonAttributes = $enableSkeleton ? $lazyLoadService->generateSkeletonAttributes([
        'height' => $skeletonHeight,
        'borderRadius' => '8px'
    ]) : [];
@endphp

<div class="smart-image-container relative overflow-hidden {{ $aspectRatio ? $aspectRatioClass : '' }}">
    @if($imageExists)
        {{-- Skeleton loading placeholder --}}
        @if($enableSkeleton && !$priority)
            <div {!! collect($skeletonAttributes)->map(fn($value, $key) => $key.'="'.$value.'"')->implode(' ') !!}
                 data-skeleton="true"></div>
        @endif
        
        {{-- Main image --}}
        <img {!! collect($imageAttributes)->map(fn($value, $key) => $key.'="'.$value.'"')->implode(' ') !!}
             data-smart-image="true"
             onload="handleSmartImageLoad(this)"
             onerror="handleSmartImageError(this)">
        
        {{-- Loading indicator --}}
        @if(!$priority)
            <div class="absolute inset-0 flex items-center justify-center bg-gray-100 smart-image-loading"
                 data-loading="true">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-red-600"></div>
            </div>
        @endif
        
    @else
        {{-- Fallback UI khi không có ảnh - chỉ icon --}}
        <div class="flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200 text-gray-400 {{ $aspectRatio ? $aspectRatioClass : 'h-48' }} {{ $class }}">
            <i class="{{ $fallbackIcon }} text-4xl"></i>
        </div>
    @endif
</div>

@once
    @push('styles')
    <style>
        .smart-image-container {
            position: relative;
        }
        
        .lazy-image {
            transition: opacity 0.3s ease, filter 0.3s ease;
        }
        
        .blur-placeholder {
            filter: blur(5px);
            transform: scale(1.1);
        }
        
        .lazy-image.loaded {
            filter: none;
            transform: scale(1);
        }
        
        .smart-image-loading {
            transition: opacity 0.3s ease;
        }
        
        .smart-image-loading.hidden {
            opacity: 0;
            pointer-events: none;
        }
        
        .skeleton-image {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }
        
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        
        /* Responsive aspect ratios */
        .aspect-ratio-16-9 { aspect-ratio: 16/9; }
        .aspect-ratio-4-3 { aspect-ratio: 4/3; }
        .aspect-ratio-1-1 { aspect-ratio: 1/1; }
        .aspect-ratio-3-2 { aspect-ratio: 3/2; }
        .aspect-ratio-21-9 { aspect-ratio: 21/9; }
    </style>
    @endpush

    @push('scripts')
    <script>
        // Smart image loading handler
        function handleSmartImageLoad(img) {
            // Remove blur effect
            img.classList.remove('blur-placeholder');
            img.classList.add('loaded');
            
            // Hide loading indicator
            const loadingIndicator = img.parentElement.querySelector('[data-loading="true"]');
            if (loadingIndicator) {
                loadingIndicator.classList.add('hidden');
            }
            
            // Hide skeleton
            const skeleton = img.parentElement.querySelector('[data-skeleton="true"]');
            if (skeleton) {
                skeleton.style.display = 'none';
            }
        }
        
        function handleSmartImageError(img) {
            // Hide loading indicator
            const loadingIndicator = img.parentElement.querySelector('[data-loading="true"]');
            if (loadingIndicator) {
                loadingIndicator.classList.add('hidden');
            }
            
            // Show fallback
            img.style.display = 'none';
            const container = img.parentElement;
            container.innerHTML = `
                <div class="flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200 text-gray-400 h-full">
                    <i class="fas fa-image text-4xl"></i>
                </div>
            `;
        }
        
        // Initialize lazy loading when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            initSmartImageLazyLoading();
        });
        
        function initSmartImageLazyLoading() {
            if ('IntersectionObserver' in window) {
                const imageObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            loadSmartImage(img);
                            observer.unobserve(img);
                        }
                    });
                }, {
                    rootMargin: '50px 0px',
                    threshold: 0.01
                });
                
                document.querySelectorAll('img[data-src]').forEach(img => {
                    imageObserver.observe(img);
                });
            } else {
                // Fallback for browsers without IntersectionObserver
                document.querySelectorAll('img[data-src]').forEach(loadSmartImage);
            }
        }
        
        function loadSmartImage(img) {
            if (img.dataset.src) {
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                
                if (img.dataset.srcset) {
                    img.srcset = img.dataset.srcset;
                    img.removeAttribute('data-srcset');
                }
            }
        }
    </script>
    @endpush
@endonce
