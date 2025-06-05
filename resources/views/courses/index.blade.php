@extends('layouts.shop')

@section('title', 'Danh sách khóa học - VBA Vũ Phúc')
@section('description', 'Khám phá các khóa học chất lượng cao tại VBA Vũ Phúc. Học từ các chuyên gia hàng đầu với phương pháp giảng dạy hiện đại.')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-red-600 to-red-700 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="text-center">
                <h1 class="text-3xl md:text-4xl font-bold mb-3 flex items-center justify-center">
                    <i class="fas fa-graduation-cap mr-4 text-red-200"></i>
                    Khóa học chất lượng cao
                </h1>
                <p class="text-lg md:text-xl mb-6 opacity-90 max-w-3xl mx-auto">
                    Học từ các chuyên gia hàng đầu với phương pháp giảng dạy hiện đại và thực tiễn
                </p>

                <!-- Quick Stats -->
                @if(isset($stats))
                <div class="mt-6">
                    <!-- Stats Grid - Responsive và thông minh -->
                    <div class="flex flex-wrap justify-center gap-3 sm:gap-4 max-w-4xl mx-auto">
                        <!-- Total Courses -->
                        <div class="flex-shrink-0 text-center bg-white/10 rounded-lg px-4 py-3 backdrop-blur-sm min-w-[100px]">
                            <div class="text-xl sm:text-2xl font-bold">{{ $stats['total'] ?? 0 }}</div>
                            <div class="text-xs sm:text-sm opacity-80 whitespace-nowrap">Khóa học</div>
                        </div>

                        @if(isset($stats['levels']) && count($stats['levels']) > 0)
                        @foreach($stats['levels'] as $level => $count)
                        <div class="flex-shrink-0 text-center bg-white/10 rounded-lg px-4 py-3 backdrop-blur-sm min-w-[100px]">
                            <div class="text-xl sm:text-2xl font-bold">{{ $count }}</div>
                            <div class="text-xs sm:text-sm opacity-80 whitespace-nowrap">
                                @if($level === 'beginner')
                                    Cơ bản
                                @elseif($level === 'intermediate')
                                    Trung cấp
                                @elseif($level === 'advanced')
                                    Nâng cao
                                @else
                                    {{ ucfirst($level) }}
                                @endif
                            </div>
                        </div>
                        @endforeach
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Course List with Filters -->
    <div class="container mx-auto px-4 py-12">
        <livewire:course-list />
    </div>

    <!-- Call to Action -->
    <div class="bg-gradient-to-r from-red-50 to-red-100 py-12">
        <div class="container mx-auto px-4 text-center">
            <div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-lg p-8 border border-red-100">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4 flex items-center justify-center">
                    <i class="fas fa-rocket mr-3 text-red-500"></i>
                    Bắt đầu hành trình học tập của bạn
                </h2>
                <p class="text-lg text-gray-600 mb-6 max-w-2xl mx-auto">
                    Tham gia cùng hàng nghìn học viên đã tin tưởng và đạt được thành công với các khóa học của chúng tôi
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#"
                       class="inline-flex items-center px-6 py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-colors shadow-md">
                        <i class="fas fa-phone mr-2"></i>
                        Liên hệ tư vấn
                    </a>
                    <a href="#"
                       class="inline-flex items-center px-6 py-3 bg-white text-red-600 font-semibold rounded-lg hover:bg-red-50 transition-colors border-2 border-red-600">
                        <i class="fas fa-info-circle mr-2"></i>
                        Tìm hiểu thêm
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Course search functionality
document.addEventListener('DOMContentLoaded', function() {
    // Add any additional JavaScript for course interactions
    console.log('Course index page loaded');
});
</script>
@endpush
