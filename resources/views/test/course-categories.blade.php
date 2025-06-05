@extends('layouts.shop')

@section('title', 'Test Course Categories Component')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Test Header -->
    <div class="bg-white shadow-sm py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900">Test Course Categories Component</h1>
            <p class="text-gray-600 mt-2">Kiểm tra giao diện danh mục khóa học với dữ liệu thực</p>
        </div>
    </div>

    <!-- Course Categories Component -->
    <section class="py-12 md:py-16 bg-white">
        @include('components.storefront.course-categories')
    </section>

    <!-- Debug Info -->
    <div class="bg-gray-100 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Debug Information</h2>
            
            @if(isset($courseCategoriesGrid))
                <div class="bg-white p-4 rounded-lg mb-4">
                    <h3 class="font-semibold mb-2">Course Categories Grid Data:</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-4 py-2 text-left">ID</th>
                                    <th class="px-4 py-2 text-left">Name</th>
                                    <th class="px-4 py-2 text-left">Slug</th>
                                    <th class="px-4 py-2 text-left">Color</th>
                                    <th class="px-4 py-2 text-left">Icon</th>
                                    <th class="px-4 py-2 text-left">Courses Count</th>
                                    <th class="px-4 py-2 text-left">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($courseCategoriesGrid as $cat)
                                    <tr class="border-t">
                                        <td class="px-4 py-2">{{ $cat->id }}</td>
                                        <td class="px-4 py-2">{{ $cat->name }}</td>
                                        <td class="px-4 py-2">{{ $cat->slug }}</td>
                                        <td class="px-4 py-2">
                                            <span class="inline-block w-4 h-4 rounded" style="background-color: {{ $cat->color }}"></span>
                                            {{ $cat->color }}
                                        </td>
                                        <td class="px-4 py-2">{{ $cat->icon }}</td>
                                        <td class="px-4 py-2">{{ $cat->courses_count }}</td>
                                        <td class="px-4 py-2">
                                            <span class="px-2 py-1 text-xs rounded {{ $cat->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $cat->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
        </div>
    </div>
</div>
@endsection
