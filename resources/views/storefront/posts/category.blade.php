@extends('layouts.shop')

@section('title', $category->seo_title ?: $category->name . ' - Vũ Phúc Baking')
@section('description', $category->seo_description ?: 'Khám phá các bài viết trong chuyên mục ' . $category->name . ' tại Vũ Phúc Baking')

@if($category->og_image_link)
@section('og_image', asset('storage/' . $category->og_image_link))
@endif

@push('styles')
<style>
    .filter-card {
        background: white;
        border: 1px solid #f1f5f9;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        transition: all 0.2s ease;
    }

    .filter-card:hover {
        border-color: rgba(220, 38, 38, 0.2);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .post-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .post-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    .filter-btn {
        transition: all 0.2s ease;
    }

    .filter-btn.active {
        background: #fef2f2;
        color: #dc2626;
        font-weight: 500;
    }

    .filter-btn:not(.active):hover {
        background: #f9fafb;
    }
</style>
@endpush

@section('content')
{{-- Hero Section đã được loại bỏ để đơn giản hóa giao diện
<section class="bg-gradient-to-r from-red-600 to-red-700">
    <div class="container mx-auto px-4 py-4">
        <nav class="mb-1">
            <div class="flex items-center space-x-2 text-white/80 text-xs font-open-sans">
                <a href="{{ route('storeFront') }}" class="hover:text-white transition-colors">Trang chủ</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <a href="{{ route('posts.categories') }}" class="hover:text-white transition-colors">Chuyên mục</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-white">{{ $category->name }}</span>
            </div>
        </nav>
        <h1 class="text-xl md:text-2xl font-bold text-white font-montserrat">
            {{ $category->name }}
            @if($category->description)
                <span class="block text-sm font-normal text-white/90 mt-1">{{ $category->description }}</span>
            @endif
        </h1>
    </div>
</section>
--}}

<!-- Main Content -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar Filters -->
            <aside class="lg:w-80 flex-shrink-0">
                <div class="space-y-6">
                    <!-- Search -->
                    <div class="filter-card rounded-xl p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center font-montserrat">
                            <i class="fas fa-search mr-2 text-red-500"></i>
                            Tìm kiếm
                        </h3>
                        <form method="GET" action="{{ request()->url() }}">
                            <div class="relative">
                                <input type="text"
                                       name="search"
                                       value="{{ request('search') }}"
                                       class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors font-open-sans"
                                       placeholder="Tìm kiếm trong {{ $category->name }}...">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                @if(request('search'))
                                <button type="button" onclick="this.form.search.value=''; this.form.submit();"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-red-600">
                                    <i class="fas fa-times"></i>
                                </button>
                                @endif
                            </div>
                            @if(request('sort'))
                                <input type="hidden" name="sort" value="{{ request('sort') }}">
                            @endif
                            @if(request('category_filter'))
                                <input type="hidden" name="category_filter" value="{{ request('category_filter') }}">
                            @endif
                        </form>
                    </div>

                    <!-- Categories -->
                    @php
                        $allCategories = \App\Models\CatPost::where('status', 'active')
                            ->withCount(['postsMany' => function($query) {
                                $query->where('status', 'active');
                            }])
                            ->having('posts_many_count', '>', 0)
                            ->orderBy('order')
                            ->get();
                    @endphp

                    @if($allCategories->count() > 1)
                        <div class="filter-card rounded-xl p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center font-montserrat">
                                <i class="fas fa-tags mr-2 text-red-500"></i>
                                Chuyên mục
                            </h3>
                            <div class="space-y-2">
                                <a href="{{ route('posts.category', $category->slug) }}"
                                   class="w-full text-left px-3 py-2 rounded-lg transition-colors font-open-sans flex items-center justify-between {{ !request('category_filter') ? 'bg-red-100 text-red-700 font-medium' : 'hover:bg-gray-50' }}">
                                    <span>{{ $category->name }}</span>
                                    <span class="text-xs bg-gray-200 text-gray-600 px-2 py-1 rounded-full">{{ $posts->total() }}</span>
                                </a>
                                @foreach($allCategories as $filterCategory)
                                    @if($filterCategory->id !== $category->id)
                                        <a href="{{ request()->fullUrlWithQuery(['category_filter' => $filterCategory->slug]) }}"
                                           class="w-full text-left px-3 py-2 rounded-lg transition-colors font-open-sans flex items-center justify-between {{ request('category_filter') === $filterCategory->slug ? 'bg-red-100 text-red-700 font-medium' : 'hover:bg-gray-50' }}">
                                            <span>{{ $filterCategory->name }}</span>
                                            <span class="text-xs bg-gray-200 text-gray-600 px-2 py-1 rounded-full">{{ $filterCategory->posts_many_count }}</span>
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Sort -->
                    <div class="filter-card rounded-xl p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center font-montserrat">
                            <i class="fas fa-sort mr-2 text-red-500"></i>
                            Sắp xếp
                        </h3>
                        <div class="space-y-2">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}"
                               class="w-full text-left px-3 py-2 rounded-lg transition-colors font-open-sans {{ request('sort', 'newest') === 'newest' ? 'bg-red-100 text-red-700 font-medium' : 'hover:bg-gray-50' }}">
                                Mới nhất
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'oldest']) }}"
                               class="w-full text-left px-3 py-2 rounded-lg transition-colors font-open-sans {{ request('sort') === 'oldest' ? 'bg-red-100 text-red-700 font-medium' : 'hover:bg-gray-50' }}">
                                Cũ nhất
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'featured']) }}"
                               class="w-full text-left px-3 py-2 rounded-lg transition-colors font-open-sans {{ request('sort') === 'featured' ? 'bg-red-100 text-red-700 font-medium' : 'hover:bg-gray-50' }}">
                                Nổi bật
                            </a>
                        </div>
                    </div>

                    <!-- Clear Filters -->
                    @if(request('search') || request('category_filter') || request('sort') !== 'newest')
                    <div class="filter-card rounded-xl p-6">
                        <a href="{{ route('posts.category', $category->slug) }}"
                           class="w-full px-4 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium font-open-sans text-center block">
                            <i class="fas fa-eraser mr-2"></i>
                            Xóa bộ lọc
                        </a>
                    </div>
                    @endif
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 min-w-0 space-y-6">
                <!-- Results Summary -->
                <div class="bg-white rounded-xl shadow-md border border-red-100 p-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 flex items-center font-montserrat">
                                <i class="fas fa-newspaper mr-3 text-red-500"></i>
                                {{ $posts->total() }} bài viết
                            </h2>
                            @if(request('search') || request('category_filter'))
                            <p class="text-red-600 mt-1 font-medium font-open-sans">
                                <i class="fas fa-filter mr-1"></i>
                                Kết quả tìm kiếm với bộ lọc đã chọn
                            </p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Posts Grid -->
                @if($posts->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($posts as $post)
                            <article class="post-card bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-red-200 group">
                                <a href="{{ route('posts.show', $post->slug) }}" class="block">
                                    <!-- Post Image -->
                                    <div class="relative overflow-hidden aspect-[16/10] bg-gradient-to-br from-red-50 to-red-100">
                                        @if($post->thumbnail)
                                            <img src="{{ asset('storage/' . $post->thumbnail) }}"
                                                 alt="{{ $post->title }}"
                                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                                 loading="lazy">
                                        @else
                                            <!-- Fallback UI -->
                                            <div class="absolute inset-0 flex flex-col items-center justify-center text-red-600">
                                                <div class="text-center space-y-3">
                                                    <i class="fas fa-newspaper text-4xl opacity-60"></i>
                                                    <div class="space-y-1">
                                                        <span class="block text-sm font-semibold opacity-80 line-clamp-1">{{ Str::limit($post->title, 20) }}</span>
                                                        <span class="block text-xs opacity-60">{{ $category->name }}</span>
                                                    </div>
                                                </div>
                                                <!-- Decorative elements -->
                                                <div class="absolute top-4 right-4 w-8 h-8 bg-red-200 rounded-full opacity-30"></div>
                                                <div class="absolute bottom-4 left-4 w-6 h-6 bg-red-300 rounded-full opacity-20"></div>
                                                <div class="absolute top-1/2 left-4 w-4 h-4 bg-red-400 rounded-full opacity-15"></div>
                                            </div>
                                        @endif

                                        <!-- Featured Badge -->
                                        @if($post->is_featured)
                                        <div class="absolute top-4 left-4 z-10">
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                NỔI BẬT
                                            </span>
                                        </div>
                                        @endif
                                    </div>

                                    <!-- Post Content -->
                                    <div class="p-6">
                                        <!-- Post Categories -->
                                        @if($post->categories->count() > 1)
                                        <div class="mb-3">
                                            @foreach($post->categories->take(2) as $postCategory)
                                                @if($postCategory->id !== $category->id)
                                                    <span class="inline-block px-2 py-1 bg-red-100 text-red-800 text-xs font-medium rounded mr-2">
                                                        {{ $postCategory->name }}
                                                    </span>
                                                @endif
                                            @endforeach
                                        </div>
                                        @endif

                                        <!-- Post Title -->
                                        <h3 class="font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-red-600 transition-colors duration-300 text-xl">
                                            {{ $post->title }}
                                        </h3>

                                        <!-- Post Description -->
                                        <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                            {{ Str::limit(strip_tags($post->content), 120) }}
                                        </p>

                                        <!-- Post Meta Info -->
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-4 text-xs text-gray-500">
                                                <div class="flex items-center">
                                                    <i class="fas fa-calendar mr-1"></i>
                                                    <time datetime="{{ $post->created_at->format('Y-m-d') }}">
                                                        {{ $post->created_at->format('d/m/Y') }}
                                                    </time>
                                                </div>
                                            </div>

                                            <!-- Read More Button -->
                                            <span class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors duration-300 flex items-center">
                                                <span>Đọc thêm</span>
                                                <i class="fas fa-arrow-right ml-2"></i>
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            </article>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="flex justify-center mt-12">
                        {{ $posts->appends(request()->query())->links() }}
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-16">
                        <div class="max-w-md mx-auto bg-white rounded-xl shadow-lg border border-red-100 p-8">
                            <div class="w-24 h-24 mx-auto mb-6 bg-red-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-search text-3xl text-red-400"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2 font-montserrat">
                                Không tìm thấy bài viết nào
                            </h3>
                            <p class="text-gray-600 mb-6 font-open-sans">
                                @if(request('search'))
                                    Thử thay đổi bộ lọc hoặc từ khóa tìm kiếm để xem thêm kết quả
                                @else
                                    Hiện tại chưa có bài viết nào trong chuyên mục này
                                @endif
                            </p>
                            @if(request('search') || request('category_filter') || request('sort') !== 'newest')
                                <a href="{{ route('posts.category', $category->slug) }}"
                                   class="inline-flex items-center px-6 py-3 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors shadow-md font-open-sans">
                                    <i class="fas fa-refresh mr-2"></i>
                                    Xóa bộ lọc
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </main>
        </div>
    </div>
</section>
@endsection
