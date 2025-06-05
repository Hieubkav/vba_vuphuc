@props([
    'src' => '',
    'alt' => '',
    'class' => '',
    'width' => null,
    'height' => null,
    'lazy' => true,
    'responsive' => true,
    'aspectRatio' => null,
    'placeholder' => true,
    'sizes' => [480, 768, 1024, 1200]
])

@php
    $imageAttributes = lazyImageAttributes($src, $alt, $class, $responsive ? $sizes : []);
    
    // Thêm width/height nếu có
    if ($width) $imageAttributes['width'] = $width;
    if ($height) $imageAttributes['height'] = $height;
    
    // Thêm aspect ratio class
    $aspectRatioClass = '';
    if ($aspectRatio) {
        $aspectRatioClass = match($aspectRatio) {
            '16:9' => 'aspect-ratio-16-9',
            '4:3' => 'aspect-ratio-4-3',
            '1:1' => 'aspect-ratio-1-1',
            default => ''
        };
    }
    
    $imageAttributes['class'] = trim($imageAttributes['class'] . ' ' . $aspectRatioClass);
@endphp

<div class="relative overflow-hidden {{ $aspectRatioClass }}">
    @if($placeholder)
        <!-- Skeleton placeholder -->
        <div class="absolute inset-0 skeleton" id="skeleton-{{ md5($src) }}"></div>
    @endif
    
    <img 
        @foreach($imageAttributes as $key => $value)
            {{ $key }}="{{ $value }}"
        @endforeach
        onload="
            this.classList.add('loaded');
            const skeleton = document.getElementById('skeleton-{{ md5($src) }}');
            if (skeleton) skeleton.style.display = 'none';
        "
        onerror="
            this.src = '{{ asset('images/placeholder.webp') }}';
            this.classList.add('loaded');
            const skeleton = document.getElementById('skeleton-{{ md5($src) }}');
            if (skeleton) skeleton.style.display = 'none';
        "
    />
</div>

@once
    @push('scripts')
    <script>
        // Intersection Observer for lazy loading
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                            if (img.dataset.srcset) {
                                img.srcset = img.dataset.srcset;
                            }
                            img.classList.remove('lazy');
                            observer.unobserve(img);
                        }
                    }
                });
            }, {
                rootMargin: '50px 0px',
                threshold: 0.01
            });

            // Observe all lazy images
            document.querySelectorAll('img.lazy').forEach(img => {
                imageObserver.observe(img);
            });
        } else {
            // Fallback for browsers without IntersectionObserver
            document.querySelectorAll('img.lazy').forEach(img => {
                if (img.dataset.src) {
                    img.src = img.dataset.src;
                    if (img.dataset.srcset) {
                        img.srcset = img.dataset.srcset;
                    }
                    img.classList.remove('lazy');
                    img.classList.add('loaded');
                }
            });
        }
    </script>
    @endpush
@endonce
