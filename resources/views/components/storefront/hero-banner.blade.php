@php
    $activeSliders = isset($sliders) && !empty($sliders) ? $sliders : \App\Models\Slider::where('status', 'active')->orderBy('order')->get();
@endphp

@if($activeSliders->count() > 0)
<section class="relative overflow-hidden w-full">
    <div id="hero-slider" class="relative w-full">
        <!-- Slider Container -->
        <div class="slider-container w-full overflow-hidden relative">

            <!-- Slides -->
            @forelse($activeSliders as $index => $slider)
                <div class="slide w-full transition-opacity duration-1000 ease-in-out {{ $index === 0 ? 'relative' : 'absolute inset-0' }}"
                     data-slide="{{ $index }}"
                     style="{{ $index === 0 ? 'opacity: 1; z-index: 20;' : 'opacity: 0; z-index: 10;' }}">

                    <!-- Minimal overlay - chỉ để text readability -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/10 via-transparent to-transparent z-10"></div>

                    <!-- Link overlay -->
                    @if($slider->link)
                        <a href="{{ $slider->link }}" class="absolute top-4 right-4 z-30 p-2 bg-white/20 backdrop-blur-sm rounded-full text-white hover:bg-white/30 transition-colors duration-300 shadow-lg" aria-label="Xem chi tiết">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                        </a>
                    @endif

                    <!-- Image -->
                    @if($slider->image_link)
                        <div class="w-full h-full image-container">
                            <img src="{{ asset('storage/' . $slider->image_link) }}"
                                 alt="{{ $slider->alt_text ?: $slider->title . ' - VBA Vũ Phúc' }}"
                                 class="w-full h-auto object-contain mobile-image"
                                 loading="eager" {{-- Thay đổi để tránh lỗi lazy loading --}}
                                 fetchpriority="high" {{-- Thêm để ưu tiên tải ảnh --}}
                                 onerror="console.log('Image failed to load:', this.src); this.style.display='none'; this.parentElement.innerHTML='<div class=\'w-full h-full bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center\'><span class=\'text-white text-lg font-medium\'>{{ addslashes($slider->title ?? 'VBA Vũ Phúc') }}</span></div>';">
                        </div>
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center">
                            <span class="text-white text-lg font-medium">{{ $slider->title ?? 'VBA Vũ Phúc' }}</span>
                        </div>
                    @endif

                    <!-- Text Content -->
                    @if($slider->title || $slider->description)
                        <div class="absolute inset-0 z-20 flex flex-col justify-end p-6 sm:p-8">
                            <!-- Compact backdrop container - tự động điều chỉnh kích thước theo nội dung -->
                            <div class="compact-backdrop inline-block relative">
                                <!-- Compact backdrop - chỉ bao quanh text area -->
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/35 to-black/10 rounded-lg backdrop-blur-sm"></div>

                                <!-- Text content container -->
                                <div class="relative p-3 sm:p-4">
                                    @if($slider->title)
                                        <h2 class="text-white text-lg sm:text-xl font-bold mb-1 text-shadow-strong leading-tight">{{ $slider->title }}</h2>
                                    @endif
                                    @if($slider->description)
                                        <p class="text-white text-xs sm:text-sm mb-2 text-shadow-medium leading-snug">{{ $slider->description }}</p>
                                    @endif
                                    @if($slider->link)
                                        <a href="{{ $slider->link }}" class="inline-flex items-center bg-red-600/90 hover:bg-red-700 text-white px-2 py-1 text-xs rounded transition-all duration-300 shadow-lg border border-red-500/30 hover:shadow-xl hover:scale-105">
                                            <span class="font-medium">Xem chi tiết</span>
                                            <svg class="h-3 w-3 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @empty
                <div class="slide relative bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center" data-slide="0" style="opacity: 1; z-index: 20;">
                    <div class="absolute inset-0 bg-gradient-to-b from-black/10 via-transparent to-black/20 z-10"></div>
                    <div class="absolute inset-0 z-20 flex flex-col justify-center items-center text-center p-6 sm:p-8">
                        <!-- Compact backdrop cho fallback content -->
                        <div class="compact-backdrop inline-block relative">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/35 to-black/10 rounded-lg backdrop-blur-sm"></div>
                            <div class="relative p-4 sm:p-6 text-center">
                                <h2 class="text-white text-xl sm:text-2xl font-bold mb-2 text-shadow-strong">VBA Vũ Phúc</h2>
                                <p class="text-white text-sm sm:text-base mb-3 text-shadow-medium leading-snug">Khóa học chuyên nghiệp & Dịch vụ đào tạo chất lượng cao</p>
                                <a href="{{ route('courses.index') ?? '#' }}" class="inline-flex items-center bg-red-600/90 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-all duration-300 shadow-lg border border-red-500/30 hover:shadow-xl hover:scale-105">
                                    <span class="font-medium">Khám phá ngay</span>
                                    <svg class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforelse

        </div>

        @if($activeSliders->count() > 1)
            <!-- Navigation Arrows -->
            <div class="absolute inset-x-0 top-1/2 transform -translate-y-1/2 flex items-center justify-between px-4 md:px-6 z-30">
                <button id="prev-btn" class="p-2 sm:p-3 rounded-full bg-white/20 backdrop-blur-sm text-white hover:bg-white/30 focus:outline-none transition-colors duration-300 shadow-lg" aria-label="Slide trước">
                    <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <button id="next-btn" class="p-2 sm:p-3 rounded-full bg-white/20 backdrop-blur-sm text-white hover:bg-white/30 focus:outline-none transition-colors duration-300 shadow-lg" aria-label="Slide tiếp theo">
                    <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>

            <!-- Dots Navigation -->
            <div class="absolute bottom-4 sm:bottom-6 left-0 right-0 z-30">
                <div id="dots-container" class="flex items-center justify-center gap-2 sm:gap-3">
                    @foreach($activeSliders as $index => $slider)
                        <button class="dot w-3 h-3 sm:w-4 sm:h-4 rounded-full transition-all duration-300 focus:outline-none relative overflow-hidden shadow-lg {{ $index === 0 ? 'bg-white w-8 sm:w-10' : 'bg-white/50' }}"
                                data-slide="{{ $index }}"
                                aria-label="Đi đến slide {{ $index + 1 }}">
                            <span class="absolute left-0 top-0 h-full bg-white transition-all duration-300 {{ $index === 0 ? 'w-full' : 'w-0' }}"></span>
                        </button>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>

<style>
/* Mobile Image Optimization với fixed height */
@media (max-width: 767px) {
    .mobile-image {
        object-fit: contain !important; /* Đảm bảo không cắt ảnh */
        object-position: center;
        width: 100% !important;
        height: auto !important; /* Để ảnh giữ tỷ lệ */
        max-height: 100% !important; /* Giới hạn trong container */
        max-width: 100% !important;
        display: block;
    }

    /* Slide container với fixed height */
    #hero-slider .slider-container {
        /* Height sẽ được set bởi JavaScript */
    }

    /* Slides với fixed height và vertical centering */
    #hero-slider .slide {
        /* Height và display properties sẽ được set bởi JavaScript */
        overflow: hidden; /* Đảm bảo không bị tràn */
    }

    /* Image container với vertical centering */
    #hero-slider .image-container {
        /* Height và display properties sẽ được set bởi JavaScript */
        position: relative;
        width: 100%;
    }

    /* Đảm bảo slide active hiển thị đúng */
    #hero-slider .slide[style*="opacity: 1"] {
        position: relative !important;
        z-index: 20 !important;
    }

    /* Đảm bảo slides ẩn không ảnh hưởng layout */
    #hero-slider .slide[style*="opacity: 0"] {
        position: absolute !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        z-index: 10 !important;
    }
}

/* Tablet styles */
@media (min-width: 768px) and (max-width: 1023px) {
    .mobile-image {
        object-fit: contain;
        max-height: 70vh;
        height: auto;
        width: 100%;
    }
}

/* Desktop Image Optimization */
@media (min-width: 1024px) {
    .mobile-image {
        object-fit: contain; /* Thay đổi từ cover sang contain để không cắt ảnh */
        object-position: center;
        width: 100%;
        height: auto; /* Thay đổi từ 100% sang auto để giữ tỷ lệ khung hình */
        max-width: 100%; /* Đảm bảo ảnh không vượt quá chiều rộng container */
    }

    /* Image container */
    .image-container {
        height: auto; /* Thay đổi từ 100% sang auto để tự điều chỉnh theo chiều cao ảnh */
        position: relative;
        width: 100%;
        display: flex; /* Thêm flex để căn giữa ảnh */
        justify-content: center; /* Căn giữa theo chiều ngang */
        align-items: center; /* Căn giữa theo chiều dọc */
    }
}

/* Enhanced text shadows for better readability on compact backdrop */
.text-shadow-strong {
    text-shadow:
        0 1px 2px rgba(0, 0, 0, 0.8),
        0 2px 4px rgba(0, 0, 0, 0.6),
        0 4px 8px rgba(0, 0, 0, 0.4),
        0 8px 16px rgba(0, 0, 0, 0.2);
}

.text-shadow-medium {
    text-shadow:
        0 1px 2px rgba(0, 0, 0, 0.7),
        0 2px 4px rgba(0, 0, 0, 0.5),
        0 4px 8px rgba(0, 0, 0, 0.3);
}

/* Compact backdrop optimization */
#hero-slider .compact-backdrop {
    /* Tự động điều chỉnh kích thước theo nội dung */
    width: fit-content;
    min-width: 200px;
    max-width: 90vw;
}

/* Responsive backdrop sizing */
@media (max-width: 640px) {
    #hero-slider .compact-backdrop {
        max-width: 280px; /* Nhỏ hơn trên mobile */
    }

    /* Tối ưu padding cho mobile */
    #hero-slider .compact-backdrop .relative {
        padding: 0.75rem; /* p-3 */
    }
}

@media (min-width: 641px) and (max-width: 1023px) {
    #hero-slider .compact-backdrop {
        max-width: 384px; /* sm:max-w-sm */
    }
}

@media (min-width: 1024px) {
    #hero-slider .compact-backdrop {
        max-width: 448px; /* md:max-w-md */
    }

    /* Padding lớn hơn cho desktop */
    #hero-slider .compact-backdrop .relative {
        padding: 1rem; /* p-4 */
    }
}

/* Backdrop blur enhancement */
#hero-slider .backdrop-blur-sm {
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
}

/* Ensure backdrop doesn't interfere with touch events */
#hero-slider .compact-backdrop {
    pointer-events: auto;
}

#hero-slider .compact-backdrop > div:first-child {
    pointer-events: none; /* Background overlay không chặn touch */
}

/* Ensure full width container với performance optimization */
#hero-slider {
    width: 100vw !important;
    margin-left: calc(-50vw + 50%) !important;
    overflow: hidden;
    transform: translateZ(0);
    -webkit-transform: translateZ(0);
    backface-visibility: hidden;
    will-change: auto;
}

/* Touch optimization cho mobile */
@media (max-width: 767px) {
    #hero-slider {
        touch-action: pan-y pinch-zoom;
        -webkit-overflow-scrolling: touch;
    }

    .slider-container {
        -webkit-overflow-scrolling: touch;
        touch-action: pan-y pinch-zoom;
    }
}

/* Smooth transitions với hardware acceleration - scoped để tránh conflict */
#hero-slider .slide {
    transition: opacity 1000ms cubic-bezier(0.4, 0, 0.2, 1);
    will-change: opacity;
    backface-visibility: hidden;
    transform: translateZ(0);
    -webkit-transform: translateZ(0);
    /* Override Tailwind transitions nếu có */
    transition-property: opacity !important;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1) !important;
}

/* Tối ưu cho mobile performance */
@media (max-width: 767px) {
    #hero-slider .slide {
        transition-duration: 600ms !important;
        transition-timing-function: cubic-bezier(0.25, 0.46, 0.45, 0.94) !important;
    }
}

/* Fix cho lỗi lazy loading và tối ưu performance */
img.mobile-image {
    backface-visibility: hidden;
    transform: translateZ(0);
    -webkit-transform: translateZ(0);
    will-change: auto;
}

/* Container optimization */
#hero-slider .slider-container {
    transform: translateZ(0);
    -webkit-transform: translateZ(0);
    backface-visibility: hidden;
    will-change: auto;
    /* Tối ưu rendering */
    contain: layout style paint;
    isolation: isolate;
}

/* Performance optimizations cho mobile với fixed height */
@media (max-width: 767px) {
    #hero-slider .slider-container {
        /* Giảm complexity cho mobile */
        contain: layout paint;
        /* Tránh layout thrashing khi tính toán chiều cao */
        position: relative;
    }

    /* Tối ưu image rendering trên mobile */
    #hero-slider .mobile-image {
        image-rendering: -webkit-optimize-contrast;
        image-rendering: crisp-edges;
        /* Tránh layout shift */
        vertical-align: middle;
    }

    /* Tối ưu cho việc đo chiều cao */
    #hero-slider .slide {
        /* Đảm bảo không có margin/padding ảnh hưởng đến measurement */
        box-sizing: border-box;
    }

    /* Smooth transition cho height changes */
    #hero-slider {
        transition: height 0.3s ease-out;
    }
}
</style>

<script>
// Hero Slider JavaScript thuần với mobile optimization
const HeroSlider = {
    currentSlide: 0,
    totalSlides: {{ $activeSliders->count() }},
    interval: null,
    container: null,
    slides: [],
    dots: [],
    isTransitioning: false,
    heightCalculateTimeout: null,
    isMobile: window.innerWidth <= 767,

    init() {
        this.container = document.getElementById('hero-slider');
        if (!this.container) return;

        this.slides = this.container.querySelectorAll('.slide');
        this.dots = this.container.querySelectorAll('.dot');

        // Set initial mobile state
        this.isMobile = window.innerWidth <= 767;

        // Calculate height
        this.calculateHeight();

        // Setup navigation
        this.setupNavigation();

        // Setup touch events cho mobile
        this.setupTouchEvents();

        // Start auto-play if multiple slides
        if (this.totalSlides > 1) {
            this.startAutoPlay();
        }

        // Handle resize với debounce
        window.addEventListener('resize', () => this.debounceCalculateHeight());

        // Pause on hover
        this.container.addEventListener('mouseenter', () => this.pauseAutoPlay());
        this.container.addEventListener('mouseleave', () => this.startAutoPlay());
    },

    calculateHeight() {
        if (!this.container) return;

        // Kiểm tra nếu là mobile
        const isMobile = window.innerWidth <= 767;

        if (isMobile) {
            this.calculateFixedHeightForMobile();
        } else {
            this.calculateDynamicHeightForDesktop();
        }
    },

    calculateFixedHeightForMobile() {
        // Sử dụng requestAnimationFrame để tối ưu performance
        requestAnimationFrame(() => {
            // Reset container height để đo chính xác
            this.container.style.height = 'auto';
            const slides = this.container.querySelectorAll('.slide');

            if (slides.length === 0) return;

            // Batch DOM operations để tránh layout thrashing
            const originalStyles = [];

            // Lưu trữ styles gốc để restore sau khi đo
            slides.forEach((slide, index) => {
                originalStyles[index] = {
                    position: slide.style.position,
                    opacity: slide.style.opacity,
                    zIndex: slide.style.zIndex,
                    display: slide.style.display
                };

                // Set tất cả slides về visible để đo chính xác
                slide.style.position = 'static';
                slide.style.opacity = '1';
                slide.style.zIndex = 'auto';
                slide.style.display = 'block';
            });

            // Tìm chiều cao lớn nhất từ tất cả ảnh
            let maxHeight = 0;
            let loadedImages = 0;
            const totalImages = slides.length;

            const checkAllImagesLoaded = () => {
                loadedImages++;
                if (loadedImages >= totalImages) {
                    // Tất cả ảnh đã load, áp dụng chiều cao

                    // Restore original styles trước khi áp dụng height mới
                    slides.forEach((slide, index) => {
                        if (originalStyles[index]) {
                            slide.style.position = originalStyles[index].position;
                            slide.style.opacity = originalStyles[index].opacity;
                            slide.style.zIndex = originalStyles[index].zIndex;
                            slide.style.display = originalStyles[index].display;
                        }
                    });

                    // Áp dụng chiều cao tối ưu
                    this.applyFixedHeight(maxHeight);
                }
            };

            // Đo chiều cao của từng slide
            slides.forEach((slide, index) => {
                const img = slide.querySelector('img');
                if (img) {
                    if (img.complete && img.naturalHeight > 0) {
                        // Ảnh đã load
                        const aspectRatio = img.naturalWidth / img.naturalHeight;
                        const containerWidth = this.container.offsetWidth;
                        const calculatedHeight = containerWidth / aspectRatio;

                        // Giới hạn chiều cao tối đa là 60% viewport height
                        // và tối thiểu là 300px
                        maxHeight = Math.max(maxHeight, Math.min(calculatedHeight, window.innerHeight * 0.6));
                        maxHeight = Math.max(maxHeight, 300);

                        checkAllImagesLoaded();
                    } else {
                        // Ảnh chưa load
                        img.onload = () => {
                            const aspectRatio = img.naturalWidth / img.naturalHeight;
                            const containerWidth = this.container.offsetWidth;
                            const calculatedHeight = containerWidth / aspectRatio;

                            // Giới hạn chiều cao tối đa là 60% viewport height
                            // và tối thiểu là 300px
                            maxHeight = Math.max(maxHeight, Math.min(calculatedHeight, window.innerHeight * 0.6));
                            maxHeight = Math.max(maxHeight, 300);

                            checkAllImagesLoaded();
                        };
                        img.onerror = () => {
                            // Nếu ảnh lỗi, dùng chiều cao mặc định
                            maxHeight = Math.max(maxHeight, 300);
                            checkAllImagesLoaded();
                        };
                    }
                } else {
                    // Slide không có ảnh
                    maxHeight = Math.max(maxHeight, 300);
                    checkAllImagesLoaded();
                }
            });

            // Fallback nếu không có ảnh nào
            if (totalImages === 0) {
                maxHeight = 300;
                this.applyFixedHeight(maxHeight);
            }
        });
    },

    applyFixedHeight(maxHeight) {
        const slides = this.container.querySelectorAll('.slide');

        // Áp dụng chiều cao cố định nếu tìm được
        if (maxHeight > 0) {
            // Batch update tất cả styles để tránh layout thrashing
            requestAnimationFrame(() => {
                this.container.style.height = maxHeight + 'px';

                slides.forEach((slide, index) => {
                    const imageContainer = slide.querySelector('.image-container');

                    // Set slide properties
                    slide.style.height = maxHeight + 'px';
                    slide.style.display = 'flex';
                    slide.style.alignItems = 'center';
                    slide.style.justifyContent = 'center';

                    // Set image container properties
                    if (imageContainer) {
                        imageContainer.style.height = maxHeight + 'px';
                        imageContainer.style.display = 'flex';
                        imageContainer.style.alignItems = 'center';
                        imageContainer.style.justifyContent = 'center';
                        imageContainer.style.position = 'relative';
                        imageContainer.style.width = '100%';
                    }

                    // Set visibility và positioning
                    if (index === this.currentSlide) {
                        slide.style.position = 'relative';
                        slide.style.opacity = '1';
                        slide.style.zIndex = '20';
                    } else {
                        slide.style.position = 'absolute';
                        slide.style.top = '0';
                        slide.style.left = '0';
                        slide.style.right = '0';
                        slide.style.opacity = '0';
                        slide.style.zIndex = '10';
                    }
                });
            });
        }
    },

    calculateDynamicHeightForDesktop() {
        // Desktop sử dụng height tự động dựa trên nội dung
        this.container.style.height = 'auto';
        const slides = this.container.querySelectorAll('.slide');

        slides.forEach((slide, index) => {
            const imageContainer = slide.querySelector('.image-container');

            if (index === this.currentSlide) {
                // Slide hiện tại
                slide.style.position = 'relative';
                slide.style.height = 'auto';
                slide.style.display = 'block';
                slide.style.alignItems = 'initial';
                slide.style.justifyContent = 'initial';

                // Image container cho slide active
                if (imageContainer) {
                    imageContainer.style.height = 'auto';
                    imageContainer.style.display = 'flex';
                    imageContainer.style.justifyContent = 'center';
                    imageContainer.style.alignItems = 'center';
                }
            } else {
                // Slides ẩn
                slide.style.position = 'absolute';
                slide.style.top = '0';
                slide.style.left = '0';
                slide.style.right = '0';
                slide.style.height = '100%';

                // Image container cho slides ẩn
                if (imageContainer) {
                    imageContainer.style.height = '100%';
                    imageContainer.style.display = 'flex';
                    imageContainer.style.justifyContent = 'center';
                    imageContainer.style.alignItems = 'center';
                }
            }
        });
    },

    setupNavigation() {
        // Setup arrow navigation
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');

        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                this.prevSlide();
                this.resetAutoPlay();
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                this.nextSlide();
                this.resetAutoPlay();
            });
        }

        // Setup dots navigation
        this.dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                this.goToSlide(index);
                this.resetAutoPlay();
            });
        });
    },

    goToSlide(index) {
        if (index === this.currentSlide || this.isTransitioning) return;

        this.isTransitioning = true;

        // Hide current slide
        if (this.slides[this.currentSlide]) {
            const currentSlide = this.slides[this.currentSlide];
            currentSlide.style.opacity = '0';
            currentSlide.style.zIndex = '10';
            currentSlide.classList.remove('relative');
            currentSlide.classList.add('absolute', 'inset-0');
        }

        // Update dots
        this.updateDots(index);

        // Update current slide
        this.currentSlide = index;

        // Show new slide với delay tối ưu
        if (this.slides[this.currentSlide]) {
            const newSlide = this.slides[this.currentSlide];

            // Delay ngắn để đảm bảo smooth transition
            setTimeout(() => {
                requestAnimationFrame(() => {
                    newSlide.style.opacity = '1';
                    newSlide.style.zIndex = '20';
                    newSlide.classList.remove('absolute', 'inset-0');
                    newSlide.classList.add('relative');
                });

                // Không cần recalculate height trên mobile vì đã cố định
                // Chỉ recalculate trên desktop khi cần thiết
                if (window.innerWidth > 767) {
                    this.debounceCalculateHeight();
                }
            }, 50);
        }

        // Reset transition flag
        setTimeout(() => {
            this.isTransitioning = false;
        }, 1000);
    },

    nextSlide() {
        const nextIndex = (this.currentSlide + 1) % this.totalSlides;
        this.goToSlide(nextIndex);
    },

    prevSlide() {
        const prevIndex = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
        this.goToSlide(prevIndex);
    },

    updateDots(activeIndex) {
        this.dots.forEach((dot, index) => {
            const span = dot.querySelector('span');
            if (index === activeIndex) {
                dot.classList.remove('bg-white/50');
                dot.classList.add('bg-white', 'w-8', 'sm:w-10');
                if (span) {
                    span.classList.remove('w-0');
                    span.classList.add('w-full');
                }
            } else {
                dot.classList.remove('bg-white', 'w-8', 'sm:w-10');
                dot.classList.add('bg-white/50');
                if (span) {
                    span.classList.remove('w-full');
                    span.classList.add('w-0');
                }
            }
        });
    },

    startAutoPlay() {
        if (this.totalSlides <= 1) return;
        this.pauseAutoPlay();
        this.interval = setInterval(() => {
            this.nextSlide();
        }, 8000);
    },

    pauseAutoPlay() {
        if (this.interval) {
            clearInterval(this.interval);
            this.interval = null;
        }
    },

    resetAutoPlay() {
        this.pauseAutoPlay();
        this.startAutoPlay();
    },

    // Debounced height calculation để tránh tính toán liên tục
    debounceCalculateHeight() {
        if (this.heightCalculateTimeout) {
            clearTimeout(this.heightCalculateTimeout);
        }
        this.heightCalculateTimeout = setTimeout(() => {
            this.handleResize();
        }, 100);
    },

    // Handle resize với logic khác nhau cho mobile/desktop
    handleResize() {
        const wasMobile = this.isMobile;
        this.isMobile = window.innerWidth <= 767;

        // Nếu chuyển từ mobile sang desktop hoặc ngược lại
        if (wasMobile !== this.isMobile) {
            this.calculateHeight();
        } else if (this.isMobile) {
            // Nếu vẫn là mobile, chỉ recalculate nếu cần thiết
            this.calculateFixedHeightForMobile();
        } else {
            // Desktop - recalculate như bình thường
            this.calculateDynamicHeightForDesktop();
        }
    },

    setupTouchEvents() {
        if (!this.container) return;

        let startX = 0;
        let startY = 0;
        let isScrolling = false;

        this.container.addEventListener('touchstart', (e) => {
            if (this.isTransitioning) return;
            startX = e.touches[0].clientX;
            startY = e.touches[0].clientY;
            isScrolling = false;
        }, { passive: true });

        this.container.addEventListener('touchmove', (e) => {
            if (!startX || this.isTransitioning) return;

            const currentX = e.touches[0].clientX;
            const currentY = e.touches[0].clientY;
            const diffX = Math.abs(startX - currentX);
            const diffY = Math.abs(startY - currentY);

            // Determine if user is scrolling vertically
            if (diffY > diffX) {
                isScrolling = true;
            }
        }, { passive: true });

        this.container.addEventListener('touchend', (e) => {
            if (!startX || isScrolling || this.isTransitioning) return;

            const endX = e.changedTouches[0].clientX;
            const diffX = startX - endX;

            // Minimum swipe distance
            if (Math.abs(diffX) > 50) {
                if (diffX > 0) {
                    this.nextSlide();
                } else {
                    this.prevSlide();
                }
                this.resetAutoPlay();
            }

            startX = 0;
            startY = 0;
        }, { passive: true });
    }
};

// Initialize when DOM is ready với namespace để tránh conflict
document.addEventListener('DOMContentLoaded', () => {
    // Đảm bảo không conflict với các slider khác
    if (document.getElementById('hero-slider')) {
        HeroSlider.init();
    }
});
</script>
@endif
