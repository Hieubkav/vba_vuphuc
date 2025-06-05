@extends('layouts.shop')

@section('title', 'Đăng ký thành công - VBA Vũ Phúc')
@section('description', 'Cảm ơn bạn đã đăng ký trở thành học viên tại VBA Vũ Phúc. Chúng tôi sẽ liên hệ với bạn sớm nhất.')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto text-center">
            <!-- Success Icon -->
            <div class="w-24 h-24 mx-auto mb-8 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-check text-4xl text-green-600"></i>
            </div>

            <!-- Success Message -->
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                Đăng ký thành công!
            </h1>
            
            <p class="text-xl text-gray-600 mb-8">
                Cảm ơn bạn đã đăng ký trở thành học viên tại VBA Vũ Phúc. 
                Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất.
            </p>

            <!-- Next Steps -->
            <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Bước tiếp theo</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-envelope text-2xl text-blue-600"></i>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Kiểm tra email</h3>
                        <p class="text-sm text-gray-600">
                            Chúng tôi đã gửi email xác nhận đến địa chỉ email của bạn
                        </p>
                    </div>

                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-phone text-2xl text-green-600"></i>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Chờ liên hệ</h3>
                        <p class="text-sm text-gray-600">
                            Đội ngũ tư vấn sẽ liên hệ với bạn trong vòng 24 giờ
                        </p>
                    </div>

                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-purple-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-graduation-cap text-2xl text-purple-600"></i>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Bắt đầu học</h3>
                        <p class="text-sm text-gray-600">
                            Chọn khóa học phù hợp và bắt đầu hành trình học tập
                        </p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
                <a href="{{ route('courses.index') }}" 
                   class="inline-flex items-center px-8 py-4 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-graduation-cap mr-2"></i>
                    Khám phá khóa học
                </a>
                
                <a href="{{ route('storeFront') }}" 
                   class="inline-flex items-center px-8 py-4 border-2 border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-home mr-2"></i>
                    Về trang chủ
                </a>
            </div>

            <!-- Contact Information -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg p-8">
                <h3 class="text-xl font-bold mb-4">Cần hỗ trợ ngay?</h3>
                <p class="mb-6 opacity-90">
                    Nếu bạn có bất kỳ câu hỏi nào, đừng ngần ngại liên hệ với chúng tôi
                </p>
                
                <div class="flex flex-col sm:flex-row gap-6 justify-center">
                    @if(isset($globalSettings))
                    @if($globalSettings->phone)
                    <a href="tel:{{ $globalSettings->phone }}" 
                       class="flex items-center justify-center px-6 py-3 bg-white bg-opacity-20 rounded-lg hover:bg-opacity-30 transition-colors">
                        <i class="fas fa-phone mr-2"></i>
                        {{ $globalSettings->phone }}
                    </a>
                    @endif
                    
                    @if($globalSettings->email)
                    <a href="mailto:{{ $globalSettings->email }}" 
                       class="flex items-center justify-center px-6 py-3 bg-white bg-opacity-20 rounded-lg hover:bg-opacity-30 transition-colors">
                        <i class="fas fa-envelope mr-2"></i>
                        {{ $globalSettings->email }}
                    </a>
                    @endif
                    @endif
                </div>
            </div>

            <!-- Social Proof -->
            @if(isset($courseStats))
            <div class="mt-12 pt-8 border-t border-gray-200">
                <p class="text-gray-600 mb-6">Tham gia cùng cộng đồng học viên của chúng tôi</p>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ $courseStats['total_students'] ?? 0 }}+</div>
                        <div class="text-sm text-gray-600">Học viên</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600">{{ $courseStats['total_courses'] ?? 0 }}+</div>
                        <div class="text-sm text-gray-600">Khóa học</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-600">{{ $courseStats['total_instructors'] ?? 0 }}+</div>
                        <div class="text-sm text-gray-600">Giảng viên</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-orange-600">{{ $courseStats['completion_rate'] ?? 0 }}%</div>
                        <div class="text-sm text-gray-600">Hoàn thành</div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto redirect after 30 seconds (optional)
    // setTimeout(function() {
    //     window.location.href = "{{ route('courses.index') }}";
    // }, 30000);

    // Track registration success for analytics
    if (typeof gtag !== 'undefined') {
        gtag('event', 'sign_up', {
            method: 'website_form'
        });
    }

    // Show success animation
    const successIcon = document.querySelector('.fa-check');
    if (successIcon) {
        successIcon.style.transform = 'scale(0)';
        setTimeout(() => {
            successIcon.style.transition = 'transform 0.5s ease-out';
            successIcon.style.transform = 'scale(1)';
        }, 100);
    }
});
</script>
@endpush
