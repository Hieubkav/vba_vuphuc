@extends('layouts.shop')

@section('title', $category->seo_title ?: $category->name . ' - VBA Vũ Phúc')
@section('description', $category->seo_description ?: 'Khám phá các khóa học ' . $category->name . ' chất lượng cao tại VBA Vũ Phúc')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Category Header -->
    <div class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Breadcrumb -->
            <nav class="text-sm mb-6">
                <ol class="flex items-center space-x-2 text-gray-500">
                    <li><a href="{{ route('storeFront') }}" class="hover:text-red-600">Trang chủ</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li><a href="{{ route('courses.index') }}" class="hover:text-red-600">Khóa học</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li class="text-gray-900">{{ $category->name }}</li>
                </ol>
            </nav>

            <!-- Category Info -->
            <div class="flex items-center space-x-6">
                @if($category->image)
                    <div class="flex-shrink-0">
                        <img 
                            src="{{ asset('storage/' . $category->image) }}" 
                            alt="{{ $category->name }}"
                            class="w-20 h-20 rounded-2xl object-cover"
                        >
                    </div>
                @else
                    <div class="flex-shrink-0 w-20 h-20 rounded-2xl flex items-center justify-center" 
                         style="background: linear-gradient(135deg, {{ $category->display_color }}CC, {{ $category->display_color }}FF);">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                @endif
                
                <div class="flex-1">
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">
                        Khóa học {{ $category->name }}
                    </h1>
                    @if($category->description)
                        <p class="text-lg text-gray-600 mb-4">
                            {{ $category->description }}
                        </p>
                    @endif
                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $courses->total() }} khóa học
                        </span>
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                            </svg>
                            Chuyên gia giảng dạy
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Courses Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        @if($courses->count() > 0)
            <!-- Courses Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($courses as $course)
                    <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden group">
                        <!-- Course Image -->
                        <div class="relative aspect-video overflow-hidden">
                            @if($course->thumbnail)
                                <img 
                                    src="{{ asset('storage/' . $course->thumbnail) }}" 
                                    alt="{{ $course->title }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                    loading="lazy"
                                >
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                            @endif
                            
                            <!-- Price Badge -->
                            @if($course->price > 0)
                                <div class="absolute top-3 right-3 bg-red-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                    {{ number_format($course->price, 0, ',', '.') }}đ
                                </div>
                            @else
                                <div class="absolute top-3 right-3 bg-green-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                    Miễn phí
                                </div>
                            @endif

                            <!-- Featured Badge -->
                            @if($course->is_featured)
                                <div class="absolute top-3 left-3 bg-yellow-500 text-white px-2 py-1 rounded text-xs font-semibold">
                                    Nổi bật
                                </div>
                            @endif
                        </div>

                        <!-- Course Content -->
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-red-600 transition-colors">
                                <a href="{{ route('courses.show', $course->slug) }}">
                                    {{ $course->title }}
                                </a>
                            </h3>
                            
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                {!! Str::limit(strip_tags($course->description), 120) !!}
                            </p>

                            <!-- Course Meta -->
                            <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                <div class="flex items-center space-x-4">
                                    @if($course->duration_hours)
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $course->duration_hours }}h
                                        </span>
                                    @endif
                                    
                                    <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs font-medium">
                                        {{ ucfirst($course->level) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Instructor -->
                            @if($course->instructor_name)
                                <div class="flex items-center text-sm text-gray-600 mb-4">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $course->instructor_name }}
                                </div>
                            @endif

                            <!-- Action Button -->
                            <a 
                                href="{{ route('courses.show', $course->slug) }}" 
                                class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg text-center transition-colors duration-200 block"
                            >
                                Xem chi tiết
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($courses->hasPages())
                <div class="mt-12 flex justify-center">
                    {{ $courses->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Chưa có khóa học nào</h3>
                <p class="text-gray-600 mb-6">Danh mục này hiện chưa có khóa học nào. Vui lòng quay lại sau.</p>
                <a 
                    href="{{ route('courses.index') }}" 
                    class="inline-flex items-center bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-6 rounded-lg transition-colors duration-200"
                >
                    Xem tất cả khóa học
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
