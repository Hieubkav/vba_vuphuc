@extends('layouts.shop')

@section('title', 'Test Course Groups Component')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="container mx-auto px-4">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Test Course Groups Component</h1>
            <p class="text-gray-600">Kiểm tra component nhóm khóa học Facebook/Zalo</p>
        </div>

        <!-- Course Groups Component -->
        <section class="py-12 md:py-16 bg-gradient-to-br from-blue-50 via-white to-purple-50 rounded-2xl">
            @include('components.storefront.course-groups')
        </section>

        <!-- Debug Info -->
        <div class="mt-12 bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Debug Information</h3>
            <div class="space-y-2 text-sm">
                <p><strong>Course Groups Count:</strong> {{ isset($courseGroups) ? $courseGroups->count() : 'Not available' }}</p>
                <p><strong>ViewServiceProvider Status:</strong> {{ class_exists('App\Providers\ViewServiceProvider') ? 'Loaded' : 'Not loaded' }}</p>
                <p><strong>Cache Status:</strong> {{ Cache::has('storefront_course_groups') ? 'Cached' : 'Not cached' }}</p>
            </div>
            
            @if(isset($courseGroups) && $courseGroups->isNotEmpty())
            <div class="mt-4">
                <h4 class="font-medium mb-2">Available Course Groups:</h4>
                <div class="bg-gray-50 p-3 rounded text-xs">
                    @foreach($courseGroups as $course)
                    <div class="mb-2 pb-2 border-b border-gray-200 last:border-b-0">
                        <strong>{{ $course->title }}</strong><br>
                        Level: {{ $course->level }}<br>
                        Group Link: {{ $course->group_link ? 'Available' : 'Not set' }}<br>
                        Show Group Link: {{ $course->show_group_link ? 'Yes' : 'No' }}
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
