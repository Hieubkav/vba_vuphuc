@php
    // Sử dụng dữ liệu từ ViewServiceProvider với fallback tối ưu
    $courseCategoriesData = $courseCategories ?? collect();

    // Lấy dữ liệu WebDesign cho section này
    $webDesignData = $webDesign ?? null;
    $sectionTitle = $webDesignData?->courses_overview_title ?? 'Khóa học chuyên nghiệp';
    $sectionDescription = $webDesignData?->courses_overview_description ?? 'Khám phá những khóa học được thiết kế bởi các chuyên gia hàng đầu';

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
                            'description', 'cat_course_id', 'instructor_id', 'level',
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

<!-- CSS Swiper - Giao diện đỏ trắng hiện đại -->
@if($useSwiper)
@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
:root {
    --primary-red: #dc2626;
    --primary-red-light: #ef4444;
    --primary-red-dark: #b91c1c;
    --accent-red: #fef2f2;
    --surface-white: #ffffff;
    --surface-gray: #f8fafc;
    --text-primary: #1f2937;
    --text-secondary: #6b7280;
    --shadow-soft: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-medium: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    --shadow-large: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.courses-swiper-container {
    padding: 0 60px;
    background: linear-gradient(135deg, var(--surface-white) 0%, var(--surface-gray) 100%);
    border-radius: 24px;
    position: relative;
    overflow: hidden;
}

.courses-swiper-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(circle at 30% 20%, rgba(220, 38, 38, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 70% 80%, rgba(220, 38, 38, 0.03) 0%, transparent 50%);
    pointer-events: none;
    z-index: 0;
}

.courses-swiper {
    position: relative;
    z-index: 1;
    padding: 32px 0;
}

.courses-swiper .swiper-slide {
    height: auto;
    display: flex;
    padding: 0 8px;
}

.courses-swiper .swiper-slide article {
    width: 100%;
    height: 100%;
    transform: translateY(0);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.courses-swiper .swiper-slide-active article {
    transform: translateY(-4px);
}

.courses-swiper .swiper-pagination-bullet {
    background: var(--primary-red);
    opacity: 0.2;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.courses-swiper .swiper-pagination-bullet-active {
    opacity: 1;
    transform: scale(1.2);
    background: linear-gradient(135deg, var(--primary-red) 0%, var(--primary-red-light) 100%);
    box-shadow: 0 4px 8px rgba(220, 38, 38, 0.3);
}

@media (max-width: 768px) {
    .courses-swiper-container {
        padding: 0 24px;
        border-radius: 20px;
    }

    .courses-swiper {
        padding: 24px 0;
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

<!-- Nội dung chính hiện đại với giao diện đỏ trắng -->
<section class="bg-white py-12">
<div class="container mx-auto px-4 animate-fade-in-optimized">
    <!-- Tiêu đề phần gọn gàng -->
    <div class="text-center mb-12">
        <h2 class="section-title mb-3 bg-gradient-to-r from-red-600 via-red-700 to-red-800 bg-clip-text text-transparent">
            {{ $sectionTitle }}
        </h2>
        <p class="subtitle max-w-2xl mx-auto">
            {{ $sectionDescription }}
        </p>
    </div>

    <!-- Danh mục khóa học - Bố cục linh hoạt (Lưới hoặc Trượt) -->
    @if($useSwiper)
        <!-- Bố cục trượt cho nhiều danh mục -->
        <div class="courses-swiper-container relative mb-12">
            <div class="swiper courses-swiper">
                <div class="swiper-wrapper">
                    @foreach($courseCategoriesData as $index => $category)
                        <div class="swiper-slide">
                            @include('components.storefront.course-card', ['category' => $category, 'useSwiper' => true])
                        </div>
                    @endforeach
                </div> <!-- Kết thúc swiper-wrapper -->

                <!-- Điều hướng trượt gọn gàng -->
                <div class="swiper-button-next !text-red-600 !w-10 !h-10 !mt-0 !top-1/2 !-translate-y-1/2 !right-6 !bg-white !rounded-full !shadow-md hover:!bg-red-50 transition-all duration-300"></div>
                <div class="swiper-button-prev !text-red-600 !w-10 !h-10 !mt-0 !top-1/2 !-translate-y-1/2 !left-6 !bg-white !rounded-full !shadow-md hover:!bg-red-50 transition-all duration-300"></div>

                <!-- Phân trang trượt gọn gàng -->
                <div class="swiper-pagination !bottom-0 !relative !mt-6"></div>
            </div> <!-- Kết thúc swiper -->
        </div> <!-- Kết thúc swiper-container -->
    @else
        <!-- Bố cục lưới hiện đại cho ít danh mục -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
            @foreach($courseCategoriesData as $index => $category)
                @include('components.storefront.course-card', ['category' => $category, 'useSwiper' => false])
            @endforeach
        </div> <!-- Kết thúc lưới -->
    @endif

    <!-- Phần kêu gọi hành động gọn gàng -->
    <div class="text-center">
        <a href="{{ route('courses.index') }}"
           class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white btn-text-lg rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-red-500/30"
           aria-label="Xem tất cả khóa học">
            <span class="font-semibold">Xem tất cả khóa học</span>
        </a>
    </div>
</div>
</section>

@else
<!-- Trạng thái trống hiện đại với thiết kế tinh tế -->
<section class="bg-white py-12">
<div class="container mx-auto px-4 animate-fade-in-optimized">
    <div class="max-w-xl mx-auto">
        <div class="bg-gradient-to-br from-white to-red-50/30 rounded-2xl p-8 shadow-lg border border-red-100/50 backdrop-blur-sm">
            <div class="flex flex-col items-center text-center space-y-6">
                <!-- Khung biểu tượng gọn gàng -->
                <div class="w-14 h-14 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg mx-auto">
                    <i class="fas fa-graduation-cap text-xl text-white"></i>
                </div>

                <!-- Kiểu chữ hiện đại -->
                <div class="space-y-3">
                    <h3 class="section-title bg-gradient-to-r from-red-600 to-red-700 bg-clip-text text-transparent">
                        {{ $sectionTitle }}
                    </h3>
                    <p class="subtitle max-w-md text-sm">
                        {{ $sectionDescription }}
                        <br class="hidden sm:block">
                        Đăng ký nhận thông báo để không bỏ lỡ!
                    </p>
                </div>

                <!-- Nút hành động gọn gàng -->
                <div class="flex flex-col sm:flex-row gap-2 w-full max-w-sm">
                    <a href="{{ route('posts.index') }}"
                       class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white btn-text rounded-lg transition-all duration-300 shadow-lg hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-red-500/30 text-sm"
                       aria-label="Xem bài viết khác">
                        <span class="font-medium">Xem bài viết</span>
                    </a>
                    <button class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-white hover:bg-red-50 text-red-600 hover:text-red-700 btn-text rounded-lg transition-all duration-300 border border-red-200 hover:border-red-300 focus:outline-none focus:ring-4 focus:ring-red-500/20 text-sm"
                            onclick="alert('Tính năng đăng ký thông báo sẽ sớm ra mắt!')">
                        <span class="font-medium">Nhận thông báo</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
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