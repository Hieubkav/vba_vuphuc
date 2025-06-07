@extends('layouts.shop')

@section('title', $post->seo_title ?? $post->title)
@section('description', $post->seo_description ?? Str::limit(strip_tags($post->content), 160))

@if($post->og_image_link)
    @section('og_image', asset('storage/' . $post->og_image_link))
@endif

@section('content')
<!-- Simple Header -->
<header class="bg-white border-b border-gray-100">
    <div class="container mx-auto px-4 py-6">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <div class="flex items-center space-x-2 text-sm text-gray-500">
                <a href="{{ route('storeFront') }}" class="hover:text-red-600 transition-colors">
                    <i class="fas fa-home mr-1"></i>Trang chủ
                </a>
                <i class="fas fa-chevron-right text-xs"></i>
                <a href="{{ route('posts.index') }}" class="hover:text-red-600 transition-colors">Bài viết</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-gray-900">{{ Str::limit($post->title, 40) }}</span>
            </div>
        </nav>

        <!-- Article Header -->
        <div class="max-w-4xl">
            <!-- Type Badge -->
            @php
                $typeConfig = [
                    'service' => ['label' => 'Dịch vụ', 'icon' => 'fas fa-cogs', 'color' => 'bg-blue-100 text-blue-800'],
                    'news' => ['label' => 'Tin tức', 'icon' => 'fas fa-newspaper', 'color' => 'bg-green-100 text-green-800'],
                    'course' => ['label' => 'Khóa học', 'icon' => 'fas fa-graduation-cap', 'color' => 'bg-purple-100 text-purple-800'],
                    'normal' => ['label' => 'Bài viết', 'icon' => 'fas fa-file-alt', 'color' => 'bg-gray-100 text-gray-800']
                ];
                $config = $typeConfig[$post->type] ?? $typeConfig['normal'];
            @endphp

            <div class="flex items-center gap-3 mb-4">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $config['color'] }}">
                    <i class="{{ $config['icon'] }} mr-1.5"></i>
                    {{ $config['label'] }}
                </span>

                @if($post->is_featured)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        <i class="fas fa-star mr-1.5"></i>
                        Nổi bật
                    </span>
                @endif
            </div>

            <!-- Title -->
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4 leading-tight">
                {{ $post->title }}
            </h1>

            <!-- Meta Info -->
            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500">
                <div class="flex items-center">
                    <i class="far fa-calendar mr-2"></i>
                    <time datetime="{{ $post->created_at->format('Y-m-d') }}">
                        {{ $post->created_at->format('d/m/Y') }}
                    </time>
                </div>

                @if($post->category)
                    <div class="flex items-center">
                        <i class="fas fa-folder mr-2"></i>
                        <span>{{ $post->category->name }}</span>
                    </div>
                @endif

                <div class="flex items-center">
                    <i class="far fa-clock mr-2"></i>
                    <span>{{ ceil(str_word_count(strip_tags($post->content)) / 200) }} phút đọc</span>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Main Content -->
<main class="bg-white">
    <div class="container mx-auto px-4 py-6 md:py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Featured Image - Không cắt xén, giữ nguyên tỷ lệ -->
            <div class="mb-6 md:mb-8">
                @if(isset($post->thumbnail) && !empty($post->thumbnail) && \Illuminate\Support\Facades\Storage::disk('public')->exists($post->thumbnail))
                    <!-- Ảnh thực tế - giữ nguyên tỷ lệ, không cắt xén -->
                    <div class="post-image-container">
                        <img src="{{ asset('storage/' . $post->thumbnail) }}"
                             alt="{{ $post->title }}"
                             loading="eager"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                        <!-- Fallback UI khi ảnh lỗi -->
                        <div class="absolute inset-0 bg-gradient-to-br from-red-50 to-red-100 flex items-center justify-center" style="display: none;">
                            <i class="fas fa-image text-4xl md:text-6xl text-red-300"></i>
                        </div>
                    </div>
                @else
                    <!-- Fallback UI khi không có ảnh -->
                    <div class="w-full h-48 md:h-64 bg-gradient-to-br from-red-50 to-red-100 flex items-center justify-center rounded-lg shadow-lg">
                        <i class="fas fa-newspaper text-4xl md:text-6xl text-red-300"></i>
                    </div>
                @endif
            </div>

            <!-- Article Content -->
            <article class="prose prose-lg max-w-none prose-red">
                {!! $post->content !!}
            </article>
        </div>
    </div>
</main>

<!-- Related Posts -->
@if($relatedPosts->count() > 0)
<section class="bg-gray-50 py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <!-- Section Header -->
            <div class="text-center mb-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-2">
                    Bài viết liên quan
                </h3>
                <div class="w-16 h-0.5 bg-red-600 mx-auto"></div>
            </div>

            <!-- Related Posts Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                @foreach($relatedPosts->take(4) as $relatedPost)
                    <article class="group">
                        <a href="{{ route('posts.show', $relatedPost->slug) }}" class="block bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300">
                            <!-- Ảnh với fallback UI -->
                            <div class="aspect-video overflow-hidden">
                                @if(isset($relatedPost->thumbnail) && !empty($relatedPost->thumbnail) && \Illuminate\Support\Facades\Storage::disk('public')->exists($relatedPost->thumbnail))
                                    <img src="{{ asset('storage/' . $relatedPost->thumbnail) }}"
                                         alt="{{ $relatedPost->title }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                         loading="lazy"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                                    <!-- Fallback khi ảnh lỗi -->
                                    <div class="w-full h-full bg-gradient-to-br from-red-50 to-red-100 flex items-center justify-center" style="display: none;">
                                        <i class="fas fa-newspaper text-3xl text-red-300"></i>
                                    </div>
                                @else
                                    <!-- Fallback khi không có ảnh -->
                                    <div class="w-full h-full bg-gradient-to-br from-red-50 to-red-100 flex items-center justify-center">
                                        <i class="fas fa-newspaper text-3xl text-red-300"></i>
                                    </div>
                                @endif
                            </div>

                            <div class="p-6">
                                <h4 class="font-semibold text-gray-900 group-hover:text-red-600 transition-colors line-clamp-2 mb-2">
                                    {{ $relatedPost->title }}
                                </h4>
                                <p class="text-sm text-gray-600 line-clamp-2 mb-3">
                                    {{ Str::limit(strip_tags($relatedPost->content), 100) }}
                                </p>
                                <div class="flex items-center text-xs text-gray-500">
                                    <i class="far fa-calendar mr-1"></i>
                                    <time datetime="{{ $relatedPost->created_at->format('Y-m-d') }}">
                                        {{ $relatedPost->created_at->format('d/m/Y') }}
                                    </time>
                                </div>
                            </div>
                        </a>
                    </article>
                @endforeach
            </div>

            <!-- View All Button -->
            <div class="text-center">
                <a href="{{ route('posts.index') }}"
                   class="inline-flex items-center px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors duration-300">
                    <span>Xem tất cả bài viết</span>
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </div>
</section>
@endif

@push('styles')
<style>
/* Simple Prose Styles - KISS Principle */
.prose {
    line-height: 1.8;
    color: #374151;
}

.prose h1, .prose h2, .prose h3, .prose h4, .prose h5, .prose h6 {
    font-weight: 600;
    color: #1f2937;
    margin-top: 2rem;
    margin-bottom: 1rem;
    line-height: 1.3;
}

/* Responsive font sizes */
.prose h1 { font-size: 1.75rem; }
.prose h2 { font-size: 1.5rem; }
.prose h3 { font-size: 1.25rem; }
.prose h4 { font-size: 1.125rem; }

@media (min-width: 768px) {
    .prose h1 { font-size: 2rem; }
    .prose h2 { font-size: 1.75rem; }
    .prose h3 { font-size: 1.5rem; }
    .prose h4 { font-size: 1.25rem; }
}

.prose p {
    margin-bottom: 1.5rem;
    text-align: justify;
}

.prose ul, .prose ol {
    margin: 1.5rem 0;
    padding-left: 1.5rem;
}

.prose li {
    margin: 0.5rem 0;
}

.prose strong {
    font-weight: 600;
    color: #1f2937;
}

.prose a {
    color: #dc2626;
    text-decoration: none;
    font-weight: 500;
    border-bottom: 1px solid transparent;
    transition: border-color 0.2s ease;
}

.prose a:hover {
    border-bottom-color: #dc2626;
}

.prose img {
    border-radius: 0.5rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    margin: 1.5rem auto;
    max-width: 100%;
    height: auto;
    object-fit: contain;
}

.prose blockquote {
    border-left: 4px solid #dc2626;
    background: #fef2f2;
    padding: 1rem;
    margin: 1.5rem 0;
    border-radius: 0.5rem;
    font-style: italic;
}

@media (min-width: 768px) {
    .prose blockquote {
        padding: 1.5rem;
        margin: 2rem 0;
    }
}

/* Line clamp utility */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Responsive image container */
.post-image-container {
    position: relative;
    width: 100%;
    background: white;
    border-radius: 0.5rem;
    overflow: hidden;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

.post-image-container img {
    width: 100%;
    height: auto;
    object-fit: contain;
    max-height: 50vh;
}

@media (min-width: 768px) {
    .post-image-container img {
        max-height: 60vh;
    }
}

@media (min-width: 1024px) {
    .post-image-container img {
        max-height: 70vh;
    }
}
</style>
@endpush
@endsection
