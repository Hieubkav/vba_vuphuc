{{--
    Hero Banner Slider Component
    Sử dụng dữ liệu từ ViewServiceProvider (model Sliders)
    Responsive design với ảnh WebP tối ưu
    Tự động ẩn nếu không có dữ liệu slider
--}}

@php
    use Illuminate\Support\Facades\Storage;
    use App\Models\Slider;

    // Sử dụng dữ liệu sliders từ ViewServiceProvider
    // Dữ liệu được preload và cached để tối ưu performance
    $activeSliders = isset($sliders) && !empty($sliders) ? $sliders : collect();

    // Fallback: Nếu không có dữ liệu từ ViewServiceProvider, query trực tiếp
    if ($activeSliders->isEmpty()) {
        $activeSliders = Slider::where('status', 'active')
            ->orderBy('order')
            ->get();
    }
@endphp

{{-- Chỉ hiển thị hero banner nếu có dữ liệu slider --}}
@if(isset($activeSliders) && $activeSliders->count() > 0)
<section class="relative overflow-hidden">
    <!-- Hero Banner Slider với dữ liệu thực từ database -->
    <div class="relative hero-carousel" x-data="{
        activeSlide: 0,
        slides: {{ $activeSliders->count() }},
        interval: null,
        init() {
            if (this.slides > 1) {
                this.interval = setInterval(() => this.nextSlide(), 8000); // Chậm lại từ 6s thành 8s
            }
        },
        nextSlide() {
            this.activeSlide = (this.activeSlide + 1) % this.slides;
        },
        prevSlide() {
            this.activeSlide = (this.activeSlide + this.slides - 1) % this.slides;
        }
    }" x-init="init()"
    @mouseenter="if(interval) { clearInterval(interval); }"
    @mouseleave="if(slides > 1) { interval = setInterval(() => nextSlide(), 8000); }">
        <!-- Mobile version (dưới md) -->
        <div class="md:hidden overflow-hidden relative h-[350px] sm:h-[450px]">
            @forelse($activeSliders as $index => $slider)
                <div
                    class="absolute inset-0 transition-all duration-1000 ease-in-out transform hero-slide"
                    x-bind:class="{
                        'opacity-100 scale-100': activeSlide === {{ $index }},
                        'opacity-0 scale-110': activeSlide !== {{ $index }}
                    }">
                    <div class="absolute inset-0 bg-gradient-to-b from-black/30 via-transparent to-black/40 z-10"></div>

                    {{-- Nút liên kết tinh tế ở góc phải trên --}}
                    @if($slider->link)
                        <a href="{{ $slider->link }}"
                           class="absolute top-4 right-4 z-30 p-2 bg-white/20 backdrop-blur-sm rounded-full text-white hover:bg-white/30 transition-all duration-300 transform hover:scale-110 shadow-lg group"
                           aria-label="Xem chi tiết {{ $slider->title ?? 'slider' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                    @endif
                    @if($slider->image_link)
                        <div class="smart-image-container w-full h-full overflow-hidden">
                            <img
                                src="{{ Storage::url($slider->image_link) }}"
                                alt="{{ $slider->alt_text ?: ($slider->title ? $slider->title . ' - Vũ Phúc Baking' : 'Banner Vũ Phúc Baking ' . ($index + 1)) }}"
                                class="smart-hero-image w-full h-full transform scale-105 transition-all duration-7000 ease-in-out"
                                x-bind:class="{ 'scale-100': activeSlide === {{ $index }}, 'scale-105': activeSlide !== {{ $index }} }"
                                loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                                onload="adjustImagePosition(this)"
                                onerror="this.style.display='none'; this.parentElement.parentElement.querySelector('.fallback-bg').style.display='flex';"
                            >
                        </div>
                        <!-- Fallback background khi ảnh không load được -->
                        <div class="fallback-bg w-full h-full bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center" style="display: none;">
                            <span class="text-white text-lg font-medium">{{ $slider->title ?? 'Vũ Phúc Baking' }}</span>
                        </div>
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center">
                            <span class="text-white text-lg font-medium">{{ $slider->title ?? 'Vũ Phúc Baking' }}</span>
                        </div>
                    @endif
                    @if($slider->title || $slider->description)
                    <div class="absolute inset-0 z-20 flex flex-col justify-end p-6 sm:p-8">
                        <div
                            class="transform transition-all duration-1000 delay-200"
                            x-bind:class="{ 'opacity-100 translate-y-0': activeSlide === {{ $index }}, 'opacity-0 translate-y-4': activeSlide !== {{ $index }} }"
                        >
                            @if($slider->title)
                                <h2 class="text-white text-xl sm:text-2xl font-bold mb-2 text-shadow-sm">{{ $slider->title }}</h2>
                            @endif
                            @if($slider->description)
                                <p class="text-white text-sm sm:text-base mb-3 max-w-md text-shadow-sm">{{ $slider->description }}</p>
                            @endif
                            @if($slider->link)
                                <a href="{{ $slider->link }}" class="inline-flex items-center bg-red-600/90 hover:bg-red-700 text-white px-3 py-1.5 text-sm rounded-md transition-all duration-300 transform hover:-translate-y-0.5 shadow-lg backdrop-blur-sm border border-red-500/30">
                                    <span class="font-medium">Xem chi tiết</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 ml-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            @empty
                {{-- Fallback nếu không có slider nào --}}
                <div class="relative h-[350px] sm:h-[450px] bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center">
                    <div class="absolute inset-0 bg-gradient-to-b from-black/20 via-transparent to-black/30 z-10"></div>
                    <div class="absolute inset-0 z-20 flex flex-col justify-center items-center text-center p-6 sm:p-8">
                        <h2 class="text-white text-xl sm:text-2xl font-bold mb-2 text-shadow-sm">Vũ Phúc Baking</h2>
                        <p class="text-white text-sm sm:text-base mb-3 max-w-md text-shadow-sm">Nguyên liệu & Dụng cụ làm bánh chất lượng cao</p>
                        <a href="{{ route('products.categories') ?? '#' }}" class="inline-flex items-center bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white px-4 py-2 rounded-lg transition transform hover:-translate-y-0.5 shadow-lg">
                            <span>Khám phá ngay</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Desktop version (md trở lên) -->
        <div class="hidden md:block overflow-hidden relative h-[550px] lg:h-[700px]">
            @forelse($activeSliders as $index => $slider)
                <div
                    class="absolute inset-0 transition-all duration-1000 ease-in-out transform hero-slide"
                    x-bind:class="{
                        'opacity-100 scale-100': activeSlide === {{ $index }},
                        'opacity-0 scale-110': activeSlide !== {{ $index }}
                    }">
                    <div class="absolute inset-0 bg-gradient-to-r from-black/50 via-black/20 to-transparent z-10"></div>

                    {{-- Nút liên kết tinh tế ở góc phải trên cho desktop --}}
                    @if($slider->link)
                        <a href="{{ $slider->link }}"
                           class="absolute top-6 right-6 z-30 p-3 bg-white/20 backdrop-blur-sm rounded-full text-white hover:bg-white/30 transition-all duration-300 transform hover:scale-110 shadow-lg group"
                           aria-label="Xem chi tiết {{ $slider->title ?? 'slider' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                    @endif
                    @if($slider->image_link)
                        <div class="smart-image-container w-full h-full overflow-hidden">
                            <img
                                src="{{ Storage::url($slider->image_link) }}"
                                alt="{{ $slider->alt_text ?: ($slider->title ? $slider->title . ' - Vũ Phúc Baking' : 'Banner Vũ Phúc Baking ' . ($index + 1)) }}"
                                class="smart-hero-image w-full h-full transform scale-105 transition-all duration-7000 ease-in-out"
                                x-bind:class="{ 'scale-100': activeSlide === {{ $index }}, 'scale-105': activeSlide !== {{ $index }} }"
                                loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                                onload="adjustImagePosition(this)"
                                onerror="this.style.display='none'; this.parentElement.parentElement.querySelector('.fallback-bg').style.display='flex';"
                            >
                        </div>
                        <!-- Fallback background khi ảnh không load được -->
                        <div class="fallback-bg w-full h-full bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center" style="display: none;">
                            <span class="text-white text-2xl font-medium">{{ $slider->title ?? 'Vũ Phúc Baking' }}</span>
                        </div>
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center">
                            <span class="text-white text-2xl font-medium">{{ $slider->title ?? 'Vũ Phúc Baking' }}</span>
                        </div>
                    @endif
                    @if($slider->title || $slider->description)
                    <div class="absolute inset-0 z-20 flex items-center">
                        <div class="container mx-auto px-4 lg:px-6">
                            <div
                                class="max-w-2xl transform transition-all duration-1000 delay-300"
                                x-bind:class="{ 'opacity-100 translate-y-0': activeSlide === {{ $index }}, 'opacity-0 translate-y-4': activeSlide !== {{ $index }} }"
                            >
                                @if($slider->title)
                                    <h1 class="text-white text-3xl lg:text-5xl xl:text-6xl font-bold mb-4 text-shadow leading-tight">{{ $slider->title }}</h1>
                                @endif
                                @if($slider->description)
                                    <p class="text-white text-base lg:text-lg xl:text-xl mb-6 max-w-2xl text-shadow leading-relaxed">{{ $slider->description }}</p>
                                @endif
                                @if($slider->link)
                                    <a href="{{ $slider->link }}" class="inline-flex items-center bg-red-600/90 hover:bg-red-700 text-white px-5 py-2.5 lg:px-6 lg:py-3 rounded-lg transition-all duration-300 transform hover:-translate-y-0.5 shadow-xl backdrop-blur-sm border border-red-500/30 text-base lg:text-lg font-medium">
                                        <span>Xem chi tiết</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 lg:h-5 lg:w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            @empty
                {{-- Fallback cho desktop nếu không có slider nào --}}
                <div class="relative h-[550px] lg:h-[700px] bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center">
                    <div class="absolute inset-0 bg-gradient-to-r from-black/30 via-black/10 to-transparent z-10"></div>
                    <div class="absolute inset-0 z-20 flex items-center">
                        <div class="container mx-auto px-4 lg:px-6">
                            <div class="max-w-2xl">
                                <h1 class="text-white text-3xl lg:text-5xl xl:text-6xl font-bold mb-4 text-shadow leading-tight">Vũ Phúc Baking</h1>
                                <p class="text-white text-base lg:text-lg xl:text-xl mb-6 max-w-2xl text-shadow leading-relaxed">Nhà phân phối độc quyền Rich Products Vietnam tại ĐBSCL - Cung cấp nguyên liệu, dụng cụ và thiết bị làm bánh chuyên nghiệp</p>
                                <a href="{{ route('products.categories') ?? '#' }}" class="inline-flex items-center bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white px-6 py-3 lg:px-8 lg:py-4 rounded-lg transition transform hover:-translate-y-0.5 shadow-xl text-lg font-medium">
                                    <span>Khám phá ngay</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Controls - Chỉ hiển thị khi có nhiều hơn 1 slider -->
        @if($activeSliders->count() > 1)
        <div class="absolute inset-x-0 top-1/2 transform -translate-y-1/2 flex items-center justify-between px-4 md:px-6 z-30">
            <button
                @click="prevSlide(); if(interval) { clearInterval(interval); interval = setInterval(() => nextSlide(), 8000); }"
                class="p-2 sm:p-3 rounded-full bg-white/20 backdrop-blur-sm text-white hover:bg-white/30 focus:outline-none transition-all duration-300 transform hover:scale-110 shadow-lg"
                aria-label="Slide trước">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <button
                @click="nextSlide(); if(interval) { clearInterval(interval); interval = setInterval(() => nextSlide(), 8000); }"
                class="p-2 sm:p-3 rounded-full bg-white/20 backdrop-blur-sm text-white hover:bg-white/30 focus:outline-none transition-all duration-300 transform hover:scale-110 shadow-lg"
                aria-label="Slide tiếp theo">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>

        <!-- Indicators -->
        <div class="absolute bottom-4 sm:bottom-6 left-0 right-0 z-30">
            <div class="flex items-center justify-center gap-2 sm:gap-3">
                @foreach($activeSliders as $index => $slider)
                    <button
                        @click="activeSlide = {{ $index }}; if(interval) { clearInterval(interval); interval = setInterval(() => nextSlide(), 8000); }"
                        class="w-3 h-3 sm:w-4 sm:h-4 rounded-full transition-all duration-300 focus:outline-none relative overflow-hidden shadow-lg"
                        x-bind:class="{ 'bg-white w-8 sm:w-10': activeSlide === {{ $index }}, 'bg-white/50': activeSlide !== {{ $index }} }"
                        aria-label="Đi đến slide {{ $index + 1 }}"
                    >
                        <span
                            class="absolute left-0 top-0 h-full bg-white transition-all duration-8000"
                            x-bind:class="{ 'w-full': activeSlide === {{ $index }}, 'w-0': activeSlide !== {{ $index }} }"
                            x-bind:style="activeSlide === {{ $index }} ? 'animation: progressBar 8s linear forwards;' : ''"
                        ></span>
                    </button>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>
@endif

{{-- CSS Styles cho Hero Banner --}}
<style>
    .text-shadow {
        text-shadow: 0 4px 8px rgba(0,0,0,0.5), 0 2px 4px rgba(0,0,0,0.3);
    }

    .text-shadow-sm {
        text-shadow: 0 2px 4px rgba(0,0,0,0.4), 0 1px 2px rgba(0,0,0,0.2);
    }

    .hero-carousel {
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
    }

    .hero-slide {
        will-change: transform, opacity;
        backface-visibility: hidden;
        transform-style: preserve-3d;
    }

    /* Smart Image Container - Thiết kế thông minh cho ảnh hero */
    .smart-image-container {
        position: relative;
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
    }

    .smart-hero-image {
        object-fit: cover;
        object-position: center center;
        image-rendering: -webkit-optimize-contrast;
        image-rendering: crisp-edges;
        transition: object-position 0.3s ease-out, transform 7s ease-in-out;
    }

    /* Responsive object positioning cho mobile */
    @media (max-width: 767px) {
        .smart-hero-image {
            object-position: center 15%; /* Ưu tiên phần trên nhiều hơn */
        }

        .smart-hero-image.landscape {
            object-position: center 20%;
        }

        .smart-hero-image.portrait {
            object-position: center 10%;
        }

        .smart-hero-image.square {
            object-position: center 18%;
        }
    }

    /* Responsive object positioning cho desktop */
    @media (min-width: 768px) {
        .smart-hero-image {
            object-position: center 25%; /* Ưu tiên phần trên trên desktop */
        }

        .smart-hero-image.landscape {
            object-position: center 30%;
        }

        .smart-hero-image.portrait {
            object-position: center 20%;
        }

        .smart-hero-image.square {
            object-position: center 25%;
        }

        .smart-hero-image.wide {
            object-position: center 35%;
        }
    }

    @keyframes progressBar {
        0% {
            width: 0;
            opacity: 0.8;
        }
        100% {
            width: 100%;
            opacity: 1;
        }
    }

    @media (min-width: 768px) {
        .hero-slide::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at center, transparent 40%, rgba(0,0,0,0.1) 100%);
            z-index: 5;
            pointer-events: none;
        }
    }

    @keyframes kenburns {
        0% {
            transform: scale(1) rotate(0deg);
        }
        100% {
            transform: scale(1.05) rotate(0.5deg);
        }
    }

    .duration-7000 {
        transition-duration: 7000ms;
    }

    .duration-8000 {
        transition-duration: 8000ms;
    }

    /* Tối ưu cho WebP images */
    .hero-slide img[src$=".webp"] {
        image-rendering: -webkit-optimize-contrast;
    }

    /* Responsive improvements */
    @media (max-width: 640px) {
        .hero-slide {
            min-height: 350px;
        }
    }

    @media (min-width: 1024px) {
        .hero-slide {
            min-height: 700px;
        }
    }

    /* Loading state cho smart images */
    .smart-hero-image {
        opacity: 0;
        transition: opacity 0.5s ease-in-out, object-position 0.3s ease-out, transform 7s ease-in-out;
    }

    .smart-hero-image.loaded {
        opacity: 1;
    }
</style>

{{-- JavaScript thông minh cho điều chỉnh vị trí ảnh --}}
<script>
function adjustImagePosition(img) {
    // Đợi ảnh load hoàn toàn
    if (!img.complete) {
        img.addEventListener('load', function() {
            adjustImagePosition(img);
        });
        return;
    }

    // Thêm class loaded để hiển thị ảnh
    img.classList.add('loaded');

    const naturalWidth = img.naturalWidth;
    const naturalHeight = img.naturalHeight;

    if (naturalWidth === 0 || naturalHeight === 0) return;

    // Tính tỷ lệ khung hình
    const aspectRatio = naturalWidth / naturalHeight;

    // Xóa các class cũ
    img.classList.remove('landscape', 'portrait', 'square', 'wide');

    // Phân loại ảnh và áp dụng positioning thông minh
    if (aspectRatio > 2.5) {
        // Ảnh rất rộng (panorama)
        img.classList.add('wide');
    } else if (aspectRatio > 1.3) {
        // Ảnh ngang (landscape)
        img.classList.add('landscape');
    } else if (aspectRatio < 0.8) {
        // Ảnh dọc (portrait)
        img.classList.add('portrait');
    } else {
        // Ảnh vuông hoặc gần vuông
        img.classList.add('square');
    }

    // Điều chỉnh thêm dựa trên kích thước container
    const container = img.closest('.smart-image-container');
    if (container) {
        const containerRect = container.getBoundingClientRect();
        const containerRatio = containerRect.width / containerRect.height;

        // Nếu container rộng hơn nhiều so với ảnh, ưu tiên phần trên
        if (containerRatio > aspectRatio * 1.5) {
            if (window.innerWidth >= 768) {
                img.style.objectPosition = 'center 20%'; // Ưu tiên phần trên
            } else {
                img.style.objectPosition = 'center 15%'; // Ưu tiên phần trên nhiều hơn trên mobile
            }
        }
        // Nếu ảnh rộng hơn nhiều so với container, vẫn ưu tiên phần trên
        else if (aspectRatio > containerRatio * 1.5) {
            if (window.innerWidth >= 768) {
                img.style.objectPosition = 'center 25%';
            } else {
                img.style.objectPosition = 'center 18%';
            }
        }
    }

    // Tối ưu cho mobile: luôn ưu tiên phần trên của ảnh
    if (window.innerWidth < 768) {
        if (img.classList.contains('portrait')) {
            img.style.objectPosition = 'center 8%'; // Rất ưu tiên phần trên cho ảnh dọc
        } else if (img.classList.contains('landscape')) {
            img.style.objectPosition = 'center 15%'; // Ưu tiên phần trên cho ảnh ngang
        } else if (img.classList.contains('square')) {
            img.style.objectPosition = 'center 12%'; // Ưu tiên phần trên cho ảnh vuông
        } else if (img.classList.contains('wide')) {
            img.style.objectPosition = 'center 20%'; // Ít ưu tiên hơn cho ảnh rất rộng
        }
    } else {
        // Desktop: vẫn ưu tiên phần trên nhưng ít hơn mobile
        if (img.classList.contains('portrait')) {
            img.style.objectPosition = 'center 15%';
        } else if (img.classList.contains('landscape')) {
            img.style.objectPosition = 'center 25%';
        } else if (img.classList.contains('square')) {
            img.style.objectPosition = 'center 20%';
        } else if (img.classList.contains('wide')) {
            img.style.objectPosition = 'center 30%';
        }
    }
}

// Điều chỉnh lại khi resize window
let resizeTimeout;
window.addEventListener('resize', function() {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(function() {
        document.querySelectorAll('.smart-hero-image').forEach(function(img) {
            if (img.complete) {
                adjustImagePosition(img);
            }
        });
    }, 250);
});

// Khởi tạo cho các ảnh đã load sẵn
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.smart-hero-image').forEach(function(img) {
        if (img.complete) {
            adjustImagePosition(img);
        }
    });
});
</script>