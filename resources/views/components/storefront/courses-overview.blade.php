@if(isset($courses) && !empty($courses) && $courses->count() > 0)
<div class="container mx-auto px-4">
    <div class="flex flex-col items-center text-center mb-10">
            <span class="text-red-600 font-semibold tracking-wider uppercase text-sm mb-2">Khóa học làm bánh</span>
            <h2 class="text-3xl md:text-4xl font-bold mb-4 text-gray-800">Khám Phá Nghệ Thuật Làm Bánh</h2>
            <p class="max-w-2xl text-gray-600">Tham gia các khóa học làm bánh đa dạng từ cơ bản đến nâng cao cùng Vũ Phúc Baking Academy.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($courses as $course)
            <div class="bg-white rounded-lg overflow-hidden shadow-lg transition-transform duration-300 hover:-translate-y-2">
                @if($course->thumbnail)
                    <img src="{{ asset('storage/' . $course->thumbnail) }}"
                         alt="{{ $course->seo_title ?? $course->title }}"
                         class="w-full h-56 object-cover">
                @else
                    <div class="w-full h-56 bg-gradient-to-br from-red-100 to-red-200 flex items-center justify-center">
                        <svg class="w-16 h-16 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                @endif
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-2 text-gray-800">{{ $course->title }}</h3>
                    <p class="text-gray-600 mb-4 line-clamp-2">
                        {{ $course->seo_description ?? Str::limit(strip_tags($course->content), 100) }}
                    </p>
                    <div class="flex justify-between items-center">
                        @if($course->category)
                            <span class="text-red-600 font-semibold text-sm">{{ $course->category->name }}</span>
                        @else
                            <span class="text-red-600 font-semibold text-sm">Khóa học</span>
                        @endif
                        <a href="{{ route('posts.show', $course->slug) }}"
                           class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">
                            Xem chi tiết
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-10">
            <a href="{{ route('posts.courses') }}"
               class="inline-block px-6 py-3 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition">
                Xem tất cả khóa học
            </a>
        </div>
    </div>
</div>
@endif