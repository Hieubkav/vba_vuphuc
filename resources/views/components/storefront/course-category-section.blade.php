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
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Minimalist Category Header -->
    <div class="flex items-center justify-between mb-6 md:mb-8">
        <div class="flex items-center space-x-3">
            <!-- Category Icon -->
            <div class="w-10 h-10 bg-red-600 rounded-lg flex items-center justify-center flex-shrink-0 overflow-hidden">
                @if(isset($category->image) && !empty($category->image))
                    <img
                        src="{{ asset('storage/' . $category->image) }}"
                        alt="{{ $category->name ?? 'Danh mục khóa học' }}"
                        class="w-6 h-6 object-cover rounded"
                        loading="lazy"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-block';"
                    >
                    <i class="fas fa-{{ $category->icon ?? 'graduation-cap' }} text-white text-sm" style="display: none;"></i>
                @else
                    <i class="fas fa-{{ $category->icon ?? 'graduation-cap' }} text-white text-sm"></i>
                @endif
            </div>

            <!-- Category Info -->
            <div>
                <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-1">
                    {{ $category->name }}
                </h2>
                @if($category->description)
                    <p class="text-sm text-gray-600 line-clamp-1">
                        {{ $category->description }}
                    </p>
                @endif
            </div>
        </div>

        <!-- View All Link -->
        <a
            href="{{ route('courses.cat-category', $category->slug) }}"
            class="inline-flex items-center px-4 py-2 text-sm font-medium text-red-600 hover:text-white hover:bg-red-600 border border-red-600 rounded-lg transition-all duration-200 group"
        >
            Xem tất cả
            <i class="fas fa-arrow-right ml-2 text-xs group-hover:translate-x-1 transition-transform duration-200"></i>
        </a>
    </div>

    <!-- Courses Container with Carousel -->
    @if($courses->count() > 4)
        <!-- Carousel for more than 4 courses -->
        <div class="relative">
            <!-- Navigation Buttons -->
            <button
                id="prevBtn-{{ $category->id }}"
                class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 z-10 w-10 h-10 bg-white border border-gray-200 rounded-full shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center group"
                style="display: none;"
            >
                <i class="fas fa-chevron-left text-gray-600 group-hover:text-red-600 transition-colors"></i>
            </button>

            <button
                id="nextBtn-{{ $category->id }}"
                class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 z-10 w-10 h-10 bg-white border border-gray-200 rounded-full shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center group"
            >
                <i class="fas fa-chevron-right text-gray-600 group-hover:text-red-600 transition-colors"></i>
            </button>

            <!-- Carousel Container -->
            <div class="overflow-hidden">
                <div
                    id="carousel-{{ $category->id }}"
                    class="flex transition-transform duration-300 ease-in-out gap-3 md:gap-4"
                    style="transform: translateX(0px);"
                >
                    @foreach($courses as $course)
                        <div class="flex-none w-1/2 md:w-1/3 lg:w-1/4 group">
                            <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-50 group h-full">
                                @if(isset($course->slug))
                                <a href="{{ route('courses.show', $course->slug) }}" class="block">
                                @else
                                <div class="block">
                                @endif
                                    <div class="h-40 md:h-48 overflow-hidden relative">
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
                                <div class="p-4 md:p-5">
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
                                    <div class="flex items-center justify-between pt-2 border-t border-gray-50">
                                        @if(isset($course->slug))
                                        <a href="{{ route('courses.show', $course->slug) }}" class="inline-flex items-center text-gray-900 hover:text-gray-700 font-medium text-xs group">
                                            <span>Xem khóa học</span>
                                            <i class="fas fa-arrow-right ml-2 transition-transform group-hover:translate-x-1"></i>
                                        </a>
                                        @endif
                                        @if(isset($course->price) && $course->price > 0)
                                        <span class="text-xs font-light text-gray-900">{{ number_format($course->price) }}đ</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Dots Indicator -->
            <div class="flex justify-center mt-4 space-x-2">
                @php
                    $totalSlides = ceil($courses->count() / 4);
                @endphp
                @for($i = 0; $i < $totalSlides; $i++)
                    <button
                        class="w-2 h-2 rounded-full transition-all duration-200 dot-{{ $category->id }} {{ $i === 0 ? 'bg-red-600' : 'bg-gray-300 hover:bg-gray-400' }}"
                        data-slide="{{ $i }}"
                    ></button>
                @endfor
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

                    // Update dots
                    dots.forEach((dot, index) => {
                        dot.classList.toggle('bg-red-600', index === currentSlide);
                        dot.classList.toggle('bg-gray-300', index !== currentSlide);
                    });

                    // Update buttons
                    prevBtn.style.display = currentSlide === 0 ? 'none' : 'flex';
                    nextBtn.style.display = currentSlide === totalSlides - 1 ? 'none' : 'flex';
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
        <!-- Static Grid for 4 or fewer courses -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-4">
            @foreach($courses as $course)
                <div class="group">
                    <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-50 group h-full">
                        @if(isset($course->slug))
                        <a href="{{ route('courses.show', $course->slug) }}" class="block">
                        @else
                        <div class="block">
                        @endif
                            <div class="h-40 md:h-48 overflow-hidden relative">
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
                        <div class="p-4 md:p-5">
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
                            <div class="flex items-center justify-between pt-2 border-t border-gray-50">
                                @if(isset($course->slug))
                                <a href="{{ route('courses.show', $course->slug) }}" class="inline-flex items-center text-gray-900 hover:text-gray-700 font-medium text-xs group">
                                    <span>Xem khóa học</span>
                                    <i class="fas fa-arrow-right ml-2 transition-transform group-hover:translate-x-1"></i>
                                </a>
                                @endif
                                @if(isset($course->price) && $course->price > 0)
                                <span class="text-xs font-light text-gray-900">{{ number_format($course->price) }}đ</span>
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

    /* Responsive grid optimizations */
    @media (max-width: 640px) {
        .grid-cols-2 > * {
            min-width: 0;
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
</style>
