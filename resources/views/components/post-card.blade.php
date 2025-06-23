@php
    // Get short description
    $shortDescription = \Illuminate\Support\Str::limit(strip_tags($post->content), 120);
@endphp

<!-- Post Card Component -->
<article class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-red-200 group"
         role="article"
         aria-labelledby="post-{{ $post->id }}-title">

    <!-- Post Image -->
    <div class="relative overflow-hidden aspect-[16/10] bg-gradient-to-br from-red-50 to-red-100">
        @if($post->thumbnail && \Illuminate\Support\Facades\Storage::disk('public')->exists($post->thumbnail))
            <img src="{{ asset('storage/' . $post->thumbnail) }}"
                 alt="{{ $post->seo_title ?? $post->title }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                 loading="lazy">
        @else
            <!-- Fallback UI -->
            <div class="absolute inset-0 flex flex-col items-center justify-center text-red-600">
                <div class="text-center space-y-3">
                    <i class="fas fa-newspaper text-4xl opacity-60"></i>
                    <div class="space-y-1">
                        <span class="block text-sm font-semibold opacity-80 line-clamp-1">{{ Str::limit($post->title, 20) }}</span>
                        <span class="block text-xs opacity-60">Bài viết</span>
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
        @if($post->categories && $post->categories->count() > 0)
        <div class="mb-3">
            @foreach($post->categories->take(2) as $category)
                <span class="inline-block px-2 py-1 bg-red-100 text-red-800 text-xs font-medium rounded mr-2">
                    {{ $category->name }}
                </span>
            @endforeach
        </div>
        @endif

        <!-- Post Title -->
        <h3 id="post-{{ $post->id }}-title"
            class="font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-red-600 transition-colors duration-300 text-xl">
            <a href="{{ route('posts.show', $post->slug) }}" class="hover:underline">
                {{ $post->title }}
            </a>
        </h3>

        <!-- Post Description -->
        @if($shortDescription)
        <p class="text-gray-600 text-sm mb-4 line-clamp-3">
            {{ $shortDescription }}
        </p>
        @endif

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

            <!-- View Details Button -->
            <a href="{{ route('posts.show', $post->slug) }}"
               class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors duration-300 flex items-center">
                <span>Đọc thêm</span>
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</article>
