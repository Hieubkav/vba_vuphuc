@extends('layouts.shop')

@section('title', $course->title)

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Course Header -->
    <div class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-8">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Course Image -->
                <div class="lg:w-1/3">
                    <div class="relative overflow-hidden rounded-lg shadow-lg">
                        @if($course->featured_image && $course->featured_image->exists())
                        <img src="{{ $course->featured_image->full_image_url }}"
                             alt="{{ $course->featured_image->alt_text }}"
                             class="w-full h-64 lg:h-80 object-cover">
                        @else
                        <div class="w-full h-64 lg:h-80 bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center">
                            <div class="text-center text-blue-600">
                                <i class="fas fa-graduation-cap text-6xl opacity-60 mb-4"></i>
                                <p class="text-lg font-medium">{{ $course->title }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Course Info -->
                <div class="lg:w-2/3">
                    <div class="mb-4">
                        @if($course->category)
                        <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-full">
                            {{ $course->category->name }}
                        </span>
                        @endif
                    </div>

                    <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">{{ $course->title }}</h1>

                    @if($course->short_description)
                    <p class="text-lg text-gray-600 mb-6">{{ $course->short_description }}</p>
                    @endif

                    <!-- Course Meta -->
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                        @if($course->duration)
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <i class="fas fa-clock text-blue-500 text-xl mb-2"></i>
                            <p class="text-sm text-gray-600">Thời lượng</p>
                            <p class="font-semibold">{{ $course->duration }}</p>
                        </div>
                        @endif

                        @if($course->level)
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <i class="fas fa-signal text-green-500 text-xl mb-2"></i>
                            <p class="text-sm text-gray-600">Cấp độ</p>
                            <p class="font-semibold">{{ $course->level }}</p>
                        </div>
                        @endif

                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <i class="fas fa-users text-purple-500 text-xl mb-2"></i>
                            <p class="text-sm text-gray-600">Học viên</p>
                            <p class="font-semibold">{{ $course->students_count ?? 0 }}</p>
                        </div>

                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <i class="fas fa-calendar text-orange-500 text-xl mb-2"></i>
                            <p class="text-sm text-gray-600">Cập nhật</p>
                            <p class="font-semibold">{{ $course->updated_at->format('d/m/Y') }}</p>
                        </div>
                    </div>

                    <!-- CTA Button -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <button class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                            <i class="fas fa-play mr-2"></i>
                            Đăng ký khóa học
                        </button>
                        <button class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-colors">
                            <i class="fas fa-heart mr-2"></i>
                            Yêu thích
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Course Content -->
    <div class="container mx-auto px-4 py-12">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Tabs Navigation -->
            <div class="border-b border-gray-200">
                <nav class="flex space-x-8 px-8">
                    <button class="tab-button py-4 px-1 border-b-2 border-blue-500 text-blue-600 font-medium" data-tab="overview">
                        Thông tin khóa học
                    </button>
                    @if($course->images && $course->images->where('status', 'active')->count() > 0)
                    <button class="tab-button py-4 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium" data-tab="gallery">
                        Thư viện ảnh
                    </button>
                    @endif
                </nav>
            </div>

            <!-- Tab Contents -->
            <div class="p-8">
                <!-- Overview Tab -->
                <div id="overview" class="tab-content">
                    @if($course->description)
                    <div class="prose max-w-none mb-8">
                        <h3 class="text-xl font-semibold mb-4">Mô tả khóa học</h3>
                        <div class="text-gray-700 leading-relaxed">
                            {!! nl2br(e($course->description)) !!}
                        </div>
                    </div>
                    @endif

                    @if($course->objectives && count($course->objectives) > 0)
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold mb-4">Mục tiêu khóa học</h3>
                        <ul class="space-y-2">
                            @foreach($course->objectives as $objective)
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                                <span class="text-gray-700">{{ $objective }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @if($course->requirements && count($course->requirements) > 0)
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold mb-4">Yêu cầu</h3>
                        <ul class="space-y-2">
                            @foreach($course->requirements as $requirement)
                            <li class="flex items-start">
                                <i class="fas fa-exclamation-circle text-orange-500 mt-1 mr-3"></i>
                                <span class="text-gray-700">{{ $requirement }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>

                <!-- Course Gallery -->
                @if($course->images && $course->images->where('status', 'active')->count() > 0)
                <div id="gallery" class="tab-content hidden">
                    <h3 class="text-xl font-semibold mb-6">Thư viện ảnh khóa học</h3>

                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($course->images->where('status', 'active')->sortBy('order') as $image)
                        <div class="group relative overflow-hidden rounded-lg cursor-pointer gallery-item"
                             data-src="{{ $image->full_image_url }}"
                             data-title="{{ $image->title }}"
                             data-description="{{ $image->description }}">
                            @if($image->exists())
                            <img src="{{ $image->full_image_url }}"
                                 alt="{{ $image->alt_text }}"
                                 class="w-full h-32 object-cover transition-transform duration-300 group-hover:scale-110">
                            @else
                            <!-- Fallback UI cho ảnh không tồn tại -->
                            <div class="w-full h-32 bg-gradient-to-br from-red-50 to-red-100 flex flex-col items-center justify-center text-red-600 relative">
                                <i class="fas fa-image text-2xl opacity-60"></i>
                                <span class="text-xs opacity-50 mt-1">Ảnh không tồn tại</span>
                                <!-- Decorative elements -->
                                <div class="absolute top-2 right-2 w-4 h-4 bg-red-200 rounded-full opacity-30"></div>
                                <div class="absolute bottom-2 left-2 w-3 h-3 bg-red-300 rounded-full opacity-20"></div>
                            </div>
                            @endif

                            <!-- Overlay -->
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all duration-300 flex items-center justify-center">
                                <i class="fas fa-search-plus text-white text-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></i>
                            </div>

                            <!-- Title overlay -->
                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-3">
                                <p class="text-white text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    {{ $image->title }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Course Materials Section -->
    @if(($openMaterials && $openMaterials->count() > 0) || ($enrolledMaterials && $enrolledMaterials->count() > 0))
    <div class="container mx-auto px-4 py-12">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-8">Tài liệu khóa học</h2>

            <!-- Auth Status Alert -->
            @if(!auth('student')->check())
                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-blue-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                        <div>
                            <p class="text-blue-800 font-medium">Đăng nhập để xem tài liệu khóa học</p>
                            <p class="text-blue-700 text-sm mt-1">
                                <a href="{{ route('auth.login') }}" class="underline hover:no-underline">Đăng nhập</a>
                                hoặc
                                <a href="{{ route('auth.register') }}" class="underline hover:no-underline">đăng ký</a>
                                để truy cập tài liệu dành cho học viên.
                            </p>
                        </div>
                    </div>
                </div>
            @elseif(auth('student')->check() && !$course->students()->where('student_id', auth('student')->id())->exists())
                <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-yellow-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        <div>
                            <p class="text-yellow-800 font-medium">Bạn chưa đăng ký khóa học này</p>
                            <p class="text-yellow-700 text-sm mt-1">
                                Vui lòng đăng ký khóa học để truy cập tài liệu dành cho học viên.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Tài liệu mở (Open Materials) -->
            @if($openMaterials && $openMaterials->count() > 0)
            <div class="mb-8">
                <div class="flex items-center mb-4">
                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <h3 class="text-lg font-semibold text-green-700">Tài liệu mở</h3>
                    <span class="ml-2 text-sm text-gray-500">(Miễn phí cho tất cả)</span>
                </div>
                <div class="space-y-4">
                    @foreach($openMaterials as $material)
                    <div class="flex items-center justify-between p-4 bg-green-50 border border-green-200 rounded-lg hover:bg-green-100 transition-colors">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <i class="{{ $material->file_icon }} text-2xl text-green-600"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900">{{ $material->title }}</h4>
                                @if($material->description)
                                <p class="text-sm text-gray-600 mt-1">{{ $material->description }}</p>
                                @endif
                                <div class="flex items-center space-x-4 mt-2 text-xs text-gray-500">
                                    <span>{{ $material->file_size_formatted }}</span>
                                    <span>{{ $material->file_type }}</span>
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full">Miễn phí</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            @if($material->canPreview())
                                <button class="px-3 py-2 text-sm bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors">
                                    Xem trước
                                </button>
                            @endif
                            <a href="{{ route('course.material.download', $material->id) }}"
                               class="px-3 py-2 text-sm bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors">
                                Tải về
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Tài liệu dành cho học viên (Enrolled Materials) -->
            @if($enrolledMaterials && $enrolledMaterials->count() > 0)
            <div class="mb-8">
                <div class="flex items-center mb-4">
                    <svg class="w-5 h-5 text-blue-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 616 0z" clip-rule="evenodd" />
                    </svg>
                    <h3 class="text-lg font-semibold text-blue-700">Tài liệu dành cho học viên</h3>
                    <span class="ml-2 text-sm text-gray-500">(Cần đăng ký khóa học)</span>
                </div>

                @php
                    $user = auth('student')->user();
                    $isEnrolled = $user ? $course->students()->where('student_id', $user->id)->exists() : false;
                @endphp

                <div class="space-y-4">
                    @foreach($enrolledMaterials as $material)
                    <div class="flex items-center justify-between p-4 bg-blue-50 border border-blue-200 rounded-lg {{ $isEnrolled ? 'hover:bg-blue-100' : 'opacity-60' }} transition-colors">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <i class="{{ $material->file_icon }} text-2xl {{ $isEnrolled ? 'text-blue-600' : 'text-gray-400' }}"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900">{{ $material->title }}</h4>
                                @if($material->description)
                                <p class="text-sm text-gray-600 mt-1">{{ $material->description }}</p>
                                @endif
                                <div class="flex items-center space-x-4 mt-2 text-xs text-gray-500">
                                    <span>{{ $material->file_size_formatted }}</span>
                                    <span>{{ $material->file_type }}</span>
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full">Học viên</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            @if($isEnrolled)
                                @if($material->canPreview())
                                    <button class="px-3 py-2 text-sm bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors">
                                        Xem trước
                                    </button>
                                @endif
                                <a href="{{ route('course.material.download', $material->id) }}"
                                   class="px-3 py-2 text-sm bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors">
                                    Tải về
                                </a>
                            @else
                                <div class="px-3 py-2 text-sm bg-gray-100 text-gray-500 rounded-lg cursor-not-allowed">
                                    <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 616 0z" clip-rule="evenodd" />
                                    </svg>
                                    Khóa
                                </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif
</div>

<!-- Gallery Modal -->
<div id="galleryModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative max-w-4xl w-full">
            <!-- Close button -->
            <button id="closeModal" class="absolute top-4 right-4 text-white hover:text-gray-300 z-10">
                <i class="fas fa-times text-2xl"></i>
            </button>

            <!-- Navigation buttons -->
            <button id="prevImage" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white hover:text-gray-300 z-10">
                <i class="fas fa-chevron-left text-3xl"></i>
            </button>
            <button id="nextImage" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white hover:text-gray-300 z-10">
                <i class="fas fa-chevron-right text-3xl"></i>
            </button>

            <!-- Image container -->
            <div class="text-center">
                <img id="modalImage" src="" alt="" class="max-w-full max-h-[80vh] object-contain mx-auto">
                <div id="modalInfo" class="mt-4 text-white">
                    <h3 id="modalTitle" class="text-xl font-semibold mb-2"></h3>
                    <p id="modalDescription" class="text-gray-300"></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab functionality
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const targetTab = button.getAttribute('data-tab');

            // Remove active classes
            tabButtons.forEach(btn => {
                btn.classList.remove('border-blue-500', 'text-blue-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            });

            tabContents.forEach(content => {
                content.classList.add('hidden');
            });

            // Add active classes
            button.classList.remove('border-transparent', 'text-gray-500');
            button.classList.add('border-blue-500', 'text-blue-600');

            // Show target content
            const targetContent = document.getElementById(targetTab);
            if (targetContent) {
                targetContent.classList.remove('hidden');
            }
        });
    });

    // Gallery functionality
    const galleryItems = document.querySelectorAll('.gallery-item');
    const modal = document.getElementById('galleryModal');
    const modalImage = document.getElementById('modalImage');
    const modalTitle = document.getElementById('modalTitle');
    const modalDescription = document.getElementById('modalDescription');
    const closeModal = document.getElementById('closeModal');
    const prevImage = document.getElementById('prevImage');
    const nextImage = document.getElementById('nextImage');

    let currentImageIndex = 0;
    const images = Array.from(galleryItems).map(item => ({
        src: item.getAttribute('data-src'),
        title: item.getAttribute('data-title'),
        description: item.getAttribute('data-description')
    }));

    function showImage(index) {
        if (images[index]) {
            modalImage.src = images[index].src;
            modalTitle.textContent = images[index].title || '';
            modalDescription.textContent = images[index].description || '';
            currentImageIndex = index;
        }
    }

    galleryItems.forEach((item, index) => {
        item.addEventListener('click', () => {
            modal.classList.remove('hidden');
            showImage(index);
        });
    });

    closeModal.addEventListener('click', () => {
        modal.classList.add('hidden');
    });

    prevImage.addEventListener('click', () => {
        const newIndex = currentImageIndex > 0 ? currentImageIndex - 1 : images.length - 1;
        showImage(newIndex);
    });

    nextImage.addEventListener('click', () => {
        const newIndex = currentImageIndex < images.length - 1 ? currentImageIndex + 1 : 0;
        showImage(newIndex);
    });

    // Close modal on escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            modal.classList.add('hidden');
        }
    });

    // Close modal on backdrop click
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.add('hidden');
        }
    });
});
</script>
@endsection
