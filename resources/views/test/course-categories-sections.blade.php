@extends('layouts.shop')

@section('title', 'Test Course Categories Sections Component')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Test Header -->
    <div class="bg-white shadow-sm py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900">Test Course Categories Sections Component</h1>
            <p class="text-gray-600 mt-2">Kiểm tra giao diện khóa học theo từng danh mục với dữ liệu thực</p>
        </div>
    </div>

    <!-- Course Categories Sections Component -->
    <div class="animate-on-scroll">
        @include('components.storefront.course-categories-sections')
    </div>

    <!-- Debug Info -->
    <div class="bg-gray-100 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Debug Information</h2>
            
            @if(isset($courseCategoriesGrid))
                <div class="bg-white p-4 rounded-lg mb-4">
                    <h3 class="font-semibold mb-2">Course Categories with Courses:</h3>
                    <div class="space-y-4">
                        @foreach($courseCategoriesGrid as $cat)
                            @php
                                $activeCourses = $cat->courses()->where('status', 'active')->get();
                            @endphp
                            <div class="border rounded p-3">
                                <div class="flex items-center space-x-3 mb-2">
                                    <span class="inline-block w-4 h-4 rounded" style="background-color: {{ $cat->color }}"></span>
                                    <strong>{{ $cat->name }}</strong>
                                    <span class="text-sm text-gray-500">({{ $activeCourses->count() }} khóa học)</span>
                                </div>
                                
                                @if($activeCourses->isNotEmpty())
                                    <div class="text-sm text-gray-600">
                                        <strong>Khóa học:</strong>
                                        <ul class="list-disc list-inside ml-4">
                                            @foreach($activeCourses->take(3) as $course)
                                                <li>{{ $course->title }} - {{ $course->level }} - {{ number_format($course->price) }}đ</li>
                                            @endforeach
                                            @if($activeCourses->count() > 3)
                                                <li class="text-gray-400">... và {{ $activeCourses->count() - 3 }} khóa học khác</li>
                                            @endif
                                        </ul>
                                    </div>
                                @else
                                    <div class="text-sm text-red-500">Không có khóa học nào</div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <strong>Warning:</strong> $courseCategoriesGrid variable is not available.
                </div>
            @endif

            <div class="mt-4">
                <h3 class="font-semibold mb-2">Available Variables:</h3>
                <ul class="list-disc list-inside text-sm text-gray-600">
                    @foreach(get_defined_vars() as $key => $value)
                        @if(!in_array($key, ['__data', '__path', '__env', 'app', 'errors', 'obLevel']))
                            <li>{{ $key }} ({{ gettype($value) }})</li>
                        @endif
                    @endforeach
                </ul>
            </div>

            <!-- Navigation Links -->
            <div class="mt-6 flex space-x-4">
                <a href="{{ route('storeFront') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    Xem trang chủ
                </a>
                <a href="{{ route('test.course-categories') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                    Test Categories Grid
                </a>
                <a href="{{ route('test.course-album') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded">
                    Test Course Album
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
