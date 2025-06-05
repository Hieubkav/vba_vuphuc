@extends('layouts.shop')

@section('title', 'Test Course Album Component')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Test Header -->
    <div class="bg-white shadow-sm py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900">Test Course Album Component</h1>
            <p class="text-gray-600 mt-2">Kiểm tra giao diện album khóa học với dữ liệu thực</p>
        </div>
    </div>

    <!-- Course Album Component -->
    <section class="py-12 md:py-16 bg-gray-50">
        @include('components.storefront.course-album')
    </section>

    <!-- Debug Info -->
    <div class="bg-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Debug Information</h2>
            
            @if(isset($courseCategories))
                <div class="bg-gray-100 p-4 rounded-lg">
                    <h3 class="font-semibold mb-2">Course Categories Data:</h3>
                    <pre class="text-sm overflow-x-auto">{{ json_encode($courseCategories->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                </div>
            @else
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <strong>Warning:</strong> $courseCategories variable is not available.
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
        </div>
    </div>
</div>
@endsection
