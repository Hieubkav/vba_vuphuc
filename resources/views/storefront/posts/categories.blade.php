@extends('layouts.shop')

@section('title', 'Danh mục bài viết - VBA Vũ Phúc')
@section('description', 'Khám phá các danh mục bài viết về Excel, VBA và các kỹ năng văn phòng tại VBA Vũ Phúc')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-8">
            <div class="text-center">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">
                    Danh mục bài viết
                </h1>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Khám phá các chủ đề về Excel, VBA và kỹ năng văn phòng được phân loại rõ ràng
                </p>
            </div>
        </div>
    </div>

    <!-- Categories Grid -->
    <div class="container mx-auto px-4 py-12">
        @if($categories && $categories->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($categories as $category)
            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden group">
                <!-- Category Header -->
                <div class="bg-gradient-to-r from-red-500 to-red-600 p-6 text-white">
                    <h3 class="text-xl font-bold mb-2">{{ $category->name }}</h3>
                    @if($category->description)
                    <p class="text-red-100 text-sm">{{ $category->description }}</p>
                    @endif
                </div>

                <!-- Category Stats -->
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-newspaper mr-2"></i>
                            <span class="text-sm">{{ $category->posts_count }} bài viết</span>
                        </div>
                        <div class="text-red-600">
                            <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <a href="{{ route('posts.category', $category->slug) }}" 
                       class="block w-full bg-red-600 text-white text-center py-3 rounded-lg font-medium hover:bg-red-700 transition-colors">
                        Xem bài viết
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <!-- Empty State -->
        <div class="text-center py-16">
            <div class="w-24 h-24 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-folder-open text-3xl text-red-300"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Chưa có danh mục nào</h3>
            <p class="text-gray-600 mb-6">Hiện tại chưa có danh mục bài viết nào được tạo.</p>
            <a href="{{ route('posts.index') }}" 
               class="inline-flex items-center bg-red-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-red-700 transition-colors">
                <i class="fas fa-newspaper mr-2"></i>
                Xem tất cả bài viết
            </a>
        </div>
        @endif
    </div>

    <!-- Call to Action -->
    <div class="bg-red-600 text-white py-16">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-2xl font-bold mb-4">Không tìm thấy nội dung phù hợp?</h2>
            <p class="text-red-100 mb-6 max-w-2xl mx-auto">
                Hãy liên hệ với chúng tôi để đề xuất chủ đề bài viết mới hoặc tham gia các khóa học VBA Excel
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('courses.index') }}" 
                   class="inline-flex items-center bg-white text-red-600 px-6 py-3 rounded-lg font-medium hover:bg-gray-100 transition-colors">
                    <i class="fas fa-graduation-cap mr-2"></i>
                    Xem khóa học
                </a>
                <a href="{{ route('posts.index') }}" 
                   class="inline-flex items-center border-2 border-white text-white px-6 py-3 rounded-lg font-medium hover:bg-white hover:text-red-600 transition-colors">
                    <i class="fas fa-newspaper mr-2"></i>
                    Tất cả bài viết
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
