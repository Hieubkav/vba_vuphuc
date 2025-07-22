@extends('layouts.shop')

@section('title', 'Cảm ơn bạn đã đóng góp ý kiến - VBA Vũ Phúc')
@section('description', 'Cảm ơn bạn đã gửi ý kiến đóng góp cho VBA Vũ Phúc. Chúng tôi sẽ xem xét và phản hồi sớm nhất có thể.')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto text-center">
            <!-- Success Icon -->
            <div class="inline-flex items-center justify-center w-24 h-24 bg-green-100 rounded-full mb-8">
                <i class="fas fa-check-circle text-4xl text-green-600"></i>
            </div>

            <!-- Success Message -->
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
                Cảm ơn bạn đã đóng góp ý kiến!
            </h1>
            
            <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
                <p class="text-lg text-gray-600 mb-6">
                    Chúng tôi đã nhận được ý kiến đóng góp của bạn và rất trân trọng sự quan tâm này. 
                    Mọi góp ý đều giúp chúng tôi cải thiện chất lượng dịch vụ và mang đến trải nghiệm tốt hơn cho học viên.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <i class="fas fa-clock text-blue-500 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-1">Thời gian xử lý</h3>
                            <p class="text-gray-600 text-sm">Chúng tôi sẽ xem xét ý kiến của bạn trong vòng 1-2 ngày làm việc</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <i class="fas fa-eye text-purple-500 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-1">Hiển thị công khai</h3>
                            <p class="text-gray-600 text-sm">Sau khi được phê duyệt, ý kiến có thể được hiển thị trên website</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <i class="fas fa-envelope text-green-500 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-1">Phản hồi qua email</h3>
                            <p class="text-gray-600 text-sm">Nếu cần thiết, chúng tôi sẽ liên hệ với bạn qua email đã cung cấp</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <i class="fas fa-heart text-red-500 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-1">Cảm ơn chân thành</h3>
                            <p class="text-gray-600 text-sm">Sự đóng góp của bạn giúp VBA Vũ Phúc ngày càng hoàn thiện hơn</p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a 
                        href="{{ route('storeFront') }}" 
                        class="inline-flex items-center justify-center px-6 py-3 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors"
                    >
                        <i class="fas fa-home mr-2"></i>
                        Về trang chủ
                    </a>
                    
                    <a 
                        href="{{ route('courses.index') }}" 
                        class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                    >
                        <i class="fas fa-graduation-cap mr-2"></i>
                        Xem khóa học
                    </a>
                    
                    <a 
                        href="{{ route('feedback.show') }}" 
                        class="inline-flex items-center justify-center px-6 py-3 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors"
                    >
                        <i class="fas fa-plus mr-2"></i>
                        Gửi ý kiến khác
                    </a>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <div class="flex items-start space-x-3">
                    <i class="fas fa-info-circle text-blue-500 text-xl flex-shrink-0 mt-1"></i>
                    <div class="text-left">
                        <h3 class="font-semibold text-blue-900 mb-2">Thông tin thêm</h3>
                        <ul class="text-blue-800 text-sm space-y-1">
                            <li>• Bạn có thể theo dõi các khóa học mới tại trang <a href="{{ route('courses.index') }}" class="underline hover:no-underline">Khóa học</a></li>
                            <li>• Đăng ký nhận thông tin khóa học qua email tại trang <a href="{{ route('students.register') }}" class="underline hover:no-underline">Đăng ký học viên</a></li>
                            <li>• Liên hệ trực tiếp với chúng tôi qua các kênh hỗ trợ trên website</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Auto redirect after 30 seconds (optional)
// setTimeout(function() {
//     window.location.href = "{{ route('storeFront') }}";
// }, 30000);

// Add some celebration animation
document.addEventListener('DOMContentLoaded', function() {
    const successIcon = document.querySelector('.fa-check-circle');
    if (successIcon) {
        successIcon.style.animation = 'bounce 1s ease-in-out';
    }
});
</script>

<style>
@keyframes bounce {
    0%, 20%, 53%, 80%, 100% {
        transform: translate3d(0,0,0);
    }
    40%, 43% {
        transform: translate3d(0,-15px,0);
    }
    70% {
        transform: translate3d(0,-7px,0);
    }
    90% {
        transform: translate3d(0,-2px,0);
    }
}
</style>
@endpush
@endsection
