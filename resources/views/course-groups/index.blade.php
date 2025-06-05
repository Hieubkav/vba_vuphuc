@extends('layouts.shop')

@section('title', 'Nhóm học tập - VBA Vũ Phúc')
@section('description', 'Tham gia các nhóm học tập VBA Excel để kết nối với cộng đồng, chia sẻ kinh nghiệm và nhận hỗ trợ từ giảng viên.')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-red-50 to-white py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-6">
                    Nhóm học tập VBA Excel
                </h1>

                <!-- Stats - Chỉ hiển thị số nhóm -->
                <div class="inline-flex items-center bg-white rounded-lg px-6 py-3 shadow-sm">
                    <div class="text-xl font-bold text-red-600 mr-2">{{ $courseGroups->total() }}</div>
                    <div class="text-sm text-gray-600">nhóm học tập</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Course Groups Grid -->
    <section class="py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($courseGroups->count() > 0)
                <!-- Groups Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                    @foreach($courseGroups as $group)
                        <div class="group bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden border border-gray-100">
                            <div class="p-6">
                                <!-- Group Icon & Type -->
                                <div class="flex items-center justify-between mb-4">
                                    <div class="w-12 h-12 rounded-lg bg-red-50 flex items-center justify-center">
                                        <i class="{{ $group->icon ?? 'fas fa-users' }} text-lg text-red-600"></i>
                                    </div>
                                    <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                                        {{ ucfirst($group->group_type ?? 'Nhóm') }}
                                    </span>
                                </div>

                                <!-- Group Name -->
                                <h3 class="text-lg font-semibold text-gray-900 mb-2 group-hover:text-red-600 transition-colors duration-300">
                                    {{ $group->name }}
                                </h3>

                                <!-- Group Description -->
                                <p class="text-gray-600 text-sm leading-relaxed mb-4">
                                    {{ Str::limit($group->description, 120) }}
                                </p>

                                <!-- Members & Level -->
                                <div class="flex items-center justify-between text-sm mb-4">
                                    <div class="flex items-center text-gray-600">
                                        <i class="fas fa-users mr-2 text-xs"></i>
                                        <span>{{ $group->current_members ?? 0 }}</span>
                                        @if($group->max_members)
                                            <span class="text-gray-400">/{{ $group->max_members }}</span>
                                        @endif
                                        <span class="ml-1">thành viên</span>
                                    </div>
                                    
                                    <span class="text-xs font-medium px-2 py-1 rounded-full
                                        @if($group->level === 'beginner') bg-green-100 text-green-800
                                        @elseif($group->level === 'intermediate') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        @if($group->level === 'beginner') Cơ bản
                                        @elseif($group->level === 'intermediate') Trung cấp
                                        @else Nâng cao
                                        @endif
                                    </span>
                                </div>

                                <!-- Instructor -->
                                @if($group->instructor_name)
                                    <div class="flex items-center text-sm text-gray-600 mb-4">
                                        <i class="fas fa-user-tie mr-2 text-xs"></i>
                                        <span>{{ $group->instructor_name }}</span>
                                    </div>
                                @endif

                                <!-- Action Button -->
                                @if($group->group_link)
                                    <a href="{{ $group->group_link }}"
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-300">
                                        <i class="{{ $group->icon ?? 'fas fa-external-link-alt' }} mr-2 text-xs"></i>
                                        @if(isset($group->max_members) && $group->current_members >= $group->max_members)
                                            <span>Nhóm đã đầy</span>
                                        @else
                                            <span>Tham gia nhóm</span>
                                        @endif
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="flex justify-center">
                    {{ $courseGroups->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-16">
                    <div class="w-16 h-16 mx-auto mb-4 bg-red-50 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-xl text-red-600 opacity-60"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Chưa có nhóm học tập</h3>
                    <p class="text-gray-600 text-sm mb-6">Các nhóm học tập sẽ sớm được cập nhật</p>
                    <a href="{{ route('courses.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors duration-300">
                        <i class="fas fa-graduation-cap mr-2"></i>
                        Xem khóa học
                    </a>
                </div>
            @endif
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-white py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">
                Bạn muốn tìm hiểu thêm về khóa học?
            </h2>
            <p class="text-gray-600 mb-6">
                Khám phá các khóa học VBA Excel chuyên nghiệp và nâng cao kỹ năng của bạn
            </p>
            <a href="{{ route('courses.index') }}"
               class="inline-flex items-center px-6 py-3 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors duration-300">
                <i class="fas fa-graduation-cap mr-2"></i>
                Xem tất cả khóa học
            </a>
        </div>
    </section>
</div>
@endsection
