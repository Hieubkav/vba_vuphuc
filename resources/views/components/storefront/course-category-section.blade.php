{{--
    Course Category Section Component - Minimalist Design
    - Thiết kế minimalist với tone đỏ-trắng
    - Layout thông minh, gọn gàng
    - Header đơn giản, hiện đại
    - Grid responsive tối ưu
--}}

@props(['category', 'limit' => 8])

@php
    // Debug: Kiểm tra loại dữ liệu category
    if (!is_object($category) || !method_exists($category, 'relationLoaded')) {
        // Nếu category không phải là Eloquent model, skip component này
        $courses = collect();
    } else {
        // Sử dụng dữ liệu đã được eager load từ ViewServiceProvider
        // Nếu courses đã được load, sử dụng luôn, nếu không thì query
        if ($category->relationLoaded('courses')) {
            $courses = $category->courses->take($limit);
        } else {
            // Fallback query nếu chưa được eager load
            $courses = $category->courses()
                ->where('status', 'active')
                ->with(['courseCategory', 'instructor'])
                ->orderBy('is_featured', 'desc')
                ->orderBy('order')
                ->orderBy('created_at', 'desc')
                ->take($limit)
                ->get();
        }
    }
@endphp

@if($courses->isNotEmpty())
<div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
    <!-- Responsive Category Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 sm:mb-6 md:mb-8 gap-3 sm:gap-0">
        <div class="flex-1 min-w-0">
            <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 mb-1 truncate">
                {{ $category->name }}
            </h2>
            @if($category->description)
                <p class="text-xs sm:text-sm text-gray-600 line-clamp-1">
                    {{ $category->description }}
                </p>
            @endif
        </div>

        <!-- View All Link - Responsive -->
        <a
            href="{{ route('courses.cat-category', $category->slug) }}"
            class="inline-flex items-center px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-red-600 hover:text-white hover:bg-red-600 border border-red-600 rounded-lg transition-all duration-200 group flex-shrink-0"
        >
            <span class="hidden sm:inline">Xem tất cả</span>
            <span class="sm:hidden">Xem thêm</span>
            <i class="fas fa-arrow-right ml-1 sm:ml-2 text-xs group-hover:translate-x-1 transition-transform duration-200"></i>
        </a>
    </div>

    <!-- Courses Container with Carousel -->
    @if($courses->count() > 4)
        <!-- Carousel for more than 4 courses -->
        <div class="mx-2 sm:mx-4 lg:mx-8">
            <div class="relative carousel-container rounded-2xl p-2 sm:p-4 md:p-6">
            <!-- Navigation Buttons - Responsive -->
            <button
                id="prevBtn-{{ $category->id }}"
                class="carousel-nav-btn absolute -left-3 sm:-left-6 top-1/2 -translate-y-1/2 z-20 w-8 sm:w-12 h-8 sm:h-12 bg-red-600 hover:bg-red-700 rounded-full transition-all duration-300 flex items-center justify-center group"
                style="display: none;"
            >
                <i class="fas fa-chevron-left text-white text-sm sm:text-lg group-hover:scale-110 transition-transform"></i>
            </button>

            <button
                id="nextBtn-{{ $category->id }}"
                class="carousel-nav-btn absolute -right-3 sm:-right-6 top-1/2 -translate-y-1/2 z-20 w-8 sm:w-12 h-8 sm:h-12 bg-red-600 hover:bg-red-700 rounded-full transition-all duration-300 flex items-center justify-center group"
            >
                <i class="fas fa-chevron-right text-white text-sm sm:text-lg group-hover:scale-110 transition-transform"></i>
            </button>

            <!-- Carousel Container -->
            <div class="overflow-hidden">
                <div
                    id="carousel-{{ $category->id }}"
                    class="flex transition-transform duration-500 ease-in-out gap-2 sm:gap-3 md:gap-4"
                    style="transform: translateX(0px);"
                >
                    @foreach($courses as $course)
                        <div class="flex-none w-[calc(50%-0.25rem)] sm:w-[calc(50%-0.375rem)] md:w-[calc(33.333%-0.5rem)] lg:w-[calc(25%-0.75rem)] group">
                            <div class="carousel-card bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-50 group flex flex-col h-full">
                                @if(isset($course->slug))
                                <a href="{{ route('courses.show', $course->slug) }}" class="block">
                                @else
                                <div class="block">
                                @endif
                                    <div class="h-32 sm:h-40 md:h-48 overflow-hidden relative">
                                        @if(isset($course->thumbnail) && !empty($course->thumbnail))
                                            <img
                                                data-src="{{ asset('storage/' . $course->thumbnail) }}"
                                                alt="{{ $course->title ?? 'Khóa học VBA Vũ Phúc' }}"
                                                class="w-full h-full object-cover course-image course-thumbnail lazy-loading"
                                                loading="lazy"
                                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                                style="opacity: 0; transition: opacity 0.3s ease, filter 0.3s ease, transform 0.3s ease;"
                                            >
                                            <div class="w-full h-full bg-gradient-to-br from-red-50 to-red-100 flex-col items-center justify-center course-placeholder" style="display: none;">
                                                <i class="fas fa-graduation-cap text-2xl text-red-300 mb-1"></i>
                                            </div>
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-red-50 to-red-100 flex flex-col items-center justify-center">
                                                <i class="fas fa-graduation-cap text-2xl text-red-300 mb-1"></i>
                                            </div>
                                        @endif
                                    </div>
                                @if(isset($course->slug))
                                </a>
                                @else
                                </div>
                                @endif
                                <div class="p-3 sm:p-4 md:p-5 flex-1 flex flex-col">
                                    <div class="flex flex-wrap items-center gap-1 sm:gap-2 mb-2 sm:mb-3">
                                        @if($course->relationLoaded('category') && $course->category)
                                        <span class="bg-gray-50 text-gray-600 px-1.5 sm:px-2 py-0.5 sm:py-1 rounded-full text-xs font-medium truncate max-w-20 sm:max-w-none">{{ $course->category->name }}</span>
                                        @elseif($course->relationLoaded('courseCategory') && $course->courseCategory)
                                        <span class="bg-gray-50 text-gray-600 px-1.5 sm:px-2 py-0.5 sm:py-1 rounded-full text-xs font-medium truncate max-w-20 sm:max-w-none">{{ $course->courseCategory->name }}</span>
                                        @endif
                                        @if(isset($course->level) && !empty($course->level))
                                        <span class="text-gray-400 text-xs hidden sm:inline">{{ ucfirst($course->level) }}</span>
                                        @endif
                                    </div>
                                    @if(isset($course->slug))
                                    <a href="{{ route('courses.show', $course->slug) }}">
                                        <h3 class="text-xs sm:text-sm md:text-base font-light text-gray-900 mb-1 sm:mb-2 group-hover:text-gray-700 transition-colors line-clamp-2 leading-snug">{{ $course->title ?? 'Khóa học mới' }}</h3>
                                    </a>
                                    @else
                                    <h3 class="text-xs sm:text-sm md:text-base font-light text-gray-900 mb-1 sm:mb-2 line-clamp-2 leading-snug">{{ $course->title ?? 'Khóa học mới' }}</h3>
                                    @endif
                                    @if(isset($course->seo_description) && !empty($course->seo_description))
                                    <p class="text-gray-500 mb-2 sm:mb-3 line-clamp-2 text-xs leading-relaxed hidden sm:block">
                                        {{ Str::limit(strip_tags($course->seo_description), 60) }}
                                    </p>
                                    @elseif(isset($course->description) && !empty($course->description))
                                    <p class="text-gray-500 mb-2 sm:mb-3 line-clamp-2 text-xs leading-relaxed hidden sm:block">
                                        {{ Str::limit(strip_tags($course->description), 60) }}
                                    </p>
                                    @endif
                                    <div class="pt-1 sm:pt-2 border-t border-gray-50 mt-auto">
                                        @if(isset($course->slug))
                                        <div class="mb-1 sm:mb-2">
                                            <a href="{{ route('courses.show', $course->slug) }}" class="inline-flex items-center text-gray-900 hover:text-gray-700 font-medium text-xs group">
                                                <span class="hidden sm:inline">Xem khóa học</span>
                                                <span class="sm:hidden">Xem</span>
                                                <i class="fas fa-arrow-right ml-1 sm:ml-2 transition-transform group-hover:translate-x-1"></i>
                                            </a>
                                        </div>
                                        @endif
                                        @if(isset($course->price) && $course->price > 0)
                                        <div class="text-right">
                                            <span class="text-xs font-light text-gray-900">{{ number_format($course->price) }}đ</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Dots Indicator - Responsive -->
            <div class="flex justify-center mt-4 sm:mt-6 space-x-2 sm:space-x-3">
                @php
                    $totalSlides = ceil($courses->count() / 4);
                @endphp
                @for($i = 0; $i < $totalSlides; $i++)
                    <button
                        class="carousel-dot w-2 sm:w-3 h-2 sm:h-3 rounded-full dot-{{ $category->id }} {{ $i === 0 ? 'bg-red-600 scale-125' : 'bg-red-200 hover:bg-red-400 hover:scale-110' }}"
                        data-slide="{{ $i }}"
                    ></button>
                @endfor
            </div>
        </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const carousel = document.getElementById('carousel-{{ $category->id }}');
                const prevBtn = document.getElementById('prevBtn-{{ $category->id }}');
                const nextBtn = document.getElementById('nextBtn-{{ $category->id }}');
                const dots = document.querySelectorAll('.dot-{{ $category->id }}');

                let currentSlide = 0;
                const totalSlides = {{ ceil($courses->count() / 4) }};
                const slideWidth = 100; // percentage

                function updateCarousel() {
                    carousel.style.transform = `translateX(-${currentSlide * slideWidth}%)`;

                    // Update dots với animation mượt mà
                    dots.forEach((dot, index) => {
                        if (index === currentSlide) {
                            dot.classList.remove('bg-red-200', 'hover:bg-red-400', 'hover:scale-110');
                            dot.classList.add('bg-red-600', 'scale-125');
                        } else {
                            dot.classList.remove('bg-red-600', 'scale-125');
                            dot.classList.add('bg-red-200', 'hover:bg-red-400', 'hover:scale-110');
                        }
                    });

                    // Update buttons với animation
                    if (currentSlide === 0) {
                        prevBtn.style.display = 'none';
                    } else {
                        prevBtn.style.display = 'flex';
                        prevBtn.style.animation = 'fadeIn 0.3s ease-in-out';
                    }

                    if (currentSlide === totalSlides - 1) {
                        nextBtn.style.display = 'none';
                    } else {
                        nextBtn.style.display = 'flex';
                        nextBtn.style.animation = 'fadeIn 0.3s ease-in-out';
                    }
                }

                nextBtn.addEventListener('click', () => {
                    if (currentSlide < totalSlides - 1) {
                        currentSlide++;
                        updateCarousel();
                    }
                });

                prevBtn.addEventListener('click', () => {
                    if (currentSlide > 0) {
                        currentSlide--;
                        updateCarousel();
                    }
                });

                dots.forEach((dot, index) => {
                    dot.addEventListener('click', () => {
                        currentSlide = index;
                        updateCarousel();
                    });
                });

                updateCarousel();

                // Lazy loading cho ảnh khóa học
                const lazyImages = document.querySelectorAll('.course-thumbnail.lazy-loading');

                const imageObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            const src = img.getAttribute('data-src');

                            if (src) {
                                img.src = src;
                                img.onload = () => {
                                    img.style.opacity = '1';
                                    img.classList.add('loaded');
                                };
                            }

                            observer.unobserve(img);
                        }
                    });
                });

                lazyImages.forEach(img => imageObserver.observe(img));
            });
        </script>
    @else
        <!-- Static Grid for 4 or fewer courses - Responsive -->
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2 sm:gap-3 md:gap-4">
            @foreach($courses as $course)
                <div class="group">
                    <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-50 group flex flex-col h-full">
                        @if(isset($course->slug))
                        <a href="{{ route('courses.show', $course->slug) }}" class="block">
                        @else
                        <div class="block">
                        @endif
                            <div class="h-32 sm:h-40 md:h-48 overflow-hidden relative">
                                @if(isset($course->thumbnail) && !empty($course->thumbnail))
                                    <img
                                        data-src="{{ asset('storage/' . $course->thumbnail) }}"
                                        alt="{{ $course->title ?? 'Khóa học VBA Vũ Phúc' }}"
                                        class="w-full h-full object-cover course-image course-thumbnail lazy-loading"
                                        loading="lazy"
                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                        style="opacity: 0; transition: opacity 0.3s ease, filter 0.3s ease, transform 0.3s ease;"
                                    >
                                    <div class="w-full h-full bg-gradient-to-br from-red-50 to-red-100 flex-col items-center justify-center course-placeholder" style="display: none;">
                                        <i class="fas fa-graduation-cap text-2xl text-red-300 mb-1"></i>
                                    </div>
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-red-50 to-red-100 flex flex-col items-center justify-center">
                                        <i class="fas fa-graduation-cap text-2xl text-red-300 mb-1"></i>
                                    </div>
                                @endif
                            </div>
                        @if(isset($course->slug))
                        </a>
                        @else
                        </div>
                        @endif
                        <div class="p-3 sm:p-4 md:p-5 flex-1 flex flex-col">
                            <div class="flex flex-wrap items-center gap-2 mb-3">
                                @if($course->relationLoaded('category') && $course->category)
                                <span class="bg-gray-50 text-gray-600 px-2 py-1 rounded-full text-xs font-medium">{{ $course->category->name }}</span>
                                @elseif($course->relationLoaded('courseCategory') && $course->courseCategory)
                                <span class="bg-gray-50 text-gray-600 px-2 py-1 rounded-full text-xs font-medium">{{ $course->courseCategory->name }}</span>
                                @endif
                                @if(isset($course->level) && !empty($course->level))
                                <span class="text-gray-400 text-xs">{{ ucfirst($course->level) }}</span>
                                @endif
                            </div>
                            @if(isset($course->slug))
                            <a href="{{ route('courses.show', $course->slug) }}">
                                <h3 class="text-sm md:text-base font-light text-gray-900 mb-2 group-hover:text-gray-700 transition-colors line-clamp-2 leading-snug">{{ $course->title ?? 'Khóa học mới' }}</h3>
                            </a>
                            @else
                            <h3 class="text-sm md:text-base font-light text-gray-900 mb-2 line-clamp-2 leading-snug">{{ $course->title ?? 'Khóa học mới' }}</h3>
                            @endif
                            @if(isset($course->seo_description) && !empty($course->seo_description))
                            <p class="text-gray-500 mb-3 line-clamp-2 text-xs md:text-sm leading-relaxed">
                                {{ Str::limit(strip_tags($course->seo_description), 80) }}
                            </p>
                            @elseif(isset($course->description) && !empty($course->description))
                            <p class="text-gray-500 mb-3 line-clamp-2 text-xs md:text-sm leading-relaxed">
                                {{ Str::limit(strip_tags($course->description), 80) }}
                            </p>
                            @endif
                            <div class="pt-2 border-t border-gray-50 mt-auto">
                                @if(isset($course->slug))
                                <div class="mb-2">
                                    <a href="{{ route('courses.show', $course->slug) }}" class="inline-flex items-center text-gray-900 hover:text-gray-700 font-medium text-xs group">
                                        <span>Xem khóa học</span>
                                        <i class="fas fa-arrow-right ml-2 transition-transform group-hover:translate-x-1"></i>
                                    </a>
                                </div>
                                @endif
                                @if(isset($course->price) && $course->price > 0)
                                <div class="text-right">
                                    <span class="text-xs font-light text-gray-900">{{ number_format($course->price) }}đ</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Lazy loading cho ảnh khóa học trong static grid
                const lazyImages = document.querySelectorAll('.course-thumbnail.lazy-loading');

                const imageObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            const src = img.getAttribute('data-src');

                            if (src) {
                                img.src = src;
                                img.onload = () => {
                                    img.style.opacity = '1';
                                    img.classList.add('loaded');
                                };
                            }

                            observer.unobserve(img);
                        }
                    });
                });

                lazyImages.forEach(img => imageObserver.observe(img));
            });
        </script>
    @endif
</div>
@endif

<style>
    /* Minimalist Course Categories Styles */
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Smooth transitions */
    .group:hover .group-hover\:scale-105 {
        transform: scale(1.05);
    }

    .group:hover .group-hover\:text-red-600 {
        color: #dc2626;
    }

    .group:hover .group-hover\:translate-x-1 {
        transform: translateX(0.25rem);
    }

    /* Responsive optimizations cho mobile */
    @media (max-width: 640px) {
        .grid-cols-2 > * {
            min-width: 0;
        }

        /* Giảm khoảng cách carousel trên mobile */
        .carousel-container {
            margin-left: 0.5rem;
            margin-right: 0.5rem;
        }

        /* Ẩn description trên mobile để tiết kiệm không gian */
        .line-clamp-2.hidden.sm\:block {
            display: none;
        }
    }

    @media (max-width: 480px) {
        /* Giảm padding cho mobile nhỏ */
        .carousel-container {
            padding: 0.5rem;
        }

        /* Giảm kích thước navigation buttons */
        .carousel-nav-btn {
            width: 2rem;
            height: 2rem;
        }
    }

    @media (min-width: 1280px) {
        .xl\:grid-cols-6 {
            grid-template-columns: repeat(6, minmax(0, 1fr));
        }
    }

    /* Enhanced hover effects for minimalist design */
    .group a {
        transform: translateY(0);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .group:hover a {
        transform: translateY(-2px);
    }

    /* Badge animations */
    .group .bg-red-600,
    .group .bg-green-600,
    .group .bg-white\/90 {
        transition: all 0.2s ease;
    }

    .group:hover .bg-white\/90 {
        background-color: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(8px);
    }

    /* Course card styles - Tone đỏ-trắng */
    .course-placeholder {
        background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 50%, #fecaca 100%);
        position: relative;
        overflow: hidden;
    }

    .course-placeholder::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.8), transparent);
        animation: shimmer 3s infinite;
    }

    @keyframes shimmer {
        0% {
            left: -100%;
        }
        100% {
            left: 100%;
        }
    }

    /* Line clamp utilities */
    .line-clamp-2 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .line-clamp-3 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
    }

    /* Lazy loading styles */
    .lazy-loading {
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .lazy-loading.loaded {
        opacity: 1;
    }

    /* Carousel Styles - Minimalist Red-White Theme */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(0.8);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    /* Carousel navigation buttons */
    .carousel-nav-btn {
        backdrop-filter: blur(10px);
        box-shadow: 0 8px 32px rgba(220, 38, 38, 0.3);
        z-index: 30;
    }

    .carousel-nav-btn:hover {
        box-shadow: 0 12px 40px rgba(220, 38, 38, 0.4);
        transform: translateY(-2px) scale(1.05);
    }

    /* Carousel container styling */
    .carousel-container {
        background: linear-gradient(135deg,
            rgba(254, 242, 242, 0.8) 0%,
            rgba(255, 255, 255, 0.9) 50%,
            rgba(254, 242, 242, 0.8) 100%);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(220, 38, 38, 0.1);
        overflow: visible;
    }

    /* Carousel wrapper để tránh cắt buttons */
    .carousel-wrapper {
        position: relative;
        margin: 0 2rem;
    }

    @media (max-width: 768px) {
        .carousel-wrapper {
            margin: 0 1rem;
        }
    }

    /* Dots animation */
    .carousel-dot {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 8px rgba(220, 38, 38, 0.2);
    }

    .carousel-dot:hover {
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
    }

    /* Card hover effects trong carousel */
    .carousel-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .carousel-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }
</style>
