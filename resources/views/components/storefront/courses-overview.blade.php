@php
    // Sử dụng dữ liệu từ ViewServiceProvider với fallback tối ưu
    $courseCategoriesData = $courseCategories ?? collect();



    // Fallback: nếu không có dữ liệu từ ViewServiceProvider, lấy trực tiếp từ model với cache
    if ($courseCategoriesData->isEmpty()) {
        try {
            $courseCategoriesData = cache()->remember('courses_overview_data', 300, function() {
                // Lấy tất cả danh mục có trạng thái active và có khóa học
                $categories = \App\Models\CatCourse::where('status', 'active')
                    ->whereHas('courses', function($query) {
                        $query->where('status', 'active');
                    })
                    ->select(['id', 'name', 'slug', 'description', 'order'])
                    ->orderBy('order')
                    ->get();

                // Lấy khóa học mới nhất cho từng danh mục riêng biệt để tránh bug eager loading
                return $categories->map(function ($category) {
                    $latestCourse = \App\Models\Course::where('cat_course_id', $category->id)
                        ->where('status', 'active')
                        ->with(['instructor:id,name'])
                        ->select([
                            'id', 'title', 'slug', 'thumbnail', 'seo_title', 'seo_description',
                            'description', 'cat_course_id', 'instructor_id', 'price', 'level',
                            'duration_hours', 'start_date', 'gg_form', 'created_at', 'order'
                        ])
                        ->orderBy('created_at', 'desc')
                        ->first(); // Lấy khóa học mới nhất

                    // Gán khóa học mới nhất vào category
                    $category->latest_course = $latestCourse;

                    return $category;
                })->filter(function ($category) {
                    // Chỉ giữ lại các danh mục có khóa học
                    return $category->latest_course !== null;
                });
            });
        } catch (\Exception $e) {
            $courseCategoriesData = collect();
        }
    }

    // Tối ưu hóa dữ liệu cho hiệu suất
    $hasData = isset($courseCategoriesData) && !empty($courseCategoriesData) && $courseCategoriesData->count() > 0;
    $placeholderImage = asset('images/course-placeholder.webp');
    $categoriesCount = $hasData ? $courseCategoriesData->count() : 0;
    $useSwiper = $categoriesCount > 3; // Sử dụng Swiper nếu có nhiều hơn 3 danh mục
@endphp

<!-- Swiper CSS -->
@if($useSwiper)
@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
.courses-swiper-container {
    padding: 0 50px; /* Space for navigation buttons */
}

.courses-swiper .swiper-slide {
    height: auto;
    display: flex;
}

.courses-swiper .swiper-slide article {
    width: 100%;
    height: 100%;
}

.courses-swiper .swiper-pagination-bullet {
    background: #dc2626;
    opacity: 0.3;
}

.courses-swiper .swiper-pagination-bullet-active {
    opacity: 1;
}

@media (max-width: 768px) {
    .courses-swiper-container {
        padding: 0 20px;
    }

    .swiper-button-next,
    .swiper-button-prev {
        display: none !important;
    }
}
</style>
@endpush
@endif

@if($hasData)

<!-- KISS: Main Content đơn giản -->
<div class="container mx-auto px-4">
    <!-- Header Section với Typography tối ưu và Glassmorphism -->
    <div class="flex flex-col items-center text-center mb-12 animate-fade-in">
        <!-- Badge với Micro-interactions và Font Awesome -->
        <div class="group inline-flex items-center px-6 py-3 bg-gradient-to-r from-red-50 to-red-100 backdrop-blur-sm border border-red-200/50 text-red-700 rounded-full text-sm font-semibold tracking-wide uppercase mb-6 shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 cursor-default">
            <i class="fas fa-graduation-cap mr-3"></i>
            <span class="bg-gradient-to-r from-red-600 to-red-700 bg-clip-text text-transparent font-bold">Khóa học làm bánh</span>
        </div>

        <!-- Main Heading với Advanced Typography -->
        <h2 class="text-4xl md:text-6xl lg:text-7xl font-black mb-8 text-gray-900 leading-[0.9] tracking-tight">
            <span class="block">Khám Phá Thế Giới</span>
            <span class="block text-red-600 relative mt-2">
                <span class="relative z-10">Làm Bánh</span>
                <!-- Animated Underline với Gradient -->
                <div class="absolute -bottom-3 left-0 w-full h-2 bg-gradient-to-r from-red-400 via-red-500 to-red-600 rounded-full transform scale-x-0 group-hover:scale-x-100 transition-transform duration-700 ease-out"></div>
                <!-- Glow Effect -->
                <div class="absolute -bottom-3 left-0 w-full h-2 bg-gradient-to-r from-red-400 via-red-500 to-red-600 rounded-full blur-sm opacity-50"></div>
            </span>
        </h2>


    </div>

    <!-- Course Categories - Dynamic Layout (Grid hoặc Swiper) -->
    @if($useSwiper)
        <!-- Swiper Layout cho nhiều danh mục -->
        <div class="courses-swiper-container relative">
            <div class="swiper courses-swiper">
                <div class="swiper-wrapper">
                    @foreach($courseCategoriesData as $index => $category)
                        <div class="swiper-slide">
                            @include('components.storefront.course-card', ['category' => $category, 'useSwiper' => true])
                        </div>
                    @endforeach
                </div> <!-- End swiper-wrapper -->

                <!-- Swiper Navigation -->
                <div class="swiper-button-next !text-red-600 !w-10 !h-10 !mt-0 !top-1/2 !-translate-y-1/2 !right-4 !bg-white !rounded-full !shadow-lg hover:!bg-red-50 transition-colors duration-300"></div>
                <div class="swiper-button-prev !text-red-600 !w-10 !h-10 !mt-0 !top-1/2 !-translate-y-1/2 !left-4 !bg-white !rounded-full !shadow-lg hover:!bg-red-50 transition-colors duration-300"></div>

                <!-- Swiper Pagination -->
                <div class="swiper-pagination !bottom-4 !relative !mt-8"></div>
            </div> <!-- End swiper -->
        </div> <!-- End swiper-container -->
    @else
        <!-- Grid Layout cho ít danh mục -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($courseCategoriesData as $index => $category)
                @include('components.storefront.course-card', ['category' => $category, 'useSwiper' => false])
            @endforeach
        </div> <!-- End grid -->
    @endif


    <!-- CTA Section - Gọn gàng -->
    <div class="text-center mt-12">
        <a href="{{ route('courses.index') }}"
           class="inline-flex items-center px-8 py-4 bg-red-600 hover:bg-red-700 text-white font-semibold text-lg rounded-2xl transition-colors duration-300 shadow-lg hover:shadow-xl"
           aria-label="Xem tất cả khóa học làm bánh">
            <span>Xem tất cả khóa học làm bánh</span>
            <i class="fas fa-arrow-right ml-3"></i>
        </a>
    </div>
</div>

@else
<!-- Enhanced Empty State với Better UX -->
<div class="container mx-auto px-4 relative z-10">
    <div class="flex flex-col items-center text-center py-20 animate-fade-in">
        <!-- Animated Icon Container với Font Awesome -->
        <div class="relative mb-8">
            <div class="w-32 h-32 bg-gradient-to-br from-red-100 to-red-200 rounded-3xl flex items-center justify-center shadow-xl">
                <i class="fas fa-graduation-cap text-6xl text-red-500"></i>
            </div>
            <!-- Floating Elements -->
            <div class="absolute -top-2 -right-2 w-6 h-6 bg-red-400 rounded-full animate-bounce"></div>
            <div class="absolute -bottom-2 -left-2 w-4 h-4 bg-red-300 rounded-full animate-pulse"></div>
        </div>

        <!-- Enhanced Typography -->
        <h3 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6 tracking-tight">
            Chưa có khóa học làm bánh nào
        </h3>

        <p class="text-gray-600 text-lg leading-relaxed max-w-lg mb-10 font-light">
            Các khóa học làm bánh sẽ sớm được cập nhật.
            <br class="hidden sm:block">
            Hãy theo dõi để không bỏ lỡ những khóa học hữu ích!
        </p>

        <!-- Enhanced CTA Button -->
        <a href="{{ route('posts.index') }}"
           class="group inline-flex items-center px-8 py-4 bg-gradient-to-r from-red-600 to-red-700 text-white font-semibold text-lg rounded-2xl hover:from-red-700 hover:to-red-800 transition-all duration-300 transform hover:scale-105 hover:-translate-y-1 shadow-xl hover:shadow-2xl focus:outline-none focus:ring-4 focus:ring-red-500/50"
           aria-label="Xem bài viết khác">
            <span>Xem bài viết khác</span>
            <i class="fas fa-arrow-right ml-3 transition-transform duration-300 group-hover:translate-x-1"></i>
        </a>
    </div>
</div>
@endif

<!-- KISS: Scripts đơn giản -->
@push('scripts')
@if($useSwiper)
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    try {
        new Swiper('.courses-swiper', {
            slidesPerView: 1,
            spaceBetween: 30,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                768: { slidesPerView: 2 },
                1024: { slidesPerView: 3 }
            }
        });
    } catch (error) {
        console.warn('Swiper failed:', error);
    }
});
</script>
@endif
@endpush