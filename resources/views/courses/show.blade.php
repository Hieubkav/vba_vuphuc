@extends('layouts.shop')

@section('title', $course->seo_title ?: $course->title . ' - VBA Vũ Phúc')
@section('description', $course->seo_description ?: Str::limit(strip_tags($course->description), 160))

@if($course->og_image_link)
@section('og_image', asset('storage/' . $course->og_image_link))
@elseif($course->thumbnail)
@section('og_image', asset('storage/' . $course->thumbnail))
@endif

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Course Header -->
    <div class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-8">
            <!-- Breadcrumb -->
            <nav class="text-sm mb-6">
                <ol class="flex items-center space-x-2 text-gray-500">
                    <li><a href="{{ route('storeFront') }}" class="hover:text-blue-600">Trang chủ</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li><a href="{{ route('courses.index') }}" class="hover:text-blue-600">Khóa học</a></li>
                    @if($course->category)
                    <li><span class="mx-2">/</span></li>
                    <li><a href="{{ route('courses.category', $course->category->slug) }}" class="hover:text-blue-600">{{ $course->category->name }}</a></li>
                    @endif
                    <li><span class="mx-2">/</span></li>
                    <li class="text-gray-900">{{ $course->title }}</li>
                </ol>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Course Info -->
                <div class="lg:col-span-2">
                    <div class="flex flex-wrap items-center gap-3 mb-4">
                        @if($course->category)
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-full">
                            {{ $course->category->name }}
                        </span>
                        @endif
                        
                        <span class="px-3 py-1 {{ $course->level === 'beginner' ? 'bg-green-100 text-green-800' : ($course->level === 'intermediate' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }} text-sm font-medium rounded-full">
                            {{ $course->level_display }}
                        </span>
                        
                        @if($course->is_featured)
                        <span class="px-3 py-1 bg-orange-100 text-orange-800 text-sm font-medium rounded-full">
                            <i class="fas fa-star mr-1"></i>Nổi bật
                        </span>
                        @endif
                    </div>

                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                        {{ $course->title }}
                    </h1>

                    <div class="flex flex-wrap items-center gap-6 text-gray-600 mb-6">
                        @if($course->instructor && $course->show_instructor)
                        <div class="flex items-center">
                            <i class="fas fa-user-tie mr-2"></i>
                            @if($course->instructor->slug)
                                <a href="{{ route('instructors.show', $course->instructor->slug) }}"
                                   class="hover:text-red-600 transition-colors duration-300">
                                    {{ $course->instructor->name }}
                                </a>
                            @else
                                <span>{{ $course->instructor->name }}</span>
                            @endif
                            @if($course->instructor->specialization)
                                <span class="text-sm text-gray-500 ml-1">({{ $course->instructor->specialization }})</span>
                            @endif
                        </div>
                        @endif
                        
                        <div class="flex items-center">
                            <i class="fas fa-clock mr-2"></i>
                            <span>{{ $course->duration_hours }} giờ</span>
                        </div>
                        
                        <div class="flex items-center">
                            <i class="fas fa-users mr-2"></i>
                            <span>{{ $course->enrolled_students_count }} học viên</span>
                        </div>
                        
                        <div class="flex items-center">
                            <i class="fas fa-calendar mr-2"></i>
                            <span>{{ $course->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>

                    <!-- Course Description -->
                    <div class="prose max-w-none mb-8">
                        {!! $course->description !!}
                    </div>
                </div>

                <!-- Course Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-lg p-6 sticky top-6">
                        <!-- Course Image với Fallback UI -->
                        <div class="mb-6">
                            @if($course->getMainImage() && $course->getMainImage() !== asset('images/placeholder-course.jpg'))
                                <img src="{{ $course->getMainImage() }}"
                                     alt="{{ $course->title }}"
                                     class="w-full h-48 object-cover rounded-lg"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            @endif

                            <!-- Fallback UI khi không có ảnh hoặc ảnh lỗi -->
                            <div class="w-full h-48 bg-gradient-to-br from-red-50 to-red-100 rounded-lg flex flex-col items-center justify-center text-red-600 relative {{ ($course->getMainImage() && $course->getMainImage() !== asset('images/placeholder-course.jpg')) ? 'hidden' : 'flex' }}">
                                <div class="text-center space-y-4">
                                    <i class="fas fa-graduation-cap text-5xl opacity-60"></i>
                                    <div class="space-y-2">
                                        <span class="block text-lg font-semibold opacity-80 line-clamp-2 px-4">{{ $course->title }}</span>
                                        <span class="block text-sm opacity-60">Khóa học VBA Vũ Phúc</span>
                                        @if($course->level)
                                        <span class="inline-block px-3 py-1 bg-red-200 text-red-800 text-xs font-medium rounded-full">
                                            {{ $course->level_display }}
                                        </span>
                                        @endif
                                        @if($course->instructor && $course->show_instructor)
                                        <span class="block text-xs opacity-50">{{ $course->instructor->name }}</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Decorative elements -->
                                <div class="absolute top-4 right-4 w-12 h-12 bg-red-200 rounded-full opacity-30"></div>
                                <div class="absolute bottom-4 left-4 w-8 h-8 bg-red-300 rounded-full opacity-20"></div>
                                <div class="absolute top-1/2 left-4 w-6 h-6 bg-red-400 rounded-full opacity-15"></div>
                            </div>
                        </div>

                        <!-- Price -->
                        @if($course->show_price)
                        <div class="mb-6">
                            <div class="flex items-center gap-3">
                                @if($course->compare_price && $course->compare_price > $course->price)
                                <span class="text-2xl font-bold text-gray-900">{{ number_format($course->price, 0, ',', '.') }} VNĐ</span>
                                <span class="text-lg text-gray-500 line-through">{{ number_format($course->compare_price, 0, ',', '.') }} VNĐ</span>
                                <span class="px-2 py-1 bg-red-100 text-red-800 text-sm font-medium rounded">
                                    -{{ $course->discount_percentage }}%
                                </span>
                                @else
                                <span class="text-2xl font-bold text-gray-900">{{ number_format($course->price, 0, ',', '.') }} VNĐ</span>
                                @endif
                            </div>
                        </div>
                        @endif

                        <!-- Enrollment Form -->
                        <livewire:enrollment-form :course="$course" />

                        <!-- Google Form và Group Links -->
                        @if(($course->gg_form && $course->show_form_link) || ($course->courseGroup?->group_link && $course->show_group_link))
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h3 class="font-semibold text-gray-900 mb-4">Liên kết khóa học:</h3>
                            <div class="space-y-3">
                                @if($course->gg_form && $course->show_form_link)
                                <a href="{{ $course->gg_form }}"
                                   target="_blank"
                                   class="flex items-center justify-center w-full px-4 py-3 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors duration-300">
                                    <i class="fas fa-file-alt mr-2"></i>
                                    Đăng ký qua Google Form
                                </a>
                                @endif

                                @if($course->courseGroup?->group_link && $course->show_group_link)
                                <a href="{{ $course->courseGroup->group_link }}"
                                   target="_blank"
                                   class="flex items-center justify-center w-full px-4 py-3 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors duration-300">
                                    <i class="fas fa-users mr-2"></i>
                                    Tham gia nhóm học tập
                                </a>
                                @endif
                            </div>
                        </div>
                        @endif

                        <!-- Course Features -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h3 class="font-semibold text-gray-900 mb-4">Khóa học bao gồm:</h3>
                            <ul class="space-y-3 text-sm text-gray-600">
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-3"></i>
                                    {{ $course->duration_hours }} giờ học
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-3"></i>
                                    Tài liệu học tập đầy đủ
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-3"></i>
                                    Hỗ trợ từ giảng viên
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-3"></i>
                                    Chứng chỉ hoàn thành
                                </li>
                                @if($course->max_students)
                                <li class="flex items-center">
                                    <i class="fas fa-users text-blue-500 mr-3"></i>
                                    Tối đa {{ $course->max_students }} học viên
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Course Content Tabs -->
    <div class="container mx-auto px-4 py-12">
        <div class="bg-white rounded-lg shadow-lg">
            <!-- Tab Navigation -->
            <div class="border-b border-gray-200">
                <nav class="flex space-x-8 px-6">
                    <button class="tab-button active py-4 px-2 border-b-2 border-blue-500 text-blue-600 font-medium" data-tab="overview">
                        Tổng quan
                    </button>
                    @if($course->requirements && is_array($course->requirements) && count($course->requirements) > 0)
                    <button class="tab-button py-4 px-2 border-b-2 border-transparent text-gray-500 hover:text-gray-700" data-tab="requirements">
                        Yêu cầu
                    </button>
                    @endif
                    @if($course->what_you_learn && is_array($course->what_you_learn) && count($course->what_you_learn) > 0)
                    <button class="tab-button py-4 px-2 border-b-2 border-transparent text-gray-500 hover:text-gray-700" data-tab="outcomes">
                        Kết quả học tập
                    </button>
                    @endif
                    @if(($openMaterials && $openMaterials->count() > 0) || ($enrolledMaterials && $enrolledMaterials->count() > 0))
                    <button class="tab-button py-4 px-2 border-b-2 border-transparent text-gray-500 hover:text-gray-700" data-tab="materials">
                        Tài liệu khóa học
                    </button>
                    @endif

                    @if($course->images && $course->images->where('status', 'active')->count() > 0)
                    <button class="tab-button py-4 px-2 border-b-2 border-transparent text-gray-500 hover:text-gray-700" data-tab="gallery">
                        Thư viện ảnh
                    </button>
                    @endif
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                <!-- Overview Tab -->
                <div id="overview" class="tab-content">
                    <div class="prose max-w-none">
                        {!! $course->description !!}
                    </div>
                    
                    @if($course->instructor && $course->show_instructor)
                    <div class="mt-8 pt-8 border-t border-gray-200">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-semibold">Về giảng viên</h3>
                            @if($course->instructor->slug)
                            <a href="{{ route('instructors.show', $course->instructor->slug) }}"
                               class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors duration-300 flex items-center">
                                <i class="fas fa-user-tie mr-2"></i>
                                Xem trang giảng viên
                            </a>
                            @endif
                        </div>
                        <div class="bg-gray-50 rounded-lg p-6">
                            <div class="flex items-start space-x-4">
                                @if($course->instructor->hasAvatar())
                                    <img src="{{ $course->instructor->avatar_url }}"
                                         alt="{{ $course->instructor->name }}"
                                         class="w-16 h-16 rounded-full object-cover"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                @endif

                                <!-- Fallback UI cho avatar giảng viên -->
                                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-red-100 to-red-200 flex items-center justify-center {{ $course->instructor->hasAvatar() ? 'hidden' : 'flex' }}">
                                    <i class="fas fa-user-tie text-red-600 text-xl opacity-70"></i>
                                </div>

                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-1">
                                        <h4 class="font-semibold text-lg">
                                            @if($course->instructor->slug)
                                                <a href="{{ route('instructors.show', $course->instructor->slug) }}"
                                                   class="hover:text-red-600 transition-colors duration-300">
                                                    {{ $course->instructor->name }}
                                                </a>
                                            @else
                                                <span>{{ $course->instructor->name }}</span>
                                            @endif
                                        </h4>

                                        @if($course->instructor->slug)
                                        <a href="{{ route('instructors.show', $course->instructor->slug) }}"
                                           class="px-3 py-1 bg-red-100 text-red-700 text-sm font-medium rounded-lg hover:bg-red-200 transition-colors duration-300 flex items-center">
                                            <i class="fas fa-arrow-right mr-1"></i>
                                            Chi tiết
                                        </a>
                                        @endif
                                    </div>

                                    @if($course->instructor->specialization)
                                        <p class="text-red-600 font-medium mb-2">{{ $course->instructor->specialization }}</p>
                                    @endif

                                    @if($course->instructor->experience_years > 0)
                                        <p class="text-sm text-gray-600 mb-2">
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ $course->instructor->experience_years }} năm kinh nghiệm
                                        </p>
                                    @endif

                                    @if($course->instructor->bio)
                                        <div class="text-gray-600 prose prose-sm max-w-none">
                                            {!! Str::limit($course->instructor->bio, 300) !!}
                                            @if(strlen($course->instructor->bio) > 300 && $course->instructor->slug)
                                                <a href="{{ route('instructors.show', $course->instructor->slug) }}"
                                                   class="text-red-600 hover:text-red-800 ml-2">
                                                    Xem thêm →
                                                </a>
                                            @endif
                                        </div>
                                    @endif

                                    @if($course->instructor->education)
                                        <div class="mt-3 text-sm text-gray-600">
                                            <strong>Học vấn:</strong> {{ $course->instructor->education }}
                                        </div>
                                    @endif

                                    @if($course->instructor->certifications && is_array($course->instructor->certifications) && count($course->instructor->certifications) > 0)
                                        <div class="mt-3">
                                            <strong class="text-sm text-gray-700">Chứng chỉ:</strong>
                                            <div class="flex flex-wrap gap-2 mt-1">
                                                @foreach(array_slice($course->instructor->certifications, 0, 3) as $cert)
                                                    <span class="inline-block bg-red-100 text-red-800 text-xs px-2 py-1 rounded">
                                                        {{ $cert['name'] }}
                                                        @if(isset($cert['issuer']))
                                                            - {{ $cert['issuer'] }}
                                                        @endif
                                                    </span>
                                                @endforeach
                                                @if(count($course->instructor->certifications) > 3 && $course->instructor->slug)
                                                    <a href="{{ route('instructors.show', $course->instructor->slug) }}"
                                                       class="text-red-600 hover:text-red-800 text-xs">
                                                        +{{ count($course->instructor->certifications) - 3 }} khác
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Requirements Tab -->
                @if($course->requirements && is_array($course->requirements) && count($course->requirements) > 0)
                <div id="requirements" class="tab-content hidden">
                    <h3 class="text-xl font-semibold mb-4">Yêu cầu đầu vào</h3>
                    <ul class="space-y-2">
                        @foreach($course->requirements as $requirement)
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mr-3 mt-1"></i>
                            <span>{{ $requirement['requirement'] ?? $requirement }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Learning Outcomes Tab -->
                @if($course->what_you_learn && is_array($course->what_you_learn) && count($course->what_you_learn) > 0)
                <div id="outcomes" class="tab-content hidden">
                    <h3 class="text-xl font-semibold mb-4">Những gì bạn sẽ học được</h3>
                    <ul class="space-y-2">
                        @foreach($course->what_you_learn as $outcome)
                        <li class="flex items-start">
                            <i class="fas fa-graduation-cap text-blue-500 mr-3 mt-1"></i>
                            <span>{{ $outcome['learning_outcome'] ?? $outcome }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Materials Tab -->
                @if(($openMaterials && $openMaterials->count() > 0) || ($enrolledMaterials && $enrolledMaterials->count() > 0))
                <div id="materials" class="tab-content hidden">
                    <h3 class="text-xl font-semibold mb-6">Tài liệu khóa học</h3>

                    <!-- Tài liệu mở (Open Materials) -->
                    @if($openMaterials && $openMaterials->count() > 0)
                    <div class="mb-8">
                        <div class="flex items-center mb-4">
                            <i class="fas fa-unlock text-green-600 mr-2"></i>
                            <h4 class="text-lg font-semibold text-green-700">Mở</h4>
                        </div>
                        <div class="grid gap-4">
                            @foreach($openMaterials as $material)
                            <div class="flex items-center justify-between p-4 bg-green-50 border border-green-200 rounded-lg">
                                <div class="flex items-center">
                                    <i class="{{ $material->file_icon }} text-green-600 mr-3"></i>
                                    <div>
                                        <h5 class="font-medium text-gray-900">{{ $material->title }}</h5>
                                        @if($material->description)
                                        <p class="text-sm text-gray-600 mt-1">{{ $material->description }}</p>
                                        @endif
                                        <div class="flex items-center gap-3 mt-2 text-xs text-gray-500">
                                            <span>{{ $material->formatted_file_size }}</span>
                                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded">
                                                Mở
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ $material->download_url }}"
                                       class="px-3 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors duration-300 flex items-center">
                                        <i class="fas fa-eye mr-2"></i>Xem
                                    </a>
                                    <a href="{{ $material->download_url }}" download
                                       class="px-3 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition-colors duration-300 flex items-center">
                                        <i class="fas fa-download mr-2"></i>Tải về
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
                            <i class="fas fa-key text-blue-600 mr-2"></i>
                            <h4 class="text-lg font-semibold text-blue-700">Dành cho học viên</h4>
                        </div>

                        <!-- Hiển thị danh sách tài liệu với icon khóa và tooltip -->
                        <div class="grid gap-4">
                            @foreach($enrolledMaterials as $material)
                            <div class="flex items-center justify-between p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fas fa-key text-blue-600 mr-3 text-lg"></i>
                                    <div>
                                        <h5 class="font-medium text-gray-900">{{ $material->title }}</h5>
                                        @if($material->description)
                                        <p class="text-sm text-gray-600 mt-1">{{ $material->description }}</p>
                                        @endif
                                        <div class="flex items-center gap-3 mt-2 text-xs text-gray-500">
                                            <span>{{ $material->formatted_file_size }}</span>
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded">
                                                Dành cho học viên
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Hiển thị icon khóa với tooltip -->
                                <div class="relative group">
                                    <div class="px-4 py-2 bg-gray-100 text-gray-500 text-sm rounded-lg flex items-center cursor-help">
                                        <i class="fas fa-key mr-2"></i>Khóa
                                    </div>
                                    <!-- Tooltip -->
                                    <div class="absolute bottom-full right-0 mb-2 px-3 py-2 bg-gray-800 text-white text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap z-10">
                                        Dành cho học viên đã đăng ký
                                        <div class="absolute top-full right-4 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-800"></div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Thông báo về tài liệu dành cho học viên -->
                        <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg text-center">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                            <span class="text-blue-700 text-sm">
                                {{ $enrolledMaterials->count() }} tài liệu dành cho học viên đã đăng ký khóa học
                            </span>
                        </div>
                    </div>
                    @endif


                </div>
                @endif

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

    <!-- Related Courses -->
    @if($relatedCourses && $relatedCourses->count() > 0)
    <div class="container mx-auto px-4 pb-12">
        <h2 class="text-2xl font-bold text-gray-900 mb-8">Khóa học liên quan</h2>

        @if($relatedCourses->count() == 3)
        <!-- Carousel cho đúng 3 khóa học -->
        <div class="relative">
            <div class="overflow-hidden">
                <div id="relatedCoursesCarousel" class="flex transition-transform duration-500 ease-in-out">
                    @foreach($relatedCourses as $relatedCourse)
                    <div class="w-full md:w-1/2 lg:w-1/3 flex-shrink-0 px-3">
                        <livewire:course-card :course="$relatedCourse" card-size="small" :key="'related-'.$relatedCourse->id" />
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Navigation buttons -->
            <button id="prevRelated" class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-white shadow-lg rounded-full p-3 text-gray-600 hover:text-red-600 transition-colors duration-300 z-10">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button id="nextRelated" class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-white shadow-lg rounded-full p-3 text-gray-600 hover:text-red-600 transition-colors duration-300 z-10">
                <i class="fas fa-chevron-right"></i>
            </button>

            <!-- Dots indicator -->
            <div class="flex justify-center mt-6 space-x-2">
                @for($i = 0; $i < $relatedCourses->count(); $i++)
                <button class="carousel-dot w-3 h-3 rounded-full bg-gray-300 hover:bg-red-500 transition-colors duration-300 {{ $i == 0 ? 'bg-red-500' : '' }}" data-slide="{{ $i }}"></button>
                @endfor
            </div>
        </div>
        @else
        <!-- Grid thông thường cho ít hơn hoặc nhiều hơn 3 khóa học -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($relatedCourses as $relatedCourse)
            <livewire:course-card :course="$relatedCourse" card-size="small" :key="'related-'.$relatedCourse->id" />
            @endforeach
        </div>
        @endif
    </div>
    @endif
</div>
@endsection

@push('scripts')
<!-- Gallery Popup Modal -->
<div id="galleryModal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden items-center justify-center">
    <div class="relative max-w-4xl max-h-full p-4">
        <!-- Close button -->
        <button id="closeModal" class="absolute top-4 right-4 text-white text-2xl hover:text-gray-300 z-10">
            <i class="fas fa-times"></i>
        </button>

        <!-- Previous button -->
        <button id="prevImage" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white text-2xl hover:text-gray-300 z-10">
            <i class="fas fa-chevron-left"></i>
        </button>

        <!-- Next button -->
        <button id="nextImage" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white text-2xl hover:text-gray-300 z-10">
            <i class="fas fa-chevron-right"></i>
        </button>

        <!-- Image container -->
        <div class="text-center">
            <img id="modalImage" src="" alt="" class="max-w-full max-h-[80vh] object-contain"
                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

            <!-- Fallback UI cho popup ảnh lỗi -->
            <div id="modalImageFallback" class="hidden max-w-md mx-auto bg-gradient-to-br from-red-50 to-red-100 rounded-lg p-8 items-center justify-center text-red-600" style="display: none; flex-direction: column;">
                <i class="fas fa-image text-6xl opacity-60 mb-4"></i>
                <h3 class="text-xl font-semibold mb-2 text-red-700">Ảnh không thể tải</h3>
                <p class="text-sm opacity-70 text-center">Ảnh này có thể đã bị xóa hoặc không tồn tại trong hệ thống</p>
                <!-- Decorative elements -->
                <div class="absolute top-4 right-4 w-8 h-8 bg-red-200 rounded-full opacity-30"></div>
                <div class="absolute bottom-4 left-4 w-6 h-6 bg-red-300 rounded-full opacity-20"></div>
                <div class="absolute top-1/2 left-4 w-4 h-4 bg-red-400 rounded-full opacity-15"></div>
            </div>

            <div class="mt-4 text-white">
                <h3 id="modalTitle" class="text-xl font-semibold mb-2"></h3>
                <p id="modalDescription" class="text-gray-300"></p>
                <div class="mt-2 text-sm text-gray-400">
                    <span id="imageCounter"></span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const galleryItems = document.querySelectorAll('.gallery-item');
    const modal = document.getElementById('galleryModal');
    const modalImage = document.getElementById('modalImage');
    const modalTitle = document.getElementById('modalTitle');
    const modalDescription = document.getElementById('modalDescription');
    const imageCounter = document.getElementById('imageCounter');
    const closeModal = document.getElementById('closeModal');
    const prevImage = document.getElementById('prevImage');
    const nextImage = document.getElementById('nextImage');

    let currentImageIndex = 0;
    let images = [];

    // Collect all images data
    galleryItems.forEach((item, index) => {
        images.push({
            src: item.dataset.src,
            title: item.dataset.title,
            description: item.dataset.description || ''
        });

        // Add click event to open modal
        item.addEventListener('click', function() {
            currentImageIndex = index;
            openModal();
        });
    });

    function openModal() {
        if (images.length === 0) return;

        const image = images[currentImageIndex];
        const modalImageFallback = document.getElementById('modalImageFallback');

        // Reset image display
        modalImage.style.display = 'block';
        modalImageFallback.style.display = 'none';

        modalImage.src = image.src;
        modalImage.alt = image.title;
        modalTitle.textContent = image.title;
        modalDescription.textContent = image.description;
        imageCounter.textContent = `${currentImageIndex + 1} / ${images.length}`;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeModalFunc() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }

    function showPrevImage() {
        currentImageIndex = currentImageIndex > 0 ? currentImageIndex - 1 : images.length - 1;
        openModal();
    }

    function showNextImage() {
        currentImageIndex = currentImageIndex < images.length - 1 ? currentImageIndex + 1 : 0;
        openModal();
    }

    // Event listeners
    closeModal.addEventListener('click', closeModalFunc);
    prevImage.addEventListener('click', showPrevImage);
    nextImage.addEventListener('click', showNextImage);

    // Close modal when clicking outside image
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModalFunc();
        }
    });

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (!modal.classList.contains('hidden')) {
            switch(e.key) {
                case 'Escape':
                    closeModalFunc();
                    break;
                case 'ArrowLeft':
                    showPrevImage();
                    break;
                case 'ArrowRight':
                    showNextImage();
                    break;
            }
        }
    });
});
</script>
@endpush

@push('scripts')
<script>
// Tab functionality
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const targetTab = button.getAttribute('data-tab');

            // Remove active class from all buttons and contents
            tabButtons.forEach(btn => {
                btn.classList.remove('active', 'border-blue-500', 'text-blue-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            });
            tabContents.forEach(content => content.classList.add('hidden'));

            // Add active class to clicked button and show content
            button.classList.add('active', 'border-blue-500', 'text-blue-600');
            button.classList.remove('border-transparent', 'text-gray-500');
            document.getElementById(targetTab).classList.remove('hidden');
        });
    });

    // Related Courses Carousel functionality
    const carousel = document.getElementById('relatedCoursesCarousel');
    const prevBtn = document.getElementById('prevRelated');
    const nextBtn = document.getElementById('nextRelated');
    const dots = document.querySelectorAll('.carousel-dot');

    if (carousel && prevBtn && nextBtn) {
        let currentSlide = 0;
        const totalSlides = dots.length;

        function updateCarousel() {
            const translateX = -currentSlide * (100 / totalSlides);
            carousel.style.transform = `translateX(${translateX}%)`;

            // Update dots
            dots.forEach((dot, index) => {
                if (index === currentSlide) {
                    dot.classList.add('bg-red-500');
                    dot.classList.remove('bg-gray-300');
                } else {
                    dot.classList.remove('bg-red-500');
                    dot.classList.add('bg-gray-300');
                }
            });
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % totalSlides;
            updateCarousel();
        }

        function prevSlide() {
            currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
            updateCarousel();
        }

        // Event listeners
        nextBtn.addEventListener('click', nextSlide);
        prevBtn.addEventListener('click', prevSlide);

        // Dot navigation
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                currentSlide = index;
                updateCarousel();
            });
        });

        // Auto-play carousel (optional)
        setInterval(nextSlide, 5000);
    }
});
</script>
@endpush
