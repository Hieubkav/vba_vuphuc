@php
    // Xử lý dữ liệu từ ViewServiceProvider hoặc fallback query
    $latestCourse = null;
    if (isset($category->latest_course)) {
        $latestCourse = $category->latest_course;
    } elseif (is_object($category) && method_exists($category, 'relationLoaded') && $category->relationLoaded('courses') && $category->courses->isNotEmpty()) {
        $latestCourse = $category->courses->first();
    }
@endphp

@if($latestCourse)
<!-- Thẻ khóa học hiện đại - Giao diện đỏ trắng -->
<article class="group relative bg-white rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 border border-red-100/50 hover:border-red-200 backdrop-blur-sm"
         role="article"
         aria-labelledby="course-{{ $latestCourse->id }}-title"
         style="background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(254,242,242,0.3) 100%);">

    <!-- Tiêu đề danh mục hiện đại -->
    <header class="relative bg-gradient-to-br from-red-50 via-white to-red-50/50 p-6 border-b border-red-100/30">
        <div class="space-y-2">
            <!-- Nhãn danh mục gọn gàng -->
            <div class="inline-flex items-center px-3 py-1 bg-gradient-to-r from-red-600 to-red-700 text-white badge-text rounded-full shadow-md">
                <span class="font-semibold text-sm">{{ is_object($category) && isset($category->name) ? $category->name : 'Danh mục khóa học' }}</span>
            </div>

            @if(is_object($category) && isset($category->description) && $category->description)
                <p class="body-text text-gray-600 leading-relaxed text-sm">{{ Str::limit($category->description, 70) }}</p>
            @endif
        </div>
    </header>

    <!-- Hình ảnh khóa học hiện đại -->
    <div class="h-56 bg-gradient-to-br from-red-50 to-white relative overflow-hidden">
        @if($latestCourse->thumbnail)
            <img src="{{ asset('storage/' . $latestCourse->thumbnail) }}"
                 alt="{{ $latestCourse->title }}"
                 class="w-full h-full object-cover group-hover:scale-110 transition-all duration-700 filter group-hover:brightness-105"
                 loading="lazy"
                 onerror="handleImageError(this)">
            <!-- Lớp phủ hình ảnh -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
        @else
            <div class="w-full h-full bg-gradient-to-br from-red-50 via-white to-red-100/50 flex items-center justify-center relative">
                <div class="text-center space-y-4 z-10">
                    <div class="w-20 h-20 bg-gradient-to-br from-red-500 to-red-600 rounded-2xl flex items-center justify-center mx-auto shadow-lg">
                        <i class="fas fa-graduation-cap text-3xl text-white"></i>
                    </div>
                    <p class="caption-text text-gray-700 px-4 font-medium">{{ Str::limit($latestCourse->title, 35) }}</p>
                </div>
                <!-- Họa tiết nền -->
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute top-4 left-4 w-8 h-8 bg-red-300 rounded-full"></div>
                    <div class="absolute bottom-6 right-6 w-6 h-6 bg-red-400 rounded-full"></div>
                    <div class="absolute top-1/2 left-1/3 w-4 h-4 bg-red-200 rounded-full"></div>
                </div>
            </div>
        @endif

        <!-- Nhãn gọn gàng -->
        <div class="absolute top-6 right-6 bg-gradient-to-r from-red-600 to-red-700 text-white badge-text px-3 py-1 rounded-lg shadow-lg">
            <span class="font-bold text-xs">MỚI NHẤT</span>
        </div>
    </div>

    <!-- Nội dung khóa học hiện đại -->
    <div class="p-6 space-y-4">
        <!-- Tiêu đề khóa học -->
        <h4 id="course-{{ $latestCourse->id }}-title"
           class="card-title line-clamp-2 group-hover:text-red-600 transition-colors duration-300 leading-tight">
            {{ $latestCourse->title }}
        </h4>

        <!-- Mô tả khóa học -->
        <p class="body-text text-gray-600 line-clamp-3 leading-relaxed text-sm">
            {{ $latestCourse->seo_description ?? Str::limit(strip_tags($latestCourse->description ?? ''), 140) }}
        </p>

        <!-- Nút hành động gọn gàng -->
        <div class="flex flex-col sm:flex-row gap-2 pt-4">
            <a href="{{ route('courses.show', $latestCourse->slug) }}"
               class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-gray-50 hover:bg-red-50 text-gray-700 hover:text-red-700 btn-text rounded-lg transition-all duration-300 hover:shadow-md border border-gray-200 hover:border-red-200 text-sm"
               aria-label="Xem chi tiết khóa học {{ $latestCourse->title }}">
                <span class="font-medium">Xem chi tiết</span>
            </a>

            @if($latestCourse->gg_form)
                <a href="{{ $latestCourse->gg_form }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white btn-text rounded-lg transition-all duration-300 hover:shadow-lg text-sm"
                   aria-label="Đăng ký khóa học {{ $latestCourse->title }}">
                    <span class="font-semibold">Đăng ký ngay</span>
                </a>
            @endif
        </div>

        <!-- Liên kết danh mục gọn gàng -->
        @if(is_object($category) && isset($category->slug) && isset($category->name))
        <div class="pt-4 border-t border-red-100/50">
            <a href="{{ route('courses.cat-category', $category->slug) }}"
               class="inline-flex items-center justify-center w-full px-4 py-2 bg-white hover:bg-red-50 text-red-600 hover:text-red-700 btn-text rounded-lg transition-all duration-300 border border-red-100 hover:border-red-200 hover:shadow-md text-sm"
               aria-label="Xem tất cả khóa học trong danh mục {{ $category->name }}">
                <span class="font-medium">Xem tất cả {{ $category->name }}</span>
            </a>
        </div>
        @endif
    </div>
</article>

{{-- Bộ xử lý toàn cục sẽ tự động xử lý .course-card-image --}}
@endif
