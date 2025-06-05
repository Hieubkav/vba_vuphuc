@extends('layouts.shop')

@section('title', $instructor->name . ' - Giảng viên VBA Vũ Phúc')
@section('description', $instructor->bio ? Str::limit(strip_tags($instructor->bio), 160) : 'Thông tin chi tiết về giảng viên ' . $instructor->name)

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Instructor Header -->
    <div class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-8">
            <!-- Breadcrumb -->
            <nav class="text-sm mb-6">
                <ol class="flex items-center space-x-2 text-gray-500">
                    <li><a href="{{ route('storeFront') }}" class="hover:text-red-600">Trang chủ</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li><a href="{{ route('courses.index') }}" class="hover:text-red-600">Khóa học</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li class="text-gray-900">{{ $instructor->name }}</li>
                </ol>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Instructor Info -->
                <div class="lg:col-span-2">
                    <div class="flex flex-col md:flex-row gap-6">
                        <!-- Avatar -->
                        <div class="flex-shrink-0">
                            @if($instructor->avatar)
                                <img src="{{ asset('storage/' . $instructor->avatar) }}"
                                     alt="{{ $instructor->name }}"
                                     class="w-32 h-32 rounded-full object-cover border-4 border-red-100">
                            @else
                                <div class="w-32 h-32 rounded-full bg-red-100 flex items-center justify-center border-4 border-red-200">
                                    <i class="fas fa-user text-red-600 text-4xl"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Info -->
                        <div class="flex-1">
                            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">
                                {{ $instructor->name }}
                            </h1>

                            @if($instructor->specialization)
                                <p class="text-xl text-red-600 font-medium mb-4">{{ $instructor->specialization }}</p>
                            @endif

                            <div class="flex flex-wrap items-center gap-6 text-gray-600 mb-6">
                                @if($instructor->experience_years > 0)
                                <div class="flex items-center">
                                    <i class="fas fa-clock mr-2 text-red-600"></i>
                                    <span>{{ $instructor->experience_years }} năm kinh nghiệm</span>
                                </div>
                                @endif
                                
                                @if($instructor->education)
                                <div class="flex items-center">
                                    <i class="fas fa-graduation-cap mr-2 text-red-600"></i>
                                    <span>{{ $instructor->education }}</span>
                                </div>
                                @endif
                                
                                <div class="flex items-center">
                                    <i class="fas fa-chalkboard-teacher mr-2 text-red-600"></i>
                                    <span>{{ $courses->total() }} khóa học</span>
                                </div>
                            </div>

                            @if($instructor->bio)
                            <div class="prose max-w-none">
                                {!! $instructor->bio !!}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Instructor Stats -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-lg p-6 sticky top-6">
                        <h3 class="font-semibold text-gray-900 mb-4">Thông tin giảng viên</h3>
                        
                        @if($instructor->certifications && count($instructor->certifications) > 0)
                        <div class="mb-6">
                            <h4 class="font-medium text-gray-700 mb-3">Chứng chỉ</h4>
                            <div class="space-y-2">
                                @foreach($instructor->certifications as $cert)
                                    <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                                        <div class="font-medium text-red-800">{{ $cert['name'] }}</div>
                                        @if(isset($cert['issuer']))
                                            <div class="text-sm text-red-600">{{ $cert['issuer'] }}</div>
                                        @endif
                                        @if(isset($cert['year']))
                                            <div class="text-xs text-gray-500">{{ $cert['year'] }}</div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Contact Info -->
                        <div class="space-y-3">
                            @if($instructor->email)
                            <div class="flex items-center text-sm">
                                <i class="fas fa-envelope text-red-600 mr-3 w-4"></i>
                                <a href="mailto:{{ $instructor->email }}" class="text-gray-600 hover:text-red-600">
                                    {{ $instructor->email }}
                                </a>
                            </div>
                            @endif

                            @if($instructor->phone)
                            <div class="flex items-center text-sm">
                                <i class="fas fa-phone text-red-600 mr-3 w-4"></i>
                                <a href="tel:{{ $instructor->phone }}" class="text-gray-600 hover:text-red-600">
                                    {{ $instructor->phone }}
                                </a>
                            </div>
                            @endif

                            @if($instructor->website)
                            <div class="flex items-center text-sm">
                                <i class="fas fa-globe text-red-600 mr-3 w-4"></i>
                                <a href="{{ $instructor->website }}" target="_blank" class="text-gray-600 hover:text-red-600">
                                    Website
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Instructor Courses -->
    @if($courses && $courses->count() > 0)
    <div class="container mx-auto px-4 py-12">
        <h2 class="text-2xl font-bold text-gray-900 mb-8">Khóa học của {{ $instructor->name }}</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
            @foreach($courses as $course)
            <livewire:course-card :course="$course" :key="'instructor-course-'.$course->id" />
            @endforeach
        </div>

        <!-- Pagination -->
        @if($courses->hasPages())
        <div class="flex justify-center">
            {{ $courses->links() }}
        </div>
        @endif
    </div>
    @else
    <div class="container mx-auto px-4 py-12">
        <div class="text-center py-12">
            <i class="fas fa-chalkboard-teacher text-gray-400 text-6xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Chưa có khóa học</h3>
            <p class="text-gray-600">{{ $instructor->name }} chưa có khóa học nào được công bố.</p>
        </div>
    </div>
    @endif
</div>
@endsection
