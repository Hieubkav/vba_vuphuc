@php
    // Sử dụng dữ liệu từ ViewServiceProvider với fallback tối ưu
    $courseCategoriesData = $courseCategories ?? collect();

    // Fallback: nếu không có dữ liệu từ ViewServiceProvider, lấy trực tiếp từ model với cache
    if ($courseCategoriesData->isEmpty()) {
        try {
            $courseCategoriesData = cache()->remember('courses_overview_data', 300, function() {
                return \App\Models\CatCourse::where('status', 'active')
                    ->whereIn('slug', ['ky-nang', 'ky-thuat', 'hoi-thao'])
                    ->with(['courses' => function($query) {
                        $query->where('status', 'active')
                            ->with(['instructor:id,name'])
                            ->select([
                                'id', 'title', 'slug', 'thumbnail', 'seo_title', 'seo_description',
                                'description', 'cat_course_id', 'instructor_id', 'price', 'level',
                                'duration_hours', 'start_date', 'gg_form', 'created_at', 'order'
                            ])
                            ->orderBy('created_at', 'desc')
                            ->take(1); // Chỉ lấy 1 khóa học mới nhất của mỗi danh mục
                    }])
                    ->orderBy('order')
                    ->get();
            });
        } catch (\Exception $e) {
            $courseCategoriesData = collect();
        }
    }

    // Tối ưu hóa dữ liệu cho hiệu suất
    $hasData = isset($courseCategoriesData) && !empty($courseCategoriesData) && $courseCategoriesData->count() > 0;
    $placeholderImage = asset('images/course-placeholder.webp');
@endphp

@if($hasData)
<!-- Skeleton Loading State -->
<div id="courses-skeleton" class="container mx-auto px-4 relative z-10 hidden">
    <div class="flex flex-col items-center text-center mb-12">
        <div class="skeleton w-48 h-8 mb-4 rounded-full"></div>
        <div class="skeleton w-96 h-12 mb-6 rounded-lg"></div>
        <div class="skeleton w-full max-w-3xl h-6 rounded-lg"></div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @for($i = 0; $i < 3; $i++)
        <div class="bg-white rounded-2xl overflow-hidden shadow-lg">
            <div class="skeleton h-12"></div>
            <div class="skeleton aspect-[4/3]"></div>
            <div class="p-6 space-y-4">
                <div class="skeleton h-6 w-3/4"></div>
                <div class="skeleton h-4 w-full"></div>
                <div class="skeleton h-4 w-2/3"></div>
                <div class="flex gap-3">
                    <div class="skeleton h-10 flex-1"></div>
                    <div class="skeleton h-10 flex-1"></div>
                </div>
            </div>
        </div>
        @endfor
    </div>
</div>

<!-- Main Content với Progressive Enhancement -->
<div id="courses-content" class="container mx-auto px-4 relative z-10 opacity-0 transition-opacity duration-500">
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

        <!-- Stats Counter với dữ liệu thực từ database -->
        @php
            // Debug: Kiểm tra dữ liệu được truyền vào
            // dd('courseStats received:', $courseStats ?? 'not set');

            // Lấy stats từ service hoặc fallback với dữ liệu thực
            $stats = isset($courseStats) && is_array($courseStats) ? $courseStats : [
                'students' => 28,
                'courses' => 6,
                'satisfaction_rate' => 32,
                'experience_years' => 3
            ];

            // Đảm bảo tất cả keys tồn tại với dữ liệu thực
            $stats = array_merge([
                'students' => 28,
                'courses' => 6,
                'satisfaction_rate' => 32,
                'experience_years' => 3
            ], $stats);

            // Debug: Kiểm tra stats cuối cùng
            // dd('Final stats:', $stats);
        @endphp
        <!-- Stats display - Tối ưu và load nhanh -->
        <div class="grid grid-cols-3 gap-6 mt-8 w-full max-w-2xl">
            <div class="text-center">
                <div class="text-2xl md:text-3xl font-bold text-red-600 mb-1 counter-fast" data-target="{{ $stats['students'] ?? 28 }}">{{ $stats['students'] ?? 28 }}</div>
                <div class="text-xs md:text-sm text-gray-600 font-medium">Học viên</div>
            </div>
            <div class="text-center">
                <div class="text-2xl md:text-3xl font-bold text-red-600 mb-1 counter-fast" data-target="{{ $stats['courses'] ?? 6 }}">{{ $stats['courses'] ?? 6 }}</div>
                <div class="text-xs md:text-sm text-gray-600 font-medium">Khóa học</div>
            </div>
            <div class="text-center">
                <div class="text-2xl md:text-3xl font-bold text-red-600 mb-1 counter-fast" data-target="{{ $stats['experience_years'] ?? 3 }}">{{ $stats['experience_years'] ?? 3 }}</div>
                <div class="text-xs md:text-sm text-gray-600 font-medium">Giảng viên</div>
            </div>
        </div>
    </div>

    <!-- Course Categories Grid - Tối ưu layout -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($courseCategoriesData as $index => $category)
        @php
            // Xử lý dữ liệu từ ViewServiceProvider hoặc fallback query
            $latestCourse = null;
            if (isset($category->latest_course)) {
                $latestCourse = $category->latest_course;
            } elseif ($category->relationLoaded('courses') && $category->courses->isNotEmpty()) {
                $latestCourse = $category->courses->first();
            }

            // Font Awesome icon classes cho từng category
            $categoryIcons = [
                'ky-nang' => 'fas fa-check-circle',
                'ky-thuat' => 'fas fa-cog',
                'hoi-thao' => 'fas fa-users'
            ];
        @endphp

        @if($latestCourse)
        <!-- Course Card - Gọn gàng và tối ưu -->
        <article class="group relative bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100"
                 role="article"
                 aria-labelledby="course-{{ $latestCourse->id }}-title">

            <!-- Category Header - Gọn gàng -->
            <header class="relative bg-gradient-to-r from-red-500 to-red-600 p-4 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold mb-1">{{ $category->name }}</h3>
                        <p class="text-red-100 text-sm">{{ Str::limit($category->description ?? '', 40) }}</p>
                    </div>

                    <!-- Font Awesome Icon -->
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                        <i class="{{ $categoryIcons[$category->slug] ?? $categoryIcons['ky-nang'] }} text-lg"></i>
                    </div>
                </div>
            </header>

            <!-- Course Image Container - Với fallback icon -->
            <div class="relative overflow-hidden aspect-[4/3] bg-gray-50">
                @if($latestCourse->thumbnail)
                    <!-- Ảnh thực tế -->
                    <img src="{{ asset('storage/' . $latestCourse->thumbnail) }}"
                         alt="{{ $latestCourse->seo_title ?? $latestCourse->title }}"
                         class="w-full h-full object-cover course-image"
                         loading="lazy"
                         decoding="async"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                    <!-- Fallback UI khi ảnh lỗi -->
                    <div class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center fallback-icon" style="display: none;">
                        <div class="text-center">
                            <i class="fas fa-image text-4xl text-gray-400 mb-2"></i>
                            <p class="text-gray-500 text-sm font-medium">Ảnh không tồn tại</p>
                        </div>
                    </div>
                @else
                    <!-- Fallback UI khi không có ảnh -->
                    <div class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                        <div class="text-center">
                            <i class="fas fa-graduation-cap text-4xl text-gray-400 mb-2"></i>
                            <p class="text-gray-500 text-sm font-medium">Chưa có ảnh</p>
                        </div>
                    </div>
                @endif

                <!-- Badge hiển thị luôn -->
                <div class="absolute top-3 left-3 px-2 py-1 bg-red-600 text-white text-xs font-bold rounded-full shadow-lg">
                    Mới nhất
                </div>
            </div>

            <!-- Course Content - Gọn gàng và tối ưu -->
            <div class="p-6 space-y-4">
                <!-- Course Title -->
                <h4 id="course-{{ $latestCourse->id }}-title"
                   class="text-lg font-bold text-gray-900 line-clamp-2 leading-tight group-hover:text-red-600 transition-colors duration-300">
                    {{ $latestCourse->title }}
                </h4>

                <!-- Course Description -->
                <p class="text-gray-600 text-sm leading-relaxed line-clamp-2">
                    {{ $latestCourse->seo_description ?? Str::limit(strip_tags($latestCourse->description ?? ''), 100) }}
                </p>

                <!-- Action Buttons - Gọn gàng -->
                <div class="flex gap-2 pt-2">
                    <a href="{{ route('courses.show', $latestCourse->slug) }}"
                       class="flex-1 inline-flex items-center justify-center px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 hover:text-gray-900 text-sm font-medium rounded-xl transition-colors duration-200"
                       aria-label="Xem chi tiết khóa học {{ $latestCourse->title }}">
                        <span>Xem chi tiết</span>
                        <i class="fas fa-arrow-right ml-2 text-xs"></i>
                    </a>

                    @if($latestCourse->gg_form)
                        <a href="{{ $latestCourse->gg_form }}"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="flex-1 inline-flex items-center justify-center px-4 py-3 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-xl transition-colors duration-200"
                           aria-label="Đăng ký khóa học {{ $latestCourse->title }}">
                            <span>Đăng ký</span>
                            <i class="fas fa-external-link-alt ml-2 text-xs"></i>
                        </a>
                    @endif
                </div>

                <!-- Category Link - Gọn gàng -->
                <div class="pt-3 border-t border-gray-100">
                    <a href="{{ route('courses.cat-category', $category->slug) }}"
                       class="inline-flex items-center text-red-600 hover:text-red-700 text-sm font-medium transition-colors duration-200"
                       aria-label="Xem tất cả khóa học trong danh mục {{ $category->name }}">
                        <span>Xem tất cả {{ $category->name }}</span>
                        <i class="fas fa-arrow-right ml-1 text-xs"></i>
                    </a>
                </div>
            </div>
        </article>
        @endif
        @endforeach
    </div>

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

<!-- Performance Optimization Scripts -->
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Progressive Enhancement
    const coursesContent = document.getElementById('courses-content');
    const coursesSkeleton = document.getElementById('courses-skeleton');

    if (coursesContent) {
        // Show content with fade-in effect
        setTimeout(() => {
            coursesContent.style.opacity = '1';
            if (coursesSkeleton) {
                coursesSkeleton.style.display = 'none';
            }
        }, 100);

        // Fast Counter Animation - Tối ưu tốc độ
        const counters = document.querySelectorAll('.counter-fast');
        const observerOptions = {
            threshold: 0.3,
            rootMargin: '0px 0px -50px 0px'
        };

        const counterObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counter = entry.target;
                    const target = parseInt(counter.getAttribute('data-target'));
                    const duration = 800; // Giảm xuống 0.8 giây
                    const step = target / (duration / 16); // 60fps
                    let current = 0;

                    const updateCounter = () => {
                        current += step;
                        if (current < target) {
                            counter.textContent = Math.floor(current);
                            requestAnimationFrame(updateCounter);
                        } else {
                            counter.textContent = target;
                        }
                    };

                    // Bắt đầu từ 0 để có hiệu ứng
                    counter.textContent = '0';
                    updateCounter();
                    counterObserver.unobserve(counter);
                }
            });
        }, observerOptions);

        counters.forEach(counter => {
            counterObserver.observe(counter);
        });

        // Image Loading Optimization với fallback
        const images = document.querySelectorAll('img.course-image');
        images.forEach(img => {
            img.addEventListener('load', function() {
                this.style.opacity = '1';
            });

            img.addEventListener('error', function() {
                // Ẩn ảnh lỗi và hiện fallback icon
                this.style.display = 'none';
                const fallback = this.nextElementSibling;
                if (fallback && fallback.classList.contains('fallback-icon')) {
                    fallback.style.display = 'flex';
                }
            });
        });

        // Preload critical resources
        const criticalImages = document.querySelectorAll('img[src*="storage"]');
        criticalImages.forEach(img => {
            const link = document.createElement('link');
            link.rel = 'preload';
            link.as = 'image';
            link.href = img.src;
            document.head.appendChild(link);
        });
    }
});
</script>
@endpush