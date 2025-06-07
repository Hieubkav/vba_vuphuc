@php
    // Xử lý dữ liệu từ ViewServiceProvider hoặc fallback query
    $latestCourse = null;
    if (isset($category->latest_course)) {
        $latestCourse = $category->latest_course;
    } elseif ($category->relationLoaded('courses') && $category->courses->isNotEmpty()) {
        $latestCourse = $category->courses->first();
    }

    // Font Awesome icon classes cho từng category - Mở rộng cho tất cả danh mục
    $categoryIcons = [
        'ky-nang' => 'fas fa-check-circle',
        'ky-thuat' => 'fas fa-cog',
        'hoi-thao' => 'fas fa-users',
        'banh-co-ban' => 'fas fa-cookie-bite',
        'banh-nang-cao' => 'fas fa-birthday-cake',
        'workshop' => 'fas fa-tools'
    ];
    $defaultIcon = 'fas fa-graduation-cap';
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
                <i class="{{ $categoryIcons[$category->slug] ?? $defaultIcon }} text-lg"></i>
            </div>
        </div>
    </header>

    <!-- KISS: Ảnh với unified fallback UI -->
    <div class="h-48 bg-gray-100 relative">
        @if($latestCourse->thumbnail)
            <img src="{{ asset('storage/' . $latestCourse->thumbnail) }}"
                 alt="{{ $latestCourse->title }}"
                 class="w-full h-full object-cover"
                 loading="lazy"
                 onerror="handleImageError(this)">
        @else
            <div class="w-full h-full bg-gradient-to-br from-red-50 to-red-100 flex items-center justify-center">
                <div class="text-center">
                    <i class="fas fa-graduation-cap text-3xl text-red-300 mb-2"></i>
                    <p class="text-sm text-red-400">{{ Str::limit($latestCourse->title, 20) }}</p>
                </div>
            </div>
        @endif

        <!-- Badge đơn giản -->
        <div class="absolute top-3 left-3 bg-red-600 text-white text-xs px-2 py-1 rounded-full">
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

{{-- Global handler sẽ tự động xử lý .course-card-image --}}
@endif
