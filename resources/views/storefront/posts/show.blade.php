@extends('layouts.shop')

@section('title', $post->seo_title ?? $post->title)
@section('description', $post->seo_description ?? Str::limit(strip_tags($post->content), 160))

@if($post->og_image_link)
    @section('og_image', asset('storage/' . $post->og_image_link))
@endif

@push('styles')
<style>
    .prose {
        font-family: 'Open Sans', sans-serif;
        line-height: 1.8;
        color: #374151;
        max-width: none;
    }

    .prose h1, .prose h2, .prose h3, .prose h4, .prose h5, .prose h6 {
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        color: #1f2937;
        margin-top: 2.5rem;
        margin-bottom: 1rem;
        line-height: 1.3;
    }

    .prose h1 { font-size: 2rem; }
    .prose h2 { font-size: 1.75rem; }
    .prose h3 { font-size: 1.5rem; }
    .prose h4 { font-size: 1.25rem; }

    .prose p {
        margin-bottom: 1.5rem;
        text-align: justify;
    }

    .prose ul, .prose ol {
        margin: 1.5rem 0;
        padding-left: 1.5rem;
    }

    .prose li {
        margin: 0.75rem 0;
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
        border-radius: 0.75rem;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        margin: 2rem auto;
    }

    .prose blockquote {
        border-left: 4px solid #dc2626;
        background: #fef2f2;
        padding: 1.5rem;
        margin: 2rem 0;
        border-radius: 0.5rem;
        font-style: italic;
    }
</style>
@endpush

@section('content')
<!-- Minimalist Header -->
<header class="bg-white border-b border-gray-100">
    <div class="container mx-auto px-4 py-6">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <div class="flex items-center space-x-2 text-sm text-gray-500 font-open-sans">
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
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4 font-montserrat leading-tight">
                {{ $post->title }}
            </h1>

            <!-- Meta Info -->
            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 font-open-sans">
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
    <div class="container mx-auto px-4 py-12">
        <div class="max-w-4xl mx-auto">
            <!-- Featured Image - Responsive và thông minh -->
            @if($post->thumbnail)
                <div class="mb-12">
                    <div class="relative overflow-hidden rounded-lg shadow-lg">
                        <img src="{{ asset('storage/' . $post->thumbnail) }}"
                             alt="{{ $post->title }}"
                             class="w-full h-auto object-cover"
                             style="max-height: 500px;"
                             loading="eager">
                    </div>
                </div>
            @endif

            <!-- Article Content -->
            <article class="prose prose-lg max-w-none">
                {!! $post->content !!}
            </article>
        </div>
    </div>
</main>

<!-- Related Posts -->
@if($relatedPosts->count() > 0)
<section class="bg-gray-50 py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <!-- Section Header -->
            <div class="text-center mb-12">
                <h3 class="text-2xl font-bold text-gray-900 mb-2 font-montserrat">
                    @php
                        $relatedTitle = [
                            'service' => 'Dịch vụ liên quan',
                            'news' => 'Tin tức liên quan',
                            'course' => 'Khóa học liên quan',
                            'normal' => 'Bài viết liên quan'
                        ];
                    @endphp
                    {{ $relatedTitle[$post->type] ?? 'Bài viết liên quan' }}
                </h3>
                <div class="w-16 h-0.5 bg-red-600 mx-auto"></div>
            </div>

            <!-- Related Posts Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                @foreach($relatedPosts->take(4) as $relatedPost)
                    <article class="group">
                        <a href="{{ route('posts.show', $relatedPost->slug) }}" class="block bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300">
                            @if($relatedPost->thumbnail)
                                <div class="aspect-video overflow-hidden">
                                    <img src="{{ asset('storage/' . $relatedPost->thumbnail) }}"
                                         alt="{{ $relatedPost->title }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                </div>
                            @else
                                <div class="aspect-video bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                    <i class="fas fa-file-alt text-3xl text-gray-400"></i>
                                </div>
                            @endif

                            <div class="p-6">
                                <h4 class="font-semibold text-gray-900 group-hover:text-red-600 transition-colors line-clamp-2 mb-2 font-montserrat">
                                    {{ $relatedPost->title }}
                                </h4>
                                <p class="text-sm text-gray-600 line-clamp-2 font-open-sans mb-3">
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
                <a href="{{ route('posts.index', ['type' => $post->type]) }}"
                   class="inline-flex items-center px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors duration-300 font-open-sans">
                    <span>Xem tất cả</span>
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </div>
</section>
@endif
@endsection
