@php
    use Carbon\Carbon;

    // Sử dụng dữ liệu từ ViewServiceProvider với fallback
    $newsPostsData = $newsPosts ?? collect();

    // Fallback: nếu không có dữ liệu từ ViewServiceProvider, lấy trực tiếp từ model
    if ($newsPostsData->isEmpty()) {
        try {
            $newsPostsData = \App\Models\Post::where('status', 'active')
                ->where('type', 'news')
                ->with(['category', 'images'])
                ->orderBy('order')
                ->orderBy('created_at', 'desc')
                ->take(4)
                ->get();
        } catch (\Exception $e) {
            $newsPostsData = collect();
        }
    }

    $postsCount = $newsPostsData->count();

    // Lấy bài viết nổi bật nhất (bài mới nhất) nếu có dữ liệu
    $featuredPost = $newsPostsData->isNotEmpty() ? $newsPostsData->first() : null;
    // Lấy tối đa 3 bài viết còn lại (tổng cộng 4 bài)
    $remainingPosts = $newsPostsData->isNotEmpty() ? $newsPostsData->slice(1, 3) : collect();
@endphp

@if($postsCount > 0)
<div class="container mx-auto px-4 relative max-w-5xl">
    <!-- Tiêu đề minimalist với hiệu ứng đẹp -->
    <div class="text-center mb-8 relative">
        <div class="inline-block relative">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">Tin tức & Sự kiện</h2>
            <div class="w-16 h-0.5 bg-red-600 mx-auto rounded-full"></div>
        </div>
        <p class="text-gray-600 text-sm md:text-base max-w-xl mx-auto mt-3">Cập nhật những tin tức nổi bật và sự kiện mới nhất</p>
    </div>

        <!-- Desktop View với Featured Post + Grid layout -->
        <div class="hidden md:block">
            <!-- Featured Post Section - Bài viết nổi bật -->
            @if($featuredPost)
            <div class="mb-8 group max-w-4xl mx-auto">
                <div class="grid md:grid-cols-5 gap-6 items-center bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
                    <div class="md:col-span-3 relative overflow-hidden">
                        @if(isset($featuredPost->thumbnail) && !empty($featuredPost->thumbnail))
                        <div class="relative h-72 overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-r from-red-600/60 to-transparent z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            <img
                                data-src="{{ asset('storage/' . $featuredPost->thumbnail) }}"
                                alt="{{ $featuredPost->title ?? 'Tin tức Vũ Phúc Baking' }}"
                                class="w-full h-full object-cover transform transition-transform duration-700 group-hover:scale-105 news-image post-thumbnail lazy-loading"
                                loading="lazy"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                style="opacity: 0; transition: opacity 0.3s ease, filter 0.3s ease, transform 0.3s ease;"
                            >
                            <div class="w-full h-full bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center news-placeholder" style="display: none;">
                                <i class="fas fa-newspaper text-4xl text-white/70"></i>
                            </div>
                            <div class="absolute top-3 left-3 z-20">
                                <span class="bg-red-600 text-white text-xs px-2.5 py-1 rounded-full font-medium">
                                    Nổi bật
                                </span>
                            </div>
                        </div>
                        @else
                        <div class="bg-gradient-to-br from-red-500 to-red-700 h-72 flex items-center justify-center news-placeholder">
                            <i class="fas fa-newspaper text-4xl text-white/70"></i>
                        </div>
                        @endif
                    </div>
                    <div class="md:col-span-2 p-6">
                        <div class="flex flex-wrap items-center gap-2 mb-3">
                            @if(isset($featuredPost->created_at))
                            <span class="inline-flex items-center text-xs bg-gray-100 text-gray-600 py-1 px-2 rounded-full">
                                <i class="far fa-calendar-alt mr-1"></i>
                                {{ Carbon::parse($featuredPost->created_at)->translatedFormat('d/m/Y') }}
                            </span>
                            @endif
                            @if(isset($featuredPost->category) && !empty($featuredPost->category))
                                <span class="inline-flex items-center text-xs bg-red-50 text-red-600 py-1 px-2 rounded-full">
                                    <i class="fas fa-tag mr-1"></i>
                                    {{ $featuredPost->category->name }}
                                </span>
                            @endif
                        </div>
                        @if(isset($featuredPost->slug))
                        <a href="{{ route('posts.show', $featuredPost->slug) }}" class="block group">
                            <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-red-600 transition-colors line-clamp-2 leading-tight">{{ $featuredPost->title ?? 'Tin tức mới' }}</h3>
                        </a>
                        @else
                        <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 leading-tight">{{ $featuredPost->title ?? 'Tin tức mới' }}</h3>
                        @endif
                        @if(isset($featuredPost->content) && !empty($featuredPost->content))
                        <p class="text-gray-600 mb-4 line-clamp-3 text-sm leading-relaxed">
                            {{ Str::limit(strip_tags($featuredPost->content), 160) }}
                        </p>
                        @endif
                        @if(isset($featuredPost->slug))
                        <a href="{{ route('posts.show', $featuredPost->slug) }}" class="inline-flex items-center text-red-600 hover:text-red-700 font-medium text-sm group">
                            <span>Đọc chi tiết</span>
                            <i class="fas fa-arrow-right ml-2 transform transition-transform group-hover:translate-x-1"></i>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Content Grid - Chỉ hiển thị grid, không có carousel -->
            @if($remainingPosts->count() > 0)
                <!-- Grid layout cho các bài viết còn lại -->
                <div class="grid md:grid-cols-3 gap-4 max-w-4xl mx-auto">
                        @foreach($remainingPosts as $post)
                            <div class="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-lg transition-all group hover:-translate-y-1 duration-300 border border-gray-100">
                                @if(isset($post->slug))
                                <a href="{{ route('posts.show', $post->slug) }}" class="block">
                                @else
                                <div class="block">
                                @endif
                                    <div class="h-44 overflow-hidden relative">
                                        @if(isset($post->thumbnail) && !empty($post->thumbnail))
                                            <img
                                                data-src="{{ asset('storage/' . $post->thumbnail) }}"
                                                alt="{{ $post->title ?? 'Tin tức Vũ Phúc Baking' }}"
                                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 news-image post-thumbnail lazy-loading"
                                                loading="lazy"
                                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                                style="opacity: 0; transition: opacity 0.3s ease, filter 0.3s ease, transform 0.3s ease;"
                                            >
                                            <div class="w-full h-full bg-gradient-to-br from-red-100 to-red-50 flex items-center justify-center news-placeholder" style="display: none;">
                                                <i class="fas fa-newspaper text-3xl text-red-300"></i>
                                            </div>
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-red-100 to-red-50 flex flex-col items-center justify-center">
                                                <i class="fas fa-newspaper text-3xl text-red-300 mb-1"></i>
                                                <span class="text-red-400 text-xs font-medium">Tin tức</span>
                                            </div>
                                        @endif
                                    </div>
                                @if(isset($post->slug))
                                </a>
                                @else
                                </div>
                                @endif
                                <div class="p-4">
                                    <div class="flex flex-wrap items-center gap-2 mb-2 text-xs">
                                        @if(isset($post->created_at))
                                        <span class="text-gray-500">{{ Carbon::parse($post->created_at)->translatedFormat('d/m/Y') }}</span>
                                        @endif
                                        @if(isset($post->category) && !empty($post->category))
                                            <span class="bg-red-50 text-red-600 px-2 py-0.5 rounded-full">{{ $post->category->name }}</span>
                                        @endif
                                    </div>
                                    @if(isset($post->slug))
                                    <a href="{{ route('posts.show', $post->slug) }}">
                                        <h3 class="text-base font-semibold text-gray-900 mb-2 group-hover:text-red-600 transition-colors line-clamp-2 leading-tight">{{ $post->title ?? 'Tin tức mới' }}</h3>
                                    </a>
                                    @else
                                    <h3 class="text-base font-semibold text-gray-900 mb-2 line-clamp-2 leading-tight">{{ $post->title ?? 'Tin tức mới' }}</h3>
                                    @endif
                                    @if(isset($post->content) && !empty($post->content))
                                    <p class="text-gray-600 mb-3 line-clamp-2 text-sm">
                                        {{ Str::limit(strip_tags($post->content), 90) }}
                                    </p>
                                    @endif
                                    @if(isset($post->slug))
                                    <a href="{{ route('posts.show', $post->slug) }}" class="inline-flex items-center text-red-600 hover:text-red-700 font-medium text-sm group">
                                        <span>Đọc tiếp</span>
                                        <i class="fas fa-arrow-right ml-1 transform transition-transform group-hover:translate-x-1"></i>
                                    </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
            @endif
        </div>

        <!-- Mobile View - Thiết kế thẻ bài viết tối ưu -->
        <div class="md:hidden max-w-sm mx-auto">
            <div class="overflow-hidden">
                <!-- Featured post trên mobile -->
                @if($featuredPost)
                <div class="mb-5 bg-white rounded-lg overflow-hidden shadow-sm border border-gray-100">
                    <div class="relative">
                        @if(isset($featuredPost->thumbnail) && !empty($featuredPost->thumbnail))
                            <div class="h-48 overflow-hidden">
                                <img
                                    data-src="{{ asset('storage/' . $featuredPost->thumbnail) }}"
                                    alt="{{ $featuredPost->title ?? 'Tin tức Vũ Phúc Baking' }}"
                                    class="w-full h-full object-cover news-image post-thumbnail lazy-loading"
                                    loading="lazy"
                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                    style="opacity: 0; transition: opacity 0.3s ease, filter 0.3s ease, transform 0.3s ease;"
                                >
                                <div class="w-full h-full bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center news-placeholder" style="display: none;">
                                    <i class="fas fa-newspaper text-3xl text-white/70"></i>
                                </div>
                                <div class="absolute top-2 right-2 bg-red-600 text-white text-xs px-2 py-1 rounded-full font-medium">
                                    Nổi bật
                                </div>
                            </div>
                        @else
                            <div class="h-48 bg-gradient-to-br from-red-500 to-red-700 flex flex-col items-center justify-center">
                                <i class="fas fa-newspaper text-3xl text-white/70 mb-2"></i>
                                <span class="text-white/60 text-sm font-medium">Tin tức</span>
                            </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <div class="flex flex-wrap items-center mb-2 text-xs gap-2">
                            @if(isset($featuredPost->created_at))
                            <span class="inline-flex items-center bg-gray-100 text-gray-600 py-1 px-2 rounded-full">
                                <i class="far fa-calendar-alt mr-1"></i>
                                {{ Carbon::parse($featuredPost->created_at)->translatedFormat('d/m/Y') }}
                            </span>
                            @endif
                            @if(isset($featuredPost->category) && !empty($featuredPost->category))
                                <span class="inline-flex items-center bg-red-50 text-red-600 py-1 px-2 rounded-full">
                                    <i class="fas fa-tag mr-1"></i>
                                    {{ $featuredPost->category->name }}
                                </span>
                            @endif
                        </div>
                        @if(isset($featuredPost->slug))
                        <a href="{{ route('posts.show', $featuredPost->slug) }}">
                            <h3 class="text-base font-bold text-gray-900 mb-2 line-clamp-2 leading-tight">{{ $featuredPost->title ?? 'Tin tức mới' }}</h3>
                        </a>
                        @else
                        <h3 class="text-base font-bold text-gray-900 mb-2 line-clamp-2 leading-tight">{{ $featuredPost->title ?? 'Tin tức mới' }}</h3>
                        @endif
                        @if(isset($featuredPost->content) && !empty($featuredPost->content))
                        <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                            {{ Str::limit(strip_tags($featuredPost->content), 100) }}
                        </p>
                        @endif
                        @if(isset($featuredPost->slug))
                        <a href="{{ route('posts.show', $featuredPost->slug) }}" class="inline-flex items-center text-red-600 font-medium text-sm">
                            <span>Đọc chi tiết</span>
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Remaining posts trong grid -->
                @if($remainingPosts->count() > 0)
                <div class="grid grid-cols-1 gap-4 mt-4">
                    @foreach($remainingPosts as $post)
                        <div class="bg-white rounded-lg overflow-hidden shadow-sm border border-gray-100">
                            @if(isset($post->slug))
                            <a href="{{ route('posts.show', $post->slug) }}" class="block">
                            @else
                            <div class="block">
                            @endif
                                <div class="h-32 overflow-hidden">
                                    @if(isset($post->thumbnail) && !empty($post->thumbnail))
                                        <img
                                            data-src="{{ asset('storage/' . $post->thumbnail) }}"
                                            alt="{{ $post->title ?? 'Tin tức Vũ Phúc Baking' }}"
                                            class="w-full h-full object-cover news-image post-thumbnail lazy-loading"
                                            loading="lazy"
                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                            style="opacity: 0; transition: opacity 0.3s ease, filter 0.3s ease, transform 0.3s ease;"
                                        >
                                        <div class="w-full h-full bg-red-50 flex items-center justify-center news-placeholder" style="display: none;">
                                            <i class="fas fa-newspaper text-3xl text-red-300"></i>
                                        </div>
                                    @else
                                        <div class="w-full h-full bg-red-50 flex items-center justify-center">
                                            <i class="fas fa-newspaper text-3xl text-red-300"></i>
                                        </div>
                                    @endif
                                </div>
                            @if(isset($post->slug))
                            </a>
                            @else
                            </div>
                            @endif
                            <div class="p-3">
                                <div class="flex flex-wrap items-center text-xs gap-1 mb-2">
                                    @if(isset($post->created_at))
                                    <span class="text-gray-500">{{ Carbon::parse($post->created_at)->translatedFormat('d/m') }}</span>
                                    @endif
                                    @if(isset($post->category) && !empty($post->category))
                                        <span class="bg-red-50 text-red-600 px-1.5 py-0.5 rounded-full">{{ $post->category->name }}</span>
                                    @endif
                                </div>
                                @if(isset($post->slug))
                                <a href="{{ route('posts.show', $post->slug) }}">
                                    <h3 class="text-sm font-medium text-gray-900 mb-2 line-clamp-2 leading-tight">{{ $post->title ?? 'Tin tức mới' }}</h3>
                                </a>
                                @else
                                <h3 class="text-sm font-medium text-gray-900 mb-2 line-clamp-2 leading-tight">{{ $post->title ?? 'Tin tức mới' }}</h3>
                                @endif
                                @if(isset($post->slug))
                                <a href="{{ route('posts.show', $post->slug) }}" class="inline-flex items-center text-xs text-red-600 font-medium">
                                    <span>Đọc tiếp</span>
                                    <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        <!-- CTA nút xem tất cả tin tức -->
        @if($postsCount > 0)
            <div class="text-center mt-8">
                @if(Route::has('posts.news'))
                    <a href="{{ route('posts.news') }}" class="inline-flex items-center px-6 py-2.5 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-all duration-300 shadow-sm hover:shadow-md">
                        <i class="fas fa-newspaper mr-2"></i>
                        <span>Xem tất cả tin tức</span>
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                @elseif(Route::has('posts.index'))
                    <a href="{{ route('posts.index', ['type' => 'news']) }}" class="inline-flex items-center px-6 py-2.5 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-all duration-300 shadow-sm hover:shadow-md">
                        <i class="fas fa-newspaper mr-2"></i>
                        <span>Xem tất cả tin tức</span>
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                @else
                    <div class="inline-flex items-center px-6 py-2.5 bg-gray-400 text-white font-medium rounded-lg cursor-not-allowed">
                        <i class="fas fa-newspaper mr-2"></i>
                        <span>Tính năng đang phát triển</span>
                    </div>
                @endif
            </div>

            <!-- Fallback message khi có ít tin tức -->
            @if($postsCount < 3)
                <div class="text-center mt-6">
                    <div class="inline-flex items-center px-4 py-2 bg-red-50 text-red-600 rounded-full text-sm">
                        <i class="fas fa-info-circle mr-2"></i>
                        <span>Chúng tôi đang cập nhật thêm nhiều tin tức mới</span>
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>
@else
<!-- Fallback UI khi không có tin tức -->
<div class="container mx-auto px-4 relative max-w-5xl">
    <!-- Tiêu đề vẫn hiển thị -->
    <div class="text-center mb-8 relative">
        <div class="inline-block relative">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">Tin tức & Sự kiện</h2>
            <div class="w-16 h-0.5 bg-red-600 mx-auto rounded-full"></div>
        </div>
        <p class="text-gray-600 text-sm md:text-base max-w-xl mx-auto mt-3">Cập nhật những tin tức nổi bật và sự kiện mới nhất</p>
    </div>

    <!-- Fallback Content -->
    <div class="max-w-2xl mx-auto text-center">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 md:p-12 fallback-card">
            <!-- Icon -->
            <div class="w-20 h-20 mx-auto mb-6 bg-red-50 rounded-full flex items-center justify-center">
                <i class="fas fa-newspaper text-3xl text-red-400"></i>
            </div>

            <!-- Heading -->
            <h3 class="text-xl md:text-2xl font-bold text-gray-900 mb-4">
                Tin tức đang được cập nhật
            </h3>

            <!-- Description -->
            <p class="text-gray-600 mb-6 leading-relaxed">
                Chúng tôi đang chuẩn bị những tin tức và sự kiện mới nhất để chia sẻ với bạn.
                Hãy quay lại sau để không bỏ lỡ những thông tin hữu ích nhé!
            </p>

            <!-- Action buttons -->
            <div class="flex flex-col sm:flex-row gap-3 justify-center items-center">
                @if(Route::has('courses.index'))
                    <a href="{{ route('courses.index') }}" class="inline-flex items-center px-6 py-2.5 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-all duration-300 shadow-sm hover:shadow-md">
                        <i class="fas fa-graduation-cap mr-2"></i>
                        <span>Xem khóa học</span>
                    </a>
                @endif
                @if(Route::has('posts.index'))
                    <a href="{{ route('posts.index') }}" class="inline-flex items-center px-6 py-2.5 bg-white text-red-600 font-medium rounded-lg border border-red-600 hover:bg-red-50 transition-all duration-300">
                        <i class="fas fa-search mr-2"></i>
                        <span>Tìm bài viết</span>
                    </a>
                @else
                    <button onclick="window.location.reload()" class="inline-flex items-center px-6 py-2.5 bg-white text-red-600 font-medium rounded-lg border border-red-600 hover:bg-red-50 transition-all duration-300">
                        <i class="fas fa-refresh mr-2"></i>
                        <span>Tải lại trang</span>
                    </button>
                @endif
            </div>
        </div>

        <!-- Decorative elements -->
        <div class="mt-8 flex justify-center space-x-2">
            <div class="w-2 h-2 bg-red-200 rounded-full animate-pulse"></div>
            <div class="w-2 h-2 bg-red-300 rounded-full animate-pulse" style="animation-delay: 0.2s;"></div>
            <div class="w-2 h-2 bg-red-400 rounded-full animate-pulse" style="animation-delay: 0.4s;"></div>
        </div>
    </div>
</div>
@endif

@push('styles')
<style>

    /* Smooth animations */
    .animate-on-scroll {
        animation: fadeInUp 0.6s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Fallback UI animations */
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }

    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }

    /* Fallback hover effects */
    .fallback-card {
        transition: all 0.3s ease;
    }

    .fallback-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    /* Fallback image placeholder */
    .news-placeholder {
        background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 50%, #fecaca 100%);
        position: relative;
        overflow: hidden;
    }

    .news-placeholder::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
        animation: shimmer 2s infinite;
    }

    @keyframes shimmer {
        0% {
            left: -100%;
        }
        100% {
            left: 100%;
        }
    }

    /* Responsive fallback adjustments */
    @media (max-width: 768px) {
        .fallback-card {
            padding: 1.5rem;
        }

        .fallback-card h3 {
            font-size: 1.25rem;
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

    /* Hover effects */
    @keyframes pulseRed {
        0% {
            box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.3);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(220, 38, 38, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(220, 38, 38, 0);
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Enhanced image error handling - Global function
        window.handleImageError = function(img) {
            console.log('Image error detected:', img.src);

            // Hide the broken image
            img.style.display = 'none';

            // Show the fallback placeholder
            const fallback = img.nextElementSibling;
            if (fallback && fallback.classList.contains('news-placeholder')) {
                fallback.style.display = 'flex';

                // Add a subtle fade-in animation
                fallback.style.opacity = '0';
                setTimeout(() => {
                    fallback.style.transition = 'opacity 0.3s ease';
                    fallback.style.opacity = '1';
                }, 50);

                console.log('Fallback UI activated for:', img.alt || 'Unnamed image');
            }
        };

        // Apply error handling to all news images
        document.querySelectorAll('img[src*="storage"], .news-image').forEach(img => {
            img.addEventListener('error', function() {
                window.handleImageError(this);
            });

            // Also check if image is already broken
            if (img.complete && img.naturalHeight === 0) {
                window.handleImageError(img);
            }
        });
    });
</script>
@endpush