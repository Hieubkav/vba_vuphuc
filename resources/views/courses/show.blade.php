@extends('layouts.shop')

@section('title', $course->seo_title ?: $course->title)
@section('meta_description', $course->seo_description ?: $course->short_description)

@push('styles')
<style>
    /* Gallery Grid Styles */
    .gallery-item {
        border-radius: 12px;
        overflow: hidden;
        background: #fff;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .gallery-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    /* Responsive gallery grid */
    @media (max-width: 640px) {
        .gallery-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }
    }

    @media (min-width: 641px) and (max-width: 768px) {
        .gallery-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
        }
    }

    @media (min-width: 769px) {
        .gallery-grid {
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
        }
    }

    /* Smooth image loading */
    .gallery-item img {
        transition: opacity 0.3s ease, transform 0.3s ease;
    }

    .gallery-item img[src=""] {
        opacity: 0;
    }
</style>
@endpush



@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Breadcrumb -->
    <div class="bg-white border-b">
        <div class="container mx-auto px-4 py-3">
            <nav class="flex items-center space-x-2 text-sm text-gray-600">
                <a href="{{ route('storeFront') }}" class="hover:text-red-600 transition-colors">Trang chủ</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <a href="{{ route('courses.index') }}" class="hover:text-red-600 transition-colors">Khóa học</a>
                @if($course->category)
                <i class="fas fa-chevron-right text-xs"></i>
                <a href="{{ route('courses.cat-category', $course->category->slug) }}" class="hover:text-red-600 transition-colors">{{ $course->category->name }}</a>
                @endif
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-gray-900 font-medium">{{ $course->title }}</span>
            </nav>
        </div>
    </div>

    <!-- Course Header -->
    <div class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-8">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Course Image -->
                <div class="lg:w-2/5">
                    <!-- Main Course Thumbnail -->
                    <div class="relative overflow-hidden rounded-xl shadow-lg">
                        @php
                            // Sử dụng thumbnail từ Course model làm ảnh đại diện chính
                            $thumbnailUrl = null;
                            if ($course->thumbnail && \Illuminate\Support\Facades\Storage::disk('public')->exists($course->thumbnail)) {
                                $thumbnailUrl = asset('storage/' . $course->thumbnail);
                            }
                        @endphp

                        @if($thumbnailUrl)
                        <img src="{{ $thumbnailUrl }}"
                             alt="{{ $course->title }}"
                             class="w-full h-64 lg:h-80 object-cover"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        @endif

                        <div class="w-full h-64 lg:h-80 bg-gradient-to-br from-red-50 to-red-100 flex items-center justify-center {{ $thumbnailUrl ? 'hidden' : '' }}">
                            <div class="text-center text-red-600">
                                <i class="fas fa-graduation-cap text-6xl opacity-60 mb-4"></i>
                                <p class="text-lg font-medium">{{ $course->title }}</p>
                            </div>
                        </div>

                        <!-- Course Badge -->
                        @if($course->courseCategory)
                        <div class="absolute top-4 left-4">
                            <span class="inline-block px-3 py-1 bg-red-600 text-white text-sm font-medium rounded-full shadow-lg">
                                {{ $course->courseCategory->name }}
                            </span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Course Info -->
                <div class="lg:w-3/5">
                    <div class="mb-6">
                        <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4 leading-tight">{{ $course->title }}</h1>
                    </div>

                    <!-- Course Meta Grid -->
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                        @if($course->duration)
                        <div class="text-center p-4 bg-gray-50 rounded-lg border">
                            <i class="fas fa-clock text-red-500 text-2xl mb-2"></i>
                            <p class="text-sm text-gray-600 mb-1">Thời lượng</p>
                            <p class="font-semibold text-gray-900">{{ $course->duration }}</p>
                        </div>
                        @endif

                        @if($course->level)
                        <div class="text-center p-4 bg-gray-50 rounded-lg border">
                            <i class="fas fa-signal text-green-500 text-2xl mb-2"></i>
                            <p class="text-sm text-gray-600 mb-1">Cấp độ</p>
                            <p class="font-semibold text-gray-900">{{ $course->level_display }}</p>
                        </div>
                        @endif

                        <div class="text-center p-4 bg-gray-50 rounded-lg border">
                            <i class="fas fa-users text-blue-500 text-2xl mb-2"></i>
                            <p class="text-sm text-gray-600 mb-1">Học viên</p>
                            <p class="font-semibold text-gray-900">{{ $course->students_count }}</p>
                        </div>

                        <div class="text-center p-4 bg-gray-50 rounded-lg border">
                            <i class="fas fa-calendar text-orange-500 text-2xl mb-2"></i>
                            <p class="text-sm text-gray-600 mb-1">Cập nhật</p>
                            <p class="font-semibold text-gray-900">{{ $course->updated_at->format('d/m/Y') }}</p>
                        </div>
                    </div>

                    <!-- Instructor Info -->
                    @if($course->instructor && $course->show_instructor)
                    <div class="mb-8 p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center">
                                <i class="fas fa-user-tie text-white text-lg"></i>
                            </div>
                            <div>
                                <p class="text-sm text-blue-600 font-medium">Giảng viên</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $course->instructor->name }}</p>
                                @if($course->instructor->specialization)
                                <p class="text-sm text-gray-600">{{ $course->instructor->specialization }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        @if($course->gg_form && $course->show_form_link)
                        <a href="{{ $course->gg_form }}" target="_blank"
                           class="flex-1 bg-red-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-red-700 transition-colors text-center">
                            <i class="fas fa-user-plus mr-2"></i>
                            Đăng ký khóa học
                        </a>
                        @endif

                        @if($course->courseGroup && $course->courseGroup->group_link && $course->show_group_link)
                        <a href="{{ $course->courseGroup->group_link }}" target="_blank"
                           class="px-6 py-3 border border-blue-600 text-blue-600 rounded-lg font-semibold hover:bg-blue-50 transition-colors text-center">
                            <i class="fas fa-users mr-2"></i>
                            Tham gia nhóm
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Course Content -->
    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-8">
                        <!-- Course Description -->
                        @if($course->description)
                        <div class="mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">Mô tả khóa học</h2>
                            <div class="prose max-w-none text-gray-700 leading-relaxed">
                                {!! $course->description !!}
                            </div>
                        </div>
                        @endif

                        <!-- Course Gallery -->
                        @if($course->images && $course->images->where('status', 'active')->count() > 0)
                        <div class="mb-8">
                            <div class="flex items-center justify-between mb-6">
                                <h2 class="text-2xl font-bold text-gray-900">Hình ảnh khóa học</h2>
                                <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                                    {{ $course->images->where('status', 'active')->count() }} ảnh
                                </span>
                            </div>

                            <!-- Gallery Grid với spacing tối ưu -->
                            <div class="gallery-grid grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                @foreach($course->images->where('status', 'active')->sortBy('order')->take(8) as $index => $image)
                                @php
                                    $galleryImageUrl = null;
                                    if (\Illuminate\Support\Facades\Storage::disk('public')->exists($image->image_link)) {
                                        $galleryImageUrl = asset('storage/' . $image->image_link);
                                    }
                                @endphp
                                <div class="group relative overflow-hidden rounded-lg cursor-pointer gallery-item aspect-square shadow-md hover:shadow-lg transition-all duration-300"
                                     data-src="{{ $galleryImageUrl ?: asset('images/placeholder-course.jpg') }}"
                                     data-title="{{ $course->title }}"
                                     data-description="Hình ảnh {{ $index + 1 }}">
                                    @if($galleryImageUrl)
                                    <img src="{{ $galleryImageUrl }}"
                                         alt="{{ $image->alt_text ?: $course->title }}"
                                         class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    @endif

                                    <div class="w-full h-full bg-gradient-to-br from-red-50 to-red-100 flex flex-col items-center justify-center text-red-600 {{ $galleryImageUrl ? 'hidden' : '' }}">
                                        <i class="fas fa-image text-2xl opacity-60 mb-2"></i>
                                        <span class="text-xs font-medium">Ảnh {{ $index + 1 }}</span>
                                    </div>

                                    <!-- Overlay với icon zoom -->
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-all duration-300 flex items-center justify-center">
                                        <div class="transform scale-75 group-hover:scale-100 transition-transform duration-300 opacity-0 group-hover:opacity-100">
                                            <div class="bg-white bg-opacity-90 rounded-full p-3">
                                                <i class="fas fa-search-plus text-gray-800 text-lg"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            @if($course->images->where('status', 'active')->count() > 8)
                            <div class="text-center mt-6">
                                <p class="text-sm text-gray-500 bg-gray-50 inline-block px-4 py-2 rounded-full">
                                    <i class="fas fa-images mr-2"></i>
                                    Còn {{ $course->images->where('status', 'active')->count() - 8 }} ảnh khác
                                </p>
                            </div>
                            @endif
                        </div>
                        @endif

                        <!-- What You'll Learn -->
                        @if($course->objectives && count($course->objectives) > 0)
                        <div class="mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">Bạn sẽ học được gì</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($course->objectives as $objective)
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0 w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mt-0.5">
                                        <i class="fas fa-check text-green-600 text-sm"></i>
                                    </div>
                                    <span class="text-gray-700">{{ $objective }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Requirements -->
                        @if($course->requirements && count($course->requirements) > 0)
                        <div class="mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">Yêu cầu</h2>
                            <div class="space-y-3">
                                @foreach($course->requirements as $requirement)
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0 w-6 h-6 bg-orange-100 rounded-full flex items-center justify-center mt-0.5">
                                        <i class="fas fa-exclamation text-orange-600 text-sm"></i>
                                    </div>
                                    <span class="text-gray-700">{{ $requirement }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Course Materials Section -->
                @if(($openMaterials && $openMaterials->count() > 0) || ($enrolledMaterials && $enrolledMaterials->count() > 0))
                <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Tài liệu khóa học</h3>

                        <!-- Auth Status Alert -->
                        @if(!auth('student')->check())
                            <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                <div class="flex items-start">
                                    <svg class="h-4 w-4 text-blue-400 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                    <div>
                                        <p class="text-blue-800 font-medium text-sm">Đăng nhập để xem tài liệu</p>
                                        <p class="text-blue-700 text-xs mt-1">
                                            <a href="{{ route('auth.login') }}?redirect={{ urlencode(request()->fullUrl()) }}" class="underline hover:no-underline">Đăng nhập</a>
                                            để truy cập tài liệu.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @elseif(auth('student')->check() && $isLoggedIn)
                            <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                                <div class="flex items-start">
                                    <svg class="h-4 w-4 text-green-400 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    <div>
                                        <p class="text-green-800 font-medium text-sm">Đã đăng nhập</p>
                                        <p class="text-green-700 text-xs mt-1">
                                            Bạn có thể truy cập tất cả tài liệu học viên.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Open Materials -->
                        @if($openMaterials && $openMaterials->count() > 0)
                        <div class="mb-6">
                            <div class="flex items-center mb-3">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <h4 class="text-sm font-semibold text-green-700">Tài liệu mở</h4>
                            </div>
                            <div class="space-y-3">
                                @foreach($openMaterials as $material)
                                <div class="p-3 bg-green-50 border border-green-200 rounded-lg hover:bg-green-100 transition-colors">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0">
                                            <i class="{{ $material->file_icon }} text-lg text-green-600"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h5 class="font-medium text-gray-900 text-sm truncate">{{ $material->title }}</h5>
                                            <div class="flex items-center space-x-2 mt-1 text-xs text-gray-500">
                                                <span>{{ $material->file_size_formatted }}</span>
                                                <span class="px-1.5 py-0.5 bg-green-100 text-green-800 rounded text-xs">Miễn phí</span>
                                            </div>
                                            <div class="flex items-center space-x-2 mt-2">
                                                @if($material->canPreview())
                                                    <a href="{{ route('course-materials.view', $material) }}"
                                                       target="_blank"
                                                       class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition-colors">
                                                        Xem
                                                    </a>
                                                @endif
                                                <a href="{{ route('course.material.download', $material->id) }}"
                                                   class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded hover:bg-green-200 transition-colors">
                                                    Tải về
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Enrolled Materials -->
                        @if($enrolledMaterials && $enrolledMaterials->count() > 0)
                        <div class="mb-4">
                            <div class="flex items-center mb-3">
                                <svg class="w-4 h-4 text-blue-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 616 0z" clip-rule="evenodd" />
                                </svg>
                                <h4 class="text-sm font-semibold text-blue-700">Tài liệu học viên</h4>
                            </div>

                            <div class="space-y-3">
                                @foreach($enrolledMaterials as $material)
                                <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg {{ $isLoggedIn ? 'hover:bg-blue-100' : 'opacity-60' }} transition-colors">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0">
                                            <i class="{{ $material->file_icon }} text-lg {{ $isLoggedIn ? 'text-blue-600' : 'text-gray-400' }}"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h5 class="font-medium text-gray-900 text-sm truncate">{{ $material->title }}</h5>
                                            <div class="flex items-center space-x-2 mt-1 text-xs text-gray-500">
                                                <span>{{ $material->file_size_formatted }}</span>
                                                <span class="px-1.5 py-0.5 bg-blue-100 text-blue-800 rounded text-xs">Học viên</span>
                                            </div>
                                            <div class="flex items-center space-x-2 mt-2">
                                                @if($isLoggedIn)
                                                    <a href="{{ route('course-materials.view', $material) }}"
                                                       target="_blank"
                                                       class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition-colors">
                                                        Xem
                                                    </a>
                                                    <a href="{{ route('course.material.download', $material->id) }}"
                                                       class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded hover:bg-green-200 transition-colors">
                                                        Tải về
                                                    </a>
                                                @else
                                                    <div class="px-2 py-1 text-xs bg-gray-100 text-gray-500 rounded cursor-not-allowed">
                                                        <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 616 0z" clip-rule="evenodd" />
                                                        </svg>
                                                        Khóa
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Related Courses -->
                @if($relatedCourses && $relatedCourses->count() > 0)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Khóa học liên quan</h3>
                        <div class="space-y-4">
                            @foreach($relatedCourses->take(3) as $relatedCourse)
                            <a href="{{ route('courses.show', $relatedCourse->slug) }}" class="block group">
                                <div class="flex space-x-3">
                                    <div class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden">
                                        @php
                                            // Sử dụng thumbnail từ Course model
                                            $relatedImageUrl = null;
                                            if ($relatedCourse->thumbnail && \Illuminate\Support\Facades\Storage::disk('public')->exists($relatedCourse->thumbnail)) {
                                                $relatedImageUrl = asset('storage/' . $relatedCourse->thumbnail);
                                            }
                                        @endphp

                                        @if($relatedImageUrl)
                                        <img src="{{ $relatedImageUrl }}"
                                             alt="{{ $relatedCourse->title }}"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        @endif

                                        <div class="w-full h-full bg-gradient-to-br from-red-50 to-red-100 flex items-center justify-center {{ $relatedImageUrl ? 'hidden' : '' }}">
                                            <i class="fas fa-graduation-cap text-red-400"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-medium text-gray-900 group-hover:text-red-600 transition-colors line-clamp-2">
                                            {{ $relatedCourse->title }}
                                        </h4>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ $relatedCourse->category->name ?? 'Khóa học' }}
                                        </p>
                                        <div class="flex items-center mt-2 text-xs text-gray-400">
                                            <i class="fas fa-users mr-1"></i>
                                            <span>{{ $relatedCourse->students_count }} học viên</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

</div>

<!-- Gallery Modal -->
<div id="galleryModal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative max-w-6xl w-full">
            <!-- Close button -->
            <button id="closeModal" class="absolute top-4 right-4 text-white hover:text-gray-300 z-20 bg-black bg-opacity-50 rounded-full p-2 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>

            <!-- Navigation buttons -->
            <button id="prevImage" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white hover:text-gray-300 z-20 bg-black bg-opacity-50 rounded-full p-3 transition-colors">
                <i class="fas fa-chevron-left text-xl"></i>
            </button>
            <button id="nextImage" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white hover:text-gray-300 z-20 bg-black bg-opacity-50 rounded-full p-3 transition-colors">
                <i class="fas fa-chevron-right text-xl"></i>
            </button>

            <!-- Image container -->
            <div class="text-center">
                <img id="modalImage" src="" alt="" class="max-w-full max-h-[85vh] object-contain mx-auto rounded-lg shadow-2xl">
                <div id="modalInfo" class="mt-6 text-white max-w-2xl mx-auto">
                    <h3 id="modalTitle" class="text-2xl font-bold mb-2"></h3>
                    <p id="modalDescription" class="text-gray-300 leading-relaxed"></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gallery Modal functionality
    const galleryItems = document.querySelectorAll('.gallery-item');
    const modal = document.getElementById('galleryModal');

    if (galleryItems.length > 0 && modal) {
        const modalImage = document.getElementById('modalImage');
        const modalTitle = document.getElementById('modalTitle');
        const modalDescription = document.getElementById('modalDescription');
        const closeModal = document.getElementById('closeModal');
        const prevImage = document.getElementById('prevImage');
        const nextImage = document.getElementById('nextImage');

        let currentImageIndex = 0;
        const images = Array.from(galleryItems).map(item => ({
            src: item.getAttribute('data-src'),
            title: item.getAttribute('data-title') || '',
            description: item.getAttribute('data-description') || ''
        }));

        function showImage(index) {
            if (images[index]) {
                modalImage.src = images[index].src;
                modalTitle.textContent = images[index].title;
                modalDescription.textContent = images[index].description;
                currentImageIndex = index;

                // Show/hide navigation buttons
                prevImage.style.display = images.length > 1 ? 'block' : 'none';
                nextImage.style.display = images.length > 1 ? 'block' : 'none';
            }
        }

        function openModal(index) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            showImage(index);
        }

        function closeModalFunc() {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Event listeners
        galleryItems.forEach((item, index) => {
            item.addEventListener('click', () => openModal(index));
        });

        closeModal.addEventListener('click', closeModalFunc);

        prevImage.addEventListener('click', () => {
            const newIndex = currentImageIndex > 0 ? currentImageIndex - 1 : images.length - 1;
            showImage(newIndex);
        });

        nextImage.addEventListener('click', () => {
            const newIndex = currentImageIndex < images.length - 1 ? currentImageIndex + 1 : 0;
            showImage(newIndex);
        });

        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (!modal.classList.contains('hidden')) {
                switch(e.key) {
                    case 'Escape':
                        closeModalFunc();
                        break;
                    case 'ArrowLeft':
                        if (images.length > 1) {
                            const newIndex = currentImageIndex > 0 ? currentImageIndex - 1 : images.length - 1;
                            showImage(newIndex);
                        }
                        break;
                    case 'ArrowRight':
                        if (images.length > 1) {
                            const newIndex = currentImageIndex < images.length - 1 ? currentImageIndex + 1 : 0;
                            showImage(newIndex);
                        }
                        break;
                }
            }
        });

        // Close modal on backdrop click
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModalFunc();
            }
        });
    }

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});
</script>
@endsection
