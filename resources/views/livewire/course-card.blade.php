<!-- Course Card Component với Fallback UI đẹp mắt -->
<article class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-red-200"
         role="article"
         aria-labelledby="course-{{ $course->id }}-title">

    <!-- Course Image với Fallback UI thông minh -->
    <div class="relative overflow-hidden {{ $cardSize === 'small' ? 'aspect-[4/3]' : 'aspect-[16/10]' }} bg-gradient-to-br from-red-50 to-red-100">
        @if($mainImage && $mainImage !== asset('images/placeholder-course.jpg'))
            <img src="{{ $mainImage }}"
                 alt="{{ $course->seo_title ?? $course->title }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                 loading="lazy">
        @endif

        <!-- Fallback UI khi không có ảnh hoặc ảnh lỗi -->
        <div class="absolute inset-0 flex flex-col items-center justify-center text-red-600 {{ $mainImage && $mainImage !== asset('images/placeholder-course.jpg') ? 'hidden' : 'flex' }}"
             id="fallback-{{ $course->id }}">
            <div class="text-center space-y-3">
                <i class="fas fa-graduation-cap text-4xl opacity-60"></i>
                <div class="space-y-1">
                    <span class="block text-sm font-semibold opacity-80 line-clamp-1">{{ Str::limit($course->title, 20) }}</span>
                    <span class="block text-xs opacity-60">{{ $levelText }}</span>
                    @if($course->instructor && $course->show_instructor)
                    <span class="block text-xs opacity-50">{{ Str::limit($course->instructor->name, 15) }}</span>
                    @endif
                </div>
            </div>

            <!-- Decorative elements -->
            <div class="absolute top-4 right-4 w-8 h-8 bg-red-200 rounded-full opacity-30"></div>
            <div class="absolute bottom-4 left-4 w-6 h-6 bg-red-300 rounded-full opacity-20"></div>
            <div class="absolute top-1/2 left-4 w-4 h-4 bg-red-400 rounded-full opacity-15"></div>
        </div>

        <!-- Course Level Badge -->
        @if($course->level)
        <div class="absolute top-4 left-4 z-10">
            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $levelBadgeColor }}">
                {{ $levelText }}
            </span>
        </div>
        @endif


    </div>

    <!-- Course Content -->
    <div class="p-6 {{ $cardSize === 'small' ? 'p-4' : 'p-6' }}">
        <!-- Course Category -->
        @if($course->category)
        <div class="mb-3">
            <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded">
                {{ $course->category->name }}
            </span>
        </div>
        @endif

        <!-- Course Title -->
        <h3 id="course-{{ $course->id }}-title"
            class="font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-red-600 transition-colors duration-300 {{ $cardSize === 'small' ? 'text-lg' : 'text-xl' }}">
            <a href="{{ route('courses.show', $course->slug) }}" class="hover:underline">
                {{ $course->title }}
            </a>
        </h3>

        <!-- Course Description -->
        @if($shortDescription)
        <p class="text-gray-600 text-sm mb-4 line-clamp-3">
            {{ $shortDescription }}
        </p>
        @endif

        <!-- Course Meta Info -->
        <div class="flex flex-wrap items-center gap-4 text-xs text-gray-500 mb-4">
            @if($course->instructor && $course->show_instructor)
            <div class="flex items-center">
                <i class="fas fa-user-tie mr-1"></i>
                @if($course->instructor->slug)
                    <a href="{{ route('instructors.show', $course->instructor->slug) }}"
                       class="hover:text-red-600 transition-colors duration-300">
                        {{ $course->instructor->name }}
                    </a>
                @else
                    <span>{{ $course->instructor->name }}</span>
                @endif
            </div>
            @endif

            @if($course->duration_hours)
            <div class="flex items-center">
                <i class="fas fa-clock mr-1"></i>
                <span>{{ $course->duration_hours }}h</span>
            </div>
            @endif

            @if($course->max_students)
            <div class="flex items-center">
                <i class="fas fa-users mr-1"></i>
                <span>{{ $course->max_students }} chỗ</span>
            </div>
            @endif
        </div>

        <!-- Action -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-500">Liên hệ để biết giá</span>
            </div>

            <!-- View Details Button -->
            <a href="{{ route('courses.show', $course->slug) }}"
               class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors duration-300 flex items-center">
                <span>Chi tiết</span>
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</article>
