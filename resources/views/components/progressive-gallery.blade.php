@props([
    'images' => [],
    'batchSize' => 6,
    'enableThumbnails' => true,
    'aspectRatio' => '4:3',
    'class' => '',
    'columns' => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3',
    'gap' => 'gap-4',
    'lightbox' => true,
    'enableInfiniteScroll' => true
])

@php
    use App\Services\LazyLoadService;
    
    $lazyLoadService = app(LazyLoadService::class);
    
    // Tạo progressive gallery data
    $galleryData = $lazyLoadService->generateProgressiveGalleryAttributes($images, [
        'batchSize' => $batchSize,
        'thumbnails' => $enableThumbnails
    ]);
    
    $aspectRatioClass = match($aspectRatio) {
        '16:9' => 'aspect-[16/9]',
        '4:3' => 'aspect-[4/3]',
        '1:1' => 'aspect-square',
        '3:2' => 'aspect-[3/2]',
        '21:9' => 'aspect-[21/9]',
        default => $aspectRatio
    };
@endphp

<div class="progressive-gallery {{ $class }}" 
     data-lightbox="{{ $lightbox ? 'true' : 'false' }}"
     data-infinite-scroll="{{ $enableInfiniteScroll ? 'true' : 'false' }}">
     
    <div class="grid {{ $columns }} {{ $gap }}">
        @foreach($galleryData as $batchIndex => $batch)
            @foreach($batch['images'] as $imageIndex => $image)
                <div class="gallery-item relative group overflow-hidden rounded-lg bg-gray-100 {{ $aspectRatioClass }}"
                     data-batch="{{ $batch['batch'] }}"
                     data-load-on-scroll="{{ $batch['loadOnScroll'] ? 'true' : 'false' }}"
                     data-image-index="{{ $imageIndex }}">
                     
                    {{-- Thumbnail placeholder --}}
                    @if($enableThumbnails && isset($image['thumbnail']))
                        <div class="absolute inset-0 bg-cover bg-center filter blur-sm scale-110 transition-all duration-300"
                             style="background-image: url('{{ $image['thumbnail'] }}');"
                             data-thumbnail="true"></div>
                    @endif
                    
                    {{-- Main image --}}
                    <img class="w-full h-full object-cover transition-all duration-300 group-hover:scale-105 {{ $image['lazy'] ? 'opacity-0' : '' }}"
                         @if($image['lazy'])
                             data-src="{{ $image['src'] }}"
                             @if(isset($image['srcset']))
                                 data-srcset="{{ $image['srcset'] }}"
                                 sizes="{{ $image['sizes'] ?? '(max-width: 768px) 100vw, (max-width: 1024px) 50vw, 33vw' }}"
                             @endif
                         @else
                             src="{{ $image['src'] }}"
                             @if(isset($image['srcset']))
                                 srcset="{{ $image['srcset'] }}"
                                 sizes="{{ $image['sizes'] ?? '(max-width: 768px) 100vw, (max-width: 1024px) 50vw, 33vw' }}"
                             @endif
                         @endif
                         alt="{{ $image['alt'] }}"
                         loading="{{ $image['lazy'] ? 'lazy' : 'eager' }}"
                         onload="handleGalleryImageLoad(this)"
                         onerror="handleGalleryImageError(this)">
                    
                    {{-- Loading overlay --}}
                    @if($image['lazy'])
                        <div class="absolute inset-0 flex items-center justify-center bg-gray-200 bg-opacity-75 gallery-loading"
                             data-loading="true">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-red-600"></div>
                        </div>
                    @endif
                    
                    {{-- Lightbox trigger --}}
                    @if($lightbox)
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300 cursor-pointer flex items-center justify-center"
                             onclick="openLightbox({{ $imageIndex }}, this.closest('.progressive-gallery'))">
                            <i class="fas fa-expand text-white text-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></i>
                        </div>
                    @endif
                    
                    {{-- Image info overlay --}}
                    @if(!empty($image['alt']))
                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-4 transform translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                            <p class="text-white text-sm font-medium">{{ $image['alt'] }}</p>
                        </div>
                    @endif
                </div>
            @endforeach
        @endforeach
    </div>
    
    {{-- Load more trigger for infinite scroll --}}
    @if($enableInfiniteScroll && count($galleryData) > 1)
        <div class="load-more-trigger mt-8 text-center" data-load-more="true">
            <div class="inline-flex items-center justify-center space-x-2 text-gray-500">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-red-600"></div>
                <span>Đang tải thêm ảnh...</span>
            </div>
        </div>
    @endif
</div>

{{-- Lightbox Modal --}}
@if($lightbox)
    <div id="gallery-lightbox" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden flex items-center justify-center p-4">
        <div class="relative max-w-7xl max-h-full">
            {{-- Close button --}}
            <button class="absolute top-4 right-4 text-white text-3xl z-10 hover:text-gray-300 transition-colors"
                    onclick="closeLightbox()">
                <i class="fas fa-times"></i>
            </button>
            
            {{-- Navigation buttons --}}
            <button class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white text-3xl z-10 hover:text-gray-300 transition-colors"
                    onclick="previousImage()" id="prev-btn">
                <i class="fas fa-chevron-left"></i>
            </button>
            
            <button class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white text-3xl z-10 hover:text-gray-300 transition-colors"
                    onclick="nextImage()" id="next-btn">
                <i class="fas fa-chevron-right"></i>
            </button>
            
            {{-- Main image --}}
            <img id="lightbox-image" class="max-w-full max-h-full object-contain" src="" alt="">
            
            {{-- Image counter --}}
            <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 text-white text-sm bg-black bg-opacity-50 px-3 py-1 rounded">
                <span id="current-image">1</span> / <span id="total-images">{{ count($images) }}</span>
            </div>
        </div>
    </div>
@endif

@once
    @push('styles')
    <style>
        .gallery-item {
            position: relative;
            overflow: hidden;
        }
        
        .gallery-loading {
            transition: opacity 0.3s ease;
        }
        
        .gallery-loading.hidden {
            opacity: 0;
            pointer-events: none;
        }
        
        .progressive-gallery img {
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
        
        .progressive-gallery img.loaded {
            opacity: 1 !important;
        }
        
        #gallery-lightbox {
            backdrop-filter: blur(5px);
        }
        
        #gallery-lightbox.show {
            display: flex !important;
        }
        
        .load-more-trigger {
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .load-more-trigger.visible {
            opacity: 1;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        let currentLightboxIndex = 0;
        let lightboxImages = [];
        let currentGallery = null;
        
        function handleGalleryImageLoad(img) {
            // Hide loading overlay
            const loadingOverlay = img.parentElement.querySelector('[data-loading="true"]');
            if (loadingOverlay) {
                loadingOverlay.classList.add('hidden');
            }
            
            // Hide thumbnail
            const thumbnail = img.parentElement.querySelector('[data-thumbnail="true"]');
            if (thumbnail) {
                thumbnail.style.opacity = '0';
            }
            
            // Show main image
            img.classList.add('loaded');
        }
        
        function handleGalleryImageError(img) {
            const loadingOverlay = img.parentElement.querySelector('[data-loading="true"]');
            if (loadingOverlay) {
                loadingOverlay.classList.add('hidden');
            }
            
            // Show error state
            img.style.display = 'none';
            const errorDiv = document.createElement('div');
            errorDiv.className = 'absolute inset-0 flex items-center justify-center bg-gray-200 text-gray-500';
            errorDiv.innerHTML = '<i class="fas fa-exclamation-triangle text-2xl"></i>';
            img.parentElement.appendChild(errorDiv);
        }
        
        function openLightbox(index, gallery) {
            currentGallery = gallery;
            lightboxImages = Array.from(gallery.querySelectorAll('img[src], img[data-src]'));
            currentLightboxIndex = index;
            
            const lightbox = document.getElementById('gallery-lightbox');
            const lightboxImage = document.getElementById('lightbox-image');
            
            updateLightboxImage();
            lightbox.classList.add('show');
            
            // Update counters
            document.getElementById('current-image').textContent = index + 1;
            document.getElementById('total-images').textContent = lightboxImages.length;
        }
        
        function closeLightbox() {
            const lightbox = document.getElementById('gallery-lightbox');
            lightbox.classList.remove('show');
        }
        
        function previousImage() {
            currentLightboxIndex = (currentLightboxIndex - 1 + lightboxImages.length) % lightboxImages.length;
            updateLightboxImage();
        }
        
        function nextImage() {
            currentLightboxIndex = (currentLightboxIndex + 1) % lightboxImages.length;
            updateLightboxImage();
        }
        
        function updateLightboxImage() {
            const img = lightboxImages[currentLightboxIndex];
            const lightboxImage = document.getElementById('lightbox-image');
            
            lightboxImage.src = img.src || img.dataset.src;
            lightboxImage.alt = img.alt;
            
            document.getElementById('current-image').textContent = currentLightboxIndex + 1;
        }
        
        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            const lightbox = document.getElementById('gallery-lightbox');
            if (lightbox.classList.contains('show')) {
                switch(e.key) {
                    case 'Escape':
                        closeLightbox();
                        break;
                    case 'ArrowLeft':
                        previousImage();
                        break;
                    case 'ArrowRight':
                        nextImage();
                        break;
                }
            }
        });
        
        // Close lightbox on background click
        document.getElementById('gallery-lightbox')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeLightbox();
            }
        });
    </script>
    @endpush
@endonce
