@php
    use Carbon\Carbon;

    // Sử dụng dữ liệu từ ViewServiceProvider với fallback
    $featuredCoursesData = $featuredCourses ?? collect();

    // Fallback: nếu không có dữ liệu từ ViewServiceProvider, lấy trực tiếp từ model
    if ($featuredCoursesData->isEmpty()) {
        try {
            $featuredCoursesData = \App\Models\Course::where('status', 'active')
                ->where('is_featured', true)
                ->with([
                    'courseCategory:id,name,slug',
                    'instructor:id,name',
                    'images' => function($q) {
                        $q->where('status', 'active')->orderBy('is_main', 'desc')->take(1);
                    }
                ])
                ->select([
                    'id', 'title', 'slug', 'description', 'price', 'compare_price',
                    'level', 'thumbnail', 'cat_course_id', 'instructor_id', 'is_featured',
                    'order', 'created_at'
                ])
                ->orderBy('order')
                ->orderBy('created_at', 'desc')
                ->take(4)
                ->get();
        } catch (\Exception $e) {
            $featuredCoursesData = collect();
        }
    }

    $coursesCount = $featuredCoursesData->count();

    // Lấy khóa học nổi bật nhất (khóa học mới nhất) nếu có dữ liệu
    $featuredCourse = $featuredCoursesData->isNotEmpty() ? $featuredCoursesData->first() : null;
    // Lấy tối đa 3 khóa học còn lại (tổng cộng 4 khóa học)
    $remainingCourses = $featuredCoursesData->isNotEmpty() ? $featuredCoursesData->slice(1, 3) : collect();
@endphp

@if($coursesCount > 0)
<div class="container mx-auto px-4 relative max-w-5xl">
    <!-- Tiêu đề minimalist -->
    <div class="text-center mb-12 md:mb-16 max-w-4xl mx-auto">
        <h2 class="text-2xl md:text-3xl lg:text-4xl font-light text-gray-900 mb-4 tracking-wide">Khóa học nổi bật</h2>
        <p class="text-gray-500 text-base md:text-lg max-w-3xl mx-auto leading-relaxed">Những khóa học được yêu thích và đánh giá cao nhất từ VBA Vũ Phúc</p>
    </div>

        <!-- Desktop View với Featured Course + Grid layout -->
        <div class="hidden md:block">
            <!-- Featured Course Section - Khóa học nổi bật -->
            @if($featuredCourse)
            <div class="mb-12 md:mb-16 max-w-4xl mx-auto">
                <div class="grid md:grid-cols-5 gap-8 md:gap-12 items-center bg-white rounded-3xl shadow-sm overflow-hidden border border-gray-50">
                    <div class="md:col-span-3 relative overflow-hidden">
                        @if(isset($featuredCourse->thumbnail) && !empty($featuredCourse->thumbnail))
                        <div class="relative h-64 md:h-80 overflow-hidden rounded-2xl md:rounded-none md:rounded-l-3xl">
                            <img
                                data-src="{{ asset('storage/' . $featuredCourse->thumbnail) }}"
                                alt="{{ $featuredCourse->title ?? 'Khóa học VBA Vũ Phúc' }}"
                                class="w-full h-full object-cover course-thumbnail lazy-loading"
                                loading="lazy"
                                onerror="handleImageError(this)"
                                style="opacity: 0; transition: opacity 0.3s ease, filter 0.3s ease, transform 0.3s ease;">
                            <div class="absolute top-4 right-4">
                                <span class="bg-white/90 backdrop-blur-sm text-gray-700 text-xs px-3 py-1.5 rounded-full font-medium shadow-sm">
                                    Nổi bật
                                </span>
                            </div>
                        </div>
                        @else
                        <div class="bg-gradient-to-br from-red-50 to-red-100 h-64 md:h-80 flex items-center justify-center rounded-2xl md:rounded-none md:rounded-l-3xl">
                            <i class="fas fa-graduation-cap text-4xl text-red-300"></i>
                        </div>
                        @endif
                    </div>
                    <div class="md:col-span-2 p-6 md:p-8 lg:p-10">
                        <div class="flex flex-wrap items-center gap-3 mb-4">
                            @if($featuredCourse->relationLoaded('category') && $featuredCourse->category)
                            <span class="text-xs bg-gray-50 text-gray-600 py-2 px-3 rounded-full font-medium">
                                {{ $featuredCourse->category->name }}
                            </span>
                            @elseif($featuredCourse->relationLoaded('courseCategory') && $featuredCourse->courseCategory)
                            <span class="text-xs bg-gray-50 text-gray-600 py-2 px-3 rounded-full font-medium">
                                {{ $featuredCourse->courseCategory->name }}
                            </span>
                            @endif
                            @if(isset($featuredCourse->level) && !empty($featuredCourse->level))
                            <span class="text-xs bg-gray-50 text-gray-600 py-2 px-3 rounded-full font-medium">
                                {{ ucfirst($featuredCourse->level) }}
                            </span>
                            @endif
                        </div>
                        @if(isset($featuredCourse->slug))
                        <a href="{{ route('courses.show', $featuredCourse->slug) }}" class="block group">
                            <h3 class="text-xl md:text-2xl lg:text-3xl font-light text-gray-900 mb-4 leading-tight group-hover:text-gray-700 transition-colors">{{ $featuredCourse->title ?? 'Khóa học mới' }}</h3>
                        </a>
                        @else
                        <h3 class="text-xl md:text-2xl lg:text-3xl font-light text-gray-900 mb-4 leading-tight">{{ $featuredCourse->title ?? 'Khóa học mới' }}</h3>
                        @endif
                        @if(isset($featuredCourse->seo_description) && !empty($featuredCourse->seo_description))
                        <p class="text-gray-500 mb-6 line-clamp-3 text-base leading-relaxed">
                            {{ Str::limit(strip_tags($featuredCourse->seo_description), 160) }}
                        </p>
                        @elseif(isset($featuredCourse->description) && !empty($featuredCourse->description))
                        <p class="text-gray-500 mb-6 line-clamp-3 text-base leading-relaxed">
                            {{ Str::limit(strip_tags($featuredCourse->description), 160) }}
                        </p>
                        @endif
                        <div class="flex items-center justify-between">
                            @if(isset($featuredCourse->slug))
                            <a href="{{ route('courses.show', $featuredCourse->slug) }}" class="inline-flex items-center text-gray-900 hover:text-gray-700 font-medium text-sm group">
                                <span>Xem chi tiết</span>
                                <i class="fas fa-arrow-right ml-2 transition-transform group-hover:translate-x-1"></i>
                            </a>
                            @endif
                            @if(isset($featuredCourse->price) && $featuredCourse->price > 0)
                            <div class="text-right">
                                <span class="text-lg md:text-xl font-light text-gray-900">{{ number_format($featuredCourse->price) }}đ</span>
                                @if(isset($featuredCourse->compare_price) && $featuredCourse->compare_price > $featuredCourse->price)
                                <span class="text-sm text-gray-400 line-through ml-2">{{ number_format($featuredCourse->compare_price) }}đ</span>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Content Grid - Chỉ hiển thị grid, không có carousel -->
            @if($remainingCourses->count() > 0)
                <!-- Grid layout cho các khóa học còn lại -->
                <div class="max-w-4xl mx-auto">
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                        @foreach($remainingCourses as $course)
                            <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-50 group">
                                @if(isset($course->slug))
                                <a href="{{ route('courses.show', $course->slug) }}" class="block">
                                @else
                                <div class="block">
                                @endif
                                    <div class="h-48 md:h-52 overflow-hidden relative">
                                        @if(isset($course->thumbnail) && !empty($course->thumbnail))
                                            <img
                                                data-src="{{ asset('storage/' . $course->thumbnail) }}"
                                                alt="{{ $course->title ?? 'Khóa học VBA Vũ Phúc' }}"
                                                class="w-full h-full object-cover course-thumbnail lazy-loading"
                                                loading="lazy"
                                                onerror="handleImageError(this)"
                                                style="opacity: 0; transition: opacity 0.3s ease, filter 0.3s ease, transform 0.3s ease;"
                                            >
                                            <div class="w-full h-full bg-gradient-to-br from-red-50 to-red-100 flex flex-col items-center justify-center image-fallback" style="display: none;">
                                                <i class="fas fa-graduation-cap text-2xl text-red-300 mb-1"></i>
                                                <span class="text-red-400 text-xs font-light">Khóa học</span>
                                            </div>
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-red-50 to-red-100 flex flex-col items-center justify-center">
                                                <i class="fas fa-graduation-cap text-2xl text-red-300 mb-1"></i>
                                                <span class="text-red-400 text-xs font-light">Khóa học</span>
                                            </div>
                                        @endif
                                    </div>
                                @if(isset($course->slug))
                                </a>
                                @else
                                </div>
                                @endif
                                <div class="p-5 md:p-6">
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
                                        <h3 class="text-lg font-light text-gray-900 mb-3 group-hover:text-gray-700 transition-colors line-clamp-2 leading-snug">{{ $course->title ?? 'Khóa học mới' }}</h3>
                                    </a>
                                    @else
                                    <h3 class="text-lg font-light text-gray-900 mb-3 line-clamp-2 leading-snug">{{ $course->title ?? 'Khóa học mới' }}</h3>
                                    @endif
                                    @if(isset($course->seo_description) && !empty($course->seo_description))
                                    <p class="text-gray-500 mb-4 line-clamp-2 text-sm leading-relaxed">
                                        {{ Str::limit(strip_tags($course->seo_description), 90) }}
                                    </p>
                                    @elseif(isset($course->description) && !empty($course->description))
                                    <p class="text-gray-500 mb-4 line-clamp-2 text-sm leading-relaxed">
                                        {{ Str::limit(strip_tags($course->description), 90) }}
                                    </p>
                                    @endif
                                    <div class="flex items-center justify-between pt-2 border-t border-gray-50">
                                        @if(isset($course->slug))
                                        <a href="{{ route('courses.show', $course->slug) }}" class="inline-flex items-center text-gray-900 hover:text-gray-700 font-medium text-sm group">
                                            <span>Xem khóa học</span>
                                            <i class="fas fa-arrow-right ml-2 transition-transform group-hover:translate-x-1"></i>
                                        </a>
                                        @endif
                                        @if(isset($course->price) && $course->price > 0)
                                        <span class="text-sm font-light text-gray-900">{{ number_format($course->price) }}đ</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        </div>
                    </div>
            @endif
        </div>

        <!-- Mobile View - Thiết kế thẻ khóa học tối ưu -->
        <div class="md:hidden max-w-sm mx-auto">
            <div class="overflow-hidden">
                <!-- Featured course trên mobile -->
                @if($featuredCourse)
                <div class="mb-5 bg-white rounded-lg overflow-hidden shadow-sm border border-gray-100">
                    <div class="relative">
                        @if(isset($featuredCourse->thumbnail) && !empty($featuredCourse->thumbnail))
                            <div class="h-48 overflow-hidden">
                                <img
                                    data-src="{{ asset('storage/' . $featuredCourse->thumbnail) }}"
                                    alt="{{ $featuredCourse->title ?? 'Khóa học VBA Vũ Phúc' }}"
                                    class="w-full h-full object-cover course-thumbnail lazy-loading"
                                    loading="lazy"
                                    onerror="handleImageError(this)"
                                    style="opacity: 0; transition: opacity 0.3s ease, filter 0.3s ease, transform 0.3s ease;"
                                >
                                <div class="w-full h-full bg-gradient-to-br from-red-50 to-red-100 flex items-center justify-center image-fallback" style="display: none;">
                                    <i class="fas fa-graduation-cap text-4xl text-red-300"></i>
                                </div>
                                <div class="absolute top-2 right-2 bg-red-600 text-white text-xs px-2 py-1 rounded-full font-medium">
                                    Nổi bật
                                </div>
                            </div>
                        @else
                            <div class="h-48 bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center">
                                <i class="fas fa-graduation-cap text-4xl text-white/70"></i>
                            </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <div class="flex flex-wrap items-center mb-2 text-xs gap-2">
                            @if($featuredCourse->relationLoaded('category') && $featuredCourse->category)
                            <span class="inline-flex items-center bg-red-50 text-red-600 py-1 px-2 rounded-full">
                                <i class="fas fa-tag mr-1"></i>
                                {{ $featuredCourse->category->name }}
                            </span>
                            @elseif($featuredCourse->relationLoaded('courseCategory') && $featuredCourse->courseCategory)
                            <span class="inline-flex items-center bg-red-50 text-red-600 py-1 px-2 rounded-full">
                                <i class="fas fa-tag mr-1"></i>
                                {{ $featuredCourse->courseCategory->name }}
                            </span>
                            @endif
                            @if(isset($featuredCourse->level) && !empty($featuredCourse->level))
                            <span class="inline-flex items-center bg-gray-100 text-gray-600 py-1 px-2 rounded-full">
                                <i class="fas fa-signal mr-1"></i>
                                {{ ucfirst($featuredCourse->level) }}
                            </span>
                            @endif
                        </div>
                        @if(isset($featuredCourse->slug))
                        <a href="{{ route('courses.show', $featuredCourse->slug) }}">
                            <h3 class="text-base font-bold text-gray-900 mb-2 line-clamp-2 leading-tight">{{ $featuredCourse->title ?? 'Khóa học mới' }}</h3>
                        </a>
                        @else
                        <h3 class="text-base font-bold text-gray-900 mb-2 line-clamp-2 leading-tight">{{ $featuredCourse->title ?? 'Khóa học mới' }}</h3>
                        @endif
                        @if(isset($featuredCourse->seo_description) && !empty($featuredCourse->seo_description))
                        <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                            {{ Str::limit(strip_tags($featuredCourse->seo_description), 100) }}
                        </p>
                        @elseif(isset($featuredCourse->description) && !empty($featuredCourse->description))
                        <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                            {{ Str::limit(strip_tags($featuredCourse->description), 100) }}
                        </p>
                        @endif
                        <div class="flex items-center justify-between">
                            @if(isset($featuredCourse->slug))
                            <a href="{{ route('courses.show', $featuredCourse->slug) }}" class="inline-flex items-center text-red-600 font-medium text-sm">
                                <span>Xem chi tiết</span>
                                <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                            @endif
                            @if(isset($featuredCourse->price) && $featuredCourse->price > 0)
                            <div class="text-right">
                                <span class="text-base font-bold text-red-600">{{ number_format($featuredCourse->price) }}đ</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <!-- Remaining courses trong grid -->
                @if($remainingCourses->count() > 0)
                <div class="grid grid-cols-1 gap-4 mt-4">
                    @foreach($remainingCourses as $course)
                        <div class="bg-white rounded-lg overflow-hidden shadow-sm border border-gray-100">
                            @if(isset($course->slug))
                            <a href="{{ route('courses.show', $course->slug) }}" class="block">
                            @else
                            <div class="block">
                            @endif
                                <div class="h-32 overflow-hidden">
                                    @if(isset($course->thumbnail) && !empty($course->thumbnail))
                                        <img
                                            data-src="{{ asset('storage/' . $course->thumbnail) }}"
                                            alt="{{ $course->title ?? 'Khóa học VBA Vũ Phúc' }}"
                                            class="w-full h-full object-cover course-thumbnail lazy-loading"
                                            loading="lazy"
                                            onerror="handleImageError(this)"
                                            style="opacity: 0; transition: opacity 0.3s ease, filter 0.3s ease, transform 0.3s ease;"
                                        >
                                        <div class="w-full h-full bg-gradient-to-br from-red-50 to-red-100 flex items-center justify-center image-fallback" style="display: none;">
                                            <i class="fas fa-graduation-cap text-3xl text-red-300"></i>
                                        </div>
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-red-50 to-red-100 flex items-center justify-center">
                                            <i class="fas fa-graduation-cap text-3xl text-red-300"></i>
                                        </div>
                                    @endif
                                </div>
                            @if(isset($course->slug))
                            </a>
                            @else
                            </div>
                            @endif
                            <div class="p-3">
                                <div class="flex flex-wrap items-center text-xs gap-1 mb-2">
                                    @if($course->relationLoaded('category') && $course->category)
                                    <span class="bg-red-50 text-red-600 px-1.5 py-0.5 rounded-full">{{ $course->category->name }}</span>
                                    @elseif($course->relationLoaded('courseCategory') && $course->courseCategory)
                                    <span class="bg-red-50 text-red-600 px-1.5 py-0.5 rounded-full">{{ $course->courseCategory->name }}</span>
                                    @endif
                                    @if(isset($course->level) && !empty($course->level))
                                    <span class="text-gray-500">{{ ucfirst($course->level) }}</span>
                                    @endif
                                </div>
                                @if(isset($course->slug))
                                <a href="{{ route('courses.show', $course->slug) }}">
                                    <h3 class="text-sm font-medium text-gray-900 mb-2 line-clamp-2 leading-tight">{{ $course->title ?? 'Khóa học mới' }}</h3>
                                </a>
                                @else
                                <h3 class="text-sm font-medium text-gray-900 mb-2 line-clamp-2 leading-tight">{{ $course->title ?? 'Khóa học mới' }}</h3>
                                @endif
                                <div class="flex items-center justify-between">
                                    @if(isset($course->slug))
                                    <a href="{{ route('courses.show', $course->slug) }}" class="inline-flex items-center text-xs text-red-600 font-medium">
                                        <span>Xem khóa học</span>
                                        <i class="fas fa-arrow-right ml-1"></i>
                                    </a>
                                    @endif
                                    @if(isset($course->price) && $course->price > 0)
                                    <span class="text-xs font-bold text-red-600">{{ number_format($course->price) }}đ</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        <!-- CTA nút xem tất cả khóa học -->
        @if($coursesCount > 0)
            <div class="text-center mt-12 md:mt-16 max-w-4xl mx-auto">
                @if(Route::has('courses.index'))
                    <a href="{{ route('courses.index') }}" class="inline-flex items-center px-8 py-3 bg-gray-900 text-white font-light rounded-full hover:bg-gray-800 transition-all duration-300">
                        <span>Xem tất cả khóa học</span>
                        <i class="fas fa-arrow-right ml-3"></i>
                    </a>
                @else
                    <div class="inline-flex items-center px-8 py-3 bg-gray-300 text-gray-600 font-light rounded-full cursor-not-allowed">
                        <span>Tính năng đang phát triển</span>
                    </div>
                @endif
            </div>

            <!-- Fallback message khi có ít khóa học -->
            @if($coursesCount < 3)
                <div class="text-center mt-8">
                    <div class="inline-flex items-center px-4 py-2 bg-gray-50 text-gray-500 rounded-full text-sm font-light">
                        <i class="fas fa-info-circle mr-2"></i>
                        <span>Chúng tôi đang cập nhật thêm nhiều khóa học mới</span>
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>
@else
<!-- Fallback UI khi không có khóa học -->
<div class="container mx-auto px-4 relative max-w-5xl">
    <!-- Tiêu đề vẫn hiển thị -->
    <div class="text-center mb-12 md:mb-16 max-w-4xl mx-auto">
        <h2 class="text-2xl md:text-3xl lg:text-4xl font-light text-gray-900 mb-4 tracking-wide">Khóa học nổi bật</h2>
        <p class="text-gray-500 text-base md:text-lg max-w-3xl mx-auto leading-relaxed">Những khóa học được yêu thích và đánh giá cao nhất từ VBA Vũ Phúc</p>
    </div>

    <!-- Fallback Content -->
    <div class="max-w-3xl mx-auto text-center">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-50 p-12 md:p-16 fallback-card">
            <!-- Icon -->
            <div class="w-24 h-24 mx-auto mb-8 bg-gray-50 rounded-full flex items-center justify-center">
                <i class="fas fa-graduation-cap text-4xl text-gray-400"></i>
            </div>

            <!-- Heading -->
            <h3 class="text-2xl md:text-3xl font-light text-gray-900 mb-6">
                Khóa học đang được cập nhật
            </h3>

            <!-- Description -->
            <p class="text-gray-500 mb-8 leading-relaxed text-lg">
                Chúng tôi đang chuẩn bị những khóa học chất lượng cao để chia sẻ với bạn.
                Hãy quay lại sau để không bỏ lỡ những khóa học hữu ích nhé!
            </p>

            <!-- Action buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                @if(Route::has('posts.index'))
                    <a href="{{ route('posts.index') }}" class="inline-flex items-center px-8 py-3 bg-gray-900 text-white font-light rounded-full hover:bg-gray-800 transition-all duration-300">
                        <span>Xem bài viết</span>
                        <i class="fas fa-arrow-right ml-3"></i>
                    </a>
                @endif
                <button onclick="window.location.reload()" class="inline-flex items-center px-8 py-3 bg-white text-gray-600 font-light rounded-full border border-gray-200 hover:bg-gray-50 transition-all duration-300">
                    <i class="fas fa-refresh mr-3"></i>
                    <span>Tải lại trang</span>
                </button>
            </div>
        </div>

        <!-- Decorative elements -->
        <div class="mt-12 flex justify-center space-x-3">
            <div class="w-2 h-2 bg-gray-200 rounded-full animate-pulse"></div>
            <div class="w-2 h-2 bg-gray-300 rounded-full animate-pulse" style="animation-delay: 0.2s;"></div>
            <div class="w-2 h-2 bg-gray-400 rounded-full animate-pulse" style="animation-delay: 0.4s;"></div>
        </div>
    </div>
</div>
@endif

@push('styles')
<style>

    /* Fallback image placeholder */
    .course-placeholder {
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 50%, #e5e7eb 100%);
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
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.6), transparent);
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

    /* Smooth transitions */
    .group {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Curved sections */
    .course-section {
        position: relative;
    }

    .course-section::before {
        content: '';
        position: absolute;
        top: -50px;
        left: 0;
        right: 0;
        height: 50px;
        background: linear-gradient(135deg, transparent 49%, #f9fafb 50%);
        z-index: 1;
    }

    .course-section::after {
        content: '';
        position: absolute;
        bottom: -50px;
        left: 0;
        right: 0;
        height: 50px;
        background: linear-gradient(315deg, transparent 49%, #f9fafb 50%);
        z-index: 1;
    }

    /* Fallback UI animations */
    .fallback-card {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .fallback-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .course-desktop-carousel,
        .course-mobile-carousel {
            padding: 0 0.5rem;
        }

        .fallback-card {
            padding: 2rem;
        }

        .course-section::before,
        .course-section::after {
            height: 30px;
        }

        .course-section::before {
            top: -30px;
        }

        .course-section::after {
            bottom: -30px;
        }
    }

    /* Smooth scroll behavior */
    html {
        scroll-behavior: smooth;
    }
</style>
@endpush

{{-- Global handler sẽ tự động xử lý .course-image --}}
