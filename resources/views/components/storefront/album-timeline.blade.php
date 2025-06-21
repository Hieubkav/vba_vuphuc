
@php
    // Lấy dữ liệu albums từ ViewServiceProvider hoặc tạo collection rỗng
    $albums = $albums ?? collect();
    $isLoading = !isset($albums) || $albums === null;
@endphp

@if($albums->isNotEmpty())
<div class="max-w-6xl mx-auto px-3 sm:px-4 lg:px-6">
    <!-- Timeline Container -->
    <div class="relative max-w-5xl mx-auto">
        <!-- Timeline Line - Desktop -->
        <div class="absolute left-1/2 transform -translate-x-1/2 w-px bg-red-200 h-full hidden lg:block"></div>

        <!-- Timeline Line - Mobile -->
        <div class="absolute left-4 top-0 w-px bg-red-200 h-full lg:hidden"></div>

        <!-- Timeline Items -->
        <div class="space-y-6 sm:space-y-8 lg:space-y-10">
            @foreach($albums as $index => $album)
            @php
                $isEven = $index % 2 === 0;
                $animationDelay = $index * 0.3;
            @endphp

            <!-- Timeline Item - Responsive Layout -->
            <div class="relative grid lg:grid-cols-2 gap-4 sm:gap-6 lg:gap-8 items-center timeline-item pl-8 sm:pl-10 lg:pl-0">

                <!-- Timeline Dot - Desktop -->
                <div class="absolute left-1/2 transform -translate-x-1/2 w-2.5 h-2.5 bg-red-500 rounded-full z-20 hidden lg:block timeline-dot"></div>

                <!-- Timeline Dot - Mobile -->
                <div class="absolute left-3 transform -translate-x-1/2 w-2 h-2 bg-red-500 rounded-full z-20 lg:hidden timeline-dot"></div>

                <!-- Album Content Side -->
                <div class="{{ $isEven ? 'lg:order-1' : 'lg:order-2' }}">
                    <div class="timeline-content bg-white border border-red-100 hover:border-red-200 hover:shadow-md transition-all duration-300 group p-3 sm:p-4 lg:p-5 rounded-lg">

                        <!-- Date Badge & Order - Responsive -->
                        <div class="mb-2 flex items-center gap-2 flex-wrap">
                            <span class="inline-block px-2 py-1 bg-red-50 text-red-600 text-xs font-medium rounded">
                                @php
                                    if ($album->published_date) {
                                        $months = [
                                            1 => 'Tháng 1', 2 => 'Tháng 2', 3 => 'Tháng 3', 4 => 'Tháng 4',
                                            5 => 'Tháng 5', 6 => 'Tháng 6', 7 => 'Tháng 7', 8 => 'Tháng 8',
                                            9 => 'Tháng 9', 10 => 'Tháng 10', 11 => 'Tháng 11', 12 => 'Tháng 12'
                                        ];
                                        $month = $months[$album->published_date->month];
                                        $year = $album->published_date->year;
                                        echo "$month $year";
                                    } else {
                                        echo 'Chưa xuất bản';
                                    }
                                @endphp
                            </span>
                        </div>

                        <!-- Album Title -->
                        <h3 class="text-base sm:text-lg lg:text-xl font-light text-gray-900 mb-2 leading-snug">
                            {{ $album->title }}
                        </h3>

                        <!-- Description -->
                        <p class="text-gray-600 mb-2 leading-relaxed font-light text-sm">
                            {{ Str::limit($album->description, 80) }}
                        </p>
                    </div>
                </div>

                <!-- Images Side - Carousel -->
                <div class="{{ $isEven ? 'lg:order-2' : 'lg:order-1' }}">
                    @if($album->images && $album->images->count() > 0)
                        <!-- Carousel Container -->
                        <div class="relative group" id="carousel-{{ $album->id }}">
                            <!-- Main Carousel -->
                            <div class="aspect-[4/3] overflow-hidden bg-gray-50 border border-red-100 rounded-lg relative">
                                <div class="carousel-track flex transition-transform duration-500 ease-in-out h-full" data-album="{{ $album->id }}">
                                    @foreach($album->images as $index => $image)
                                        <div class="carousel-slide w-full flex-shrink-0 relative">
                                            <img src="{{ $image->image_url }}"
                                                 alt="{{ $image->alt_text ?? $album->title }}"
                                                 class="w-full h-full object-cover cursor-pointer"
                                                 loading="lazy"
                                                 onclick="window.open('{{ $image->image_url }}', '_blank')"
                                                 onerror="handleImageError(this)">
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Navigation Arrows -->
                                @if($album->images->count() > 1)
                                    <button class="carousel-prev absolute left-2 top-1/2 -translate-y-1/2 w-8 h-8 bg-white/80 hover:bg-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 shadow-md z-10"
                                            onclick="prevSlide({{ $album->id }})">
                                        <i class="fas fa-chevron-left text-gray-600 text-sm"></i>
                                    </button>
                                    <button class="carousel-next absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 bg-white/80 hover:bg-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 shadow-md z-10"
                                            onclick="nextSlide({{ $album->id }})">
                                        <i class="fas fa-chevron-right text-gray-600 text-sm"></i>
                                    </button>
                                @endif

                                <!-- Dots Indicator -->
                                @if($album->images->count() > 1)
                                    <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex space-x-1.5">
                                        @foreach($album->images as $index => $image)
                                            <button class="carousel-dot w-2 h-2 rounded-full transition-all duration-300 {{ $index === 0 ? 'bg-white' : 'bg-white/50' }}"
                                                    onclick="goToSlide({{ $album->id }}, {{ $index }})"></button>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Image Counter -->
                                <div class="absolute top-2 right-2 bg-black/50 backdrop-blur-sm px-2 py-1 text-xs text-white font-medium rounded-full">
                                    <span class="current-slide">1</span>/<span class="total-slides">{{ $album->images->count() }}</span>
                                </div>
                            </div>

                            <!-- View Album Button - Tinh tế ở góc -->
                            <button onclick="openAlbumGallery({{ $album->id }})"
                                    class="absolute bottom-2 left-2 bg-white/90 hover:bg-white backdrop-blur-sm px-2 py-1 text-xs text-gray-700 hover:text-red-600 font-medium rounded-full transition-all duration-300 shadow-sm hover:shadow-md opacity-0 group-hover:opacity-100">
                                <i class="fas fa-images mr-1"></i>Xem album
                            </button>
                        </div>
                    @else
                        <!-- Fallback when no images -->
                        <div class="aspect-[4/3] bg-red-50 border border-red-100 flex items-center justify-center rounded-lg">
                            <div class="text-center">
                                <i class="fas fa-images text-red-300 text-3xl mb-2"></i>
                                <p class="text-red-400 font-light text-sm">Chưa có hình ảnh</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>




@else
<!-- Empty State - Minimalist -->
<div class="max-w-5xl mx-auto px-3 sm:px-4 lg:px-6">
    <!-- Header Section - Minimalist -->
    <div class="text-center mb-12">
        <div class="w-12 h-1 bg-gray-300 mx-auto mb-6"></div>
        <h2 class="text-2xl md:text-3xl font-light text-gray-900 mb-3 tracking-wide">
            Timeline
        </h2>
        <p class="text-gray-500 max-w-md mx-auto font-light text-sm">
            Hành trình học tập qua thời gian
        </p>
    </div>

    <!-- Empty Timeline -->
    <div class="relative max-w-4xl mx-auto">
        <!-- Timeline Line - Desktop -->
        <div class="absolute left-1/2 transform -translate-x-1/2 w-px bg-gray-200 h-64 hidden lg:block"></div>

        <!-- Timeline Line - Mobile -->
        <div class="absolute left-4 top-0 w-px bg-gray-200 h-64 lg:hidden"></div>

        <!-- Empty State Content -->
        <div class="relative grid lg:grid-cols-2 gap-8 items-center pl-10 lg:pl-0">
            <!-- Timeline Dot - Desktop -->
            <div class="absolute left-1/2 transform -translate-x-1/2 w-2.5 h-2.5 bg-gray-300 rounded-full z-20 hidden lg:block"></div>

            <!-- Timeline Dot - Mobile -->
            <div class="absolute left-3 transform -translate-x-1/2 w-2.5 h-2.5 bg-gray-300 rounded-full z-20 lg:hidden"></div>

            <!-- Empty Content -->
            <div class="lg:order-1">
                <div class="bg-white border border-gray-100 p-5 rounded-lg">
                    <div class="mb-4">
                        <span class="inline-block px-2 py-1 bg-gray-50 text-gray-500 text-xs font-medium rounded">
                            Chưa có dữ liệu
                        </span>
                    </div>

                    <h3 class="text-lg font-light text-gray-900 mb-3 leading-snug">
                        Timeline trống
                    </h3>

                    <p class="text-gray-600 mb-4 leading-relaxed font-light text-sm">
                        Các album khóa học sẽ sớm được thêm vào timeline này.
                    </p>
                </div>
            </div>

            <!-- Empty Image -->
            <div class="lg:order-2">
                <div class="aspect-[4/3] bg-gray-50 border border-gray-100 flex items-center justify-center rounded-lg">
                    <div class="text-center">
                        <i class="fas fa-images text-gray-300 text-4xl mb-3"></i>
                        <p class="text-gray-400 font-light text-sm">Chưa có hình ảnh</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Call to action - Minimalist -->
        <div class="text-center mt-12">
            <div class="flex justify-center space-x-6 text-sm">
                <a href="{{ route('courses.index') }}"
                   class="text-red-500 hover:text-red-600 font-medium transition-colors">
                    Khám phá khóa học
                </a>
                <a href="{{ route('posts.index') }}"
                   class="text-gray-500 hover:text-gray-600 font-medium transition-colors">
                    Đọc tin tức
                </a>
            </div>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
// Carousel functionality - Fixed version
const carouselStates = {};
const carouselIntervals = {};

// Debug function
function debugCarousel(albumId, message) {
    console.log(`[Carousel ${albumId}] ${message}`);
}

function initCarousel(albumId) {
    if (!carouselStates[albumId]) {
        const slides = document.querySelectorAll(`#carousel-${albumId} .carousel-slide`);
        carouselStates[albumId] = {
            currentSlide: 0,
            totalSlides: slides.length
        };
        debugCarousel(albumId, `Initialized with ${slides.length} slides`);
    }
}

function updateCarousel(albumId) {
    const track = document.querySelector(`#carousel-${albumId} .carousel-track`);
    const dots = document.querySelectorAll(`#carousel-${albumId} .carousel-dot`);
    const counter = document.querySelector(`#carousel-${albumId} .current-slide`);

    if (!track) {
        debugCarousel(albumId, 'Track not found!');
        return;
    }

    const state = carouselStates[albumId];
    if (!state) {
        debugCarousel(albumId, 'State not found!');
        return;
    }

    const translateX = -state.currentSlide * 100;
    track.style.transform = `translateX(${translateX}%)`;

    debugCarousel(albumId, `Moving to slide ${state.currentSlide + 1}/${state.totalSlides} (${translateX}%)`);

    // Update dots
    dots.forEach((dot, index) => {
        if (index === state.currentSlide) {
            dot.classList.remove('bg-white/50');
            dot.classList.add('bg-white');
        } else {
            dot.classList.remove('bg-white');
            dot.classList.add('bg-white/50');
        }
    });

    // Update counter
    if (counter) {
        counter.textContent = state.currentSlide + 1;
    }
}

function nextSlide(albumId) {
    debugCarousel(albumId, 'Next slide clicked');
    initCarousel(albumId);
    const state = carouselStates[albumId];
    if (state.totalSlides <= 1) return;

    state.currentSlide = (state.currentSlide + 1) % state.totalSlides;
    updateCarousel(albumId);
}

function prevSlide(albumId) {
    debugCarousel(albumId, 'Previous slide clicked');
    initCarousel(albumId);
    const state = carouselStates[albumId];
    if (state.totalSlides <= 1) return;

    state.currentSlide = state.currentSlide === 0 ? state.totalSlides - 1 : state.currentSlide - 1;
    updateCarousel(albumId);
}

function goToSlide(albumId, slideIndex) {
    debugCarousel(albumId, `Go to slide ${slideIndex + 1}`);
    initCarousel(albumId);
    const state = carouselStates[albumId];
    if (slideIndex >= 0 && slideIndex < state.totalSlides) {
        state.currentSlide = slideIndex;
        updateCarousel(albumId);
    }
}

function openAlbumGallery(albumId) {
    const images = document.querySelectorAll(`#carousel-${albumId} .carousel-slide img`);
    if (images.length > 0) {
        const currentIndex = carouselStates[albumId]?.currentSlide || 0;
        window.open(images[currentIndex].src, '_blank');
    }
}

// Auto-play carousel - Simplified
function startAutoPlay(albumId, interval = 3000) {
    const carousel = document.querySelector(`#carousel-${albumId}`);
    if (!carousel) return;

    const state = carouselStates[albumId];
    if (!state || state.totalSlides <= 1) return;

    debugCarousel(albumId, `Starting auto-play with ${interval}ms interval`);

    // Clear existing interval
    if (carouselIntervals[albumId]) {
        clearInterval(carouselIntervals[albumId]);
    }

    carouselIntervals[albumId] = setInterval(() => {
        // Skip if hovering
        if (carousel.matches(':hover')) return;

        // Skip if tab hidden
        if (document.hidden) return;

        nextSlide(albumId);
    }, interval);
}

function stopAutoPlay(albumId) {
    if (carouselIntervals[albumId]) {
        clearInterval(carouselIntervals[albumId]);
        delete carouselIntervals[albumId];
        debugCarousel(albumId, 'Auto-play stopped');
    }
}

// Initialize all carousels
function initAllCarousels() {
    debugCarousel('ALL', 'Initializing all carousels...');

    document.querySelectorAll('[id^="carousel-"]').forEach(carousel => {
        const albumId = parseInt(carousel.id.replace('carousel-', ''));

        // Initialize carousel
        initCarousel(albumId);
        updateCarousel(albumId);

        // Start auto-play
        startAutoPlay(albumId, 3000);

        // Add hover events
        carousel.addEventListener('mouseenter', () => stopAutoPlay(albumId));
        carousel.addEventListener('mouseleave', () => startAutoPlay(albumId, 3000));
    });
}

// Wait for DOM and images to load
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAllCarousels);
} else {
    initAllCarousels();
}

// Also try after a short delay to ensure images are loaded
setTimeout(initAllCarousels, 1000);

// Handle image errors
function handleImageError(img) {
    img.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjE1MCIgdmlld0JveD0iMCAwIDIwMCAxNTAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIyMDAiIGhlaWdodD0iMTUwIiBmaWxsPSIjRjNGNEY2Ii8+CjxwYXRoIGQ9Ik04NyA2NUw5MyA3MUwxMDUgNTlMMTIzIDc3SDE3VjEyM0gxNzdWNzdMMTIzIDc3WiIgZmlsbD0iI0Q1RDlERCIvPgo8Y2lyY2xlIGN4PSI3NyIgY3k9IjU5IiByPSI4IiBmaWxsPSIjRDVEOUREIi8+Cjx0ZXh0IHg9IjEwMCIgeT0iOTAiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGZpbGw9IiM5Q0EzQUYiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxMiI+S2jDtG5nIHThuqNpIMSRxrDhu6NjIGjDrG5oPC90ZXh0Pgo8L3N2Zz4K';
    img.alt = 'Không tải được hình';
}

// Global functions for debugging
window.debugCarousel = debugCarousel;
window.carouselStates = carouselStates;
</script>
@endpush

{{-- KISS: Không cần global handler phức tạp --}}

<style>
/* Carousel Styles - Fixed */
.carousel-track {
    will-change: transform;
    transition: transform 0.5s ease-in-out;
    display: flex;
    height: 100%;
}

.carousel-slide {
    min-width: 100%;
    width: 100%;
    flex-shrink: 0;
    position: relative;
}

.carousel-slide img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.carousel-dot {
    transition: all 0.3s ease;
    cursor: pointer;
    border: none;
    outline: none;
}

.carousel-dot:hover {
    transform: scale(1.2);
}

.carousel-dot:focus {
    outline: 2px solid rgba(239, 68, 68, 0.5);
    outline-offset: 2px;
}

/* Navigation buttons */
.carousel-prev,
.carousel-next {
    border: none;
    outline: none;
    cursor: pointer;
}

.carousel-prev:focus,
.carousel-next:focus {
    outline: 2px solid rgba(239, 68, 68, 0.5);
    outline-offset: 2px;
}

/* Timeline Optimizations */
.timeline-item {
    transition: all 0.3s ease;
}

/* Responsive spacing optimizations */
@media (max-width: 640px) {
    .timeline-item {
        padding-left: 2rem;
    }

    .timeline-dot {
        left: 0.5rem;
    }

    /* Tối ưu spacing cho mobile */
    .space-y-6 > * + * {
        margin-top: 1rem; /* 16px */
    }
}

@media (max-width: 480px) {
    .timeline-item {
        padding-left: 1.75rem;
    }

    .timeline-dot {
        left: 0.375rem;
    }

    /* Spacing tối thiểu cho mobile nhỏ */
    .space-y-6 > * + * {
        margin-top: 0.75rem; /* 12px */
    }

    /* Giảm padding trong content */
    .timeline-content {
        padding: 0.75rem; /* 12px */
    }
}

/* Hover effects */
.group:hover .carousel-prev,
.group:hover .carousel-next {
    opacity: 1 !important;
}

/* Smooth transitions */
* {
    scroll-behavior: smooth;
}

/* Debug styles - Remove in production */
.carousel-track {
    border: 1px solid transparent; /* For debugging */
}
</style>
