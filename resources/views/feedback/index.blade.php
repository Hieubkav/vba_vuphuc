@extends('layouts.shop')

@section('title', 'Đóng góp ý kiến - VBA Vũ Phúc')
@section('description', 'Chia sẻ ý kiến đóng góp của bạn về khóa học và dịch vụ tại VBA Vũ Phúc. Chúng tôi luôn lắng nghe và cải thiện để phục vụ bạn tốt hơn.')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="container mx-auto px-4">
        <!-- Header Section -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-red-100 rounded-full mb-6">
                <i class="fas fa-comments text-2xl text-red-600"></i>
            </div>
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Đóng góp ý kiến</h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Chia sẻ trải nghiệm và ý kiến của bạn về khóa học tại VBA Vũ Phúc. 
                Mọi góp ý của bạn đều rất quý giá và giúp chúng tôi cải thiện chất lượng dịch vụ.
            </p>
        </div>

        <!-- Feedback Form -->
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-lg shadow-lg p-8">
                <!-- Success/Error Messages -->
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-3"></i>
                            <p class="text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                            <p class="text-red-800">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                <form action="{{ route('feedback.store') }}" method="POST" id="feedbackForm" novalidate>
                    @csrf
                    
                    <!-- Name Field -->
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Họ và tên <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="{{ old('name') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors @error('name') border-red-500 @enderror"
                            placeholder="Nhập họ và tên của bạn"
                            required
                        >
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Field -->
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors @error('email') border-red-500 @enderror"
                            placeholder="Nhập email của bạn"
                            required
                        >
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Rating Field -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Đánh giá tổng thể
                        </label>
                        <div class="flex items-center space-x-2">
                            @for($i = 1; $i <= 5; $i++)
                                <label class="cursor-pointer">
                                    <input 
                                        type="radio" 
                                        name="rating" 
                                        value="{{ $i }}" 
                                        class="sr-only rating-input"
                                        {{ old('rating', 5) == $i ? 'checked' : '' }}
                                    >
                                    <i class="fas fa-star text-2xl rating-star {{ old('rating', 5) >= $i ? 'text-yellow-400' : 'text-gray-300' }} hover:text-yellow-400 transition-colors"></i>
                                </label>
                            @endfor
                            <span class="ml-3 text-sm text-gray-600" id="ratingText">Tuyệt vời</span>
                        </div>
                        @error('rating')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Content Field -->
                    <div class="mb-6">
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                            Nội dung ý kiến <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            id="content" 
                            name="content" 
                            rows="6"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors resize-none @error('content') border-red-500 @enderror"
                            placeholder="Chia sẻ trải nghiệm, ý kiến đóng góp của bạn về khóa học, giảng viên, hoặc dịch vụ tại VBA Vũ Phúc..."
                            required
                            minlength="10"
                            maxlength="1000"
                        >{{ old('content') }}</textarea>
                        <div class="flex justify-between items-center mt-2">
                            @error('content')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @else
                                <p class="text-sm text-gray-500">Tối thiểu 10 ký tự</p>
                            @enderror
                            <p class="text-sm text-gray-500">
                                <span id="charCount">{{ strlen(old('content', '')) }}</span>/1000
                            </p>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <button 
                            type="submit" 
                            class="flex-1 bg-red-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            id="submitBtn"
                        >
                            <i class="fas fa-paper-plane mr-2"></i>
                            Gửi ý kiến
                        </button>
                        <a 
                            href="{{ route('storeFront') }}" 
                            class="flex-1 bg-gray-100 text-gray-700 px-6 py-3 rounded-lg font-medium hover:bg-gray-200 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors text-center"
                        >
                            <i class="fas fa-arrow-left mr-2"></i>
                            Quay lại trang chủ
                        </a>
                    </div>
                </form>
            </div>

            <!-- Additional Info -->
            <div class="mt-8 text-center">
                <div class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 rounded-lg">
                    <i class="fas fa-info-circle mr-2"></i>
                    <span class="text-sm">Ý kiến của bạn sẽ được xem xét và có thể được hiển thị công khai sau khi được phê duyệt</span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Rating stars functionality
    const ratingInputs = document.querySelectorAll('.rating-input');
    const ratingStars = document.querySelectorAll('.rating-star');
    const ratingText = document.getElementById('ratingText');
    const ratingTexts = ['Rất tệ', 'Tệ', 'Bình thường', 'Tốt', 'Tuyệt vời'];

    ratingInputs.forEach((input, index) => {
        input.addEventListener('change', function() {
            updateStars(index + 1);
            ratingText.textContent = ratingTexts[index];
        });
    });

    ratingStars.forEach((star, index) => {
        star.addEventListener('click', function() {
            ratingInputs[index].checked = true;
            updateStars(index + 1);
            ratingText.textContent = ratingTexts[index];
        });
    });

    function updateStars(rating) {
        ratingStars.forEach((star, index) => {
            if (index < rating) {
                star.classList.remove('text-gray-300');
                star.classList.add('text-yellow-400');
            } else {
                star.classList.remove('text-yellow-400');
                star.classList.add('text-gray-300');
            }
        });
    }

    // Character counter
    const contentTextarea = document.getElementById('content');
    const charCount = document.getElementById('charCount');

    contentTextarea.addEventListener('input', function() {
        charCount.textContent = this.value.length;
    });

    // Form validation
    const form = document.getElementById('feedbackForm');
    const submitBtn = document.getElementById('submitBtn');

    form.addEventListener('submit', function(e) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang gửi...';
    });
});
</script>
@endpush
@endsection
