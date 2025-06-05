@extends('layouts.shop')

@section('title', 'Đăng ký học viên - VBA Vũ Phúc')
@section('description', 'Đăng ký trở thành học viên tại VBA Vũ Phúc để tham gia các khóa học chất lượng cao từ các chuyên gia hàng đầu.')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="container mx-auto px-4">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                Đăng ký học viên
            </h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Tham gia cộng đồng học tập của chúng tôi và bắt đầu hành trình phát triển bản thân
            </p>
        </div>

        <div class="max-w-4xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Registration Form -->
                <div class="bg-white rounded-lg shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Thông tin đăng ký</h2>
                    
                    <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        
                        <!-- Personal Information -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Thông tin cá nhân</h3>
                            
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Họ và tên <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       id="name"
                                       name="name"
                                       value="{{ old('name') }}"
                                       class="w-full px-4 py-3 border @error('name') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Nhập họ và tên đầy đủ"
                                       required>
                                @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                        Email <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email"
                                           id="email"
                                           name="email"
                                           value="{{ old('email') }}"
                                           class="w-full px-4 py-3 border @error('email') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           placeholder="example@email.com"
                                           required>
                                    @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                        Số điện thoại
                                    </label>
                                    <input type="tel"
                                           id="phone"
                                           name="phone"
                                           value="{{ old('phone') }}"
                                           class="w-full px-4 py-3 border @error('phone') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           placeholder="0123456789">
                                    @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2">
                                        Ngày sinh
                                    </label>
                                    <input type="date"
                                           id="birth_date"
                                           name="birth_date"
                                           value="{{ old('birth_date') }}"
                                           class="w-full px-4 py-3 border @error('birth_date') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    @error('birth_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">
                                        Giới tính
                                    </label>
                                    <select id="gender"
                                            name="gender"
                                            class="w-full px-4 py-3 border @error('gender') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">Chọn giới tính</option>
                                        <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Nam</option>
                                        <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Nữ</option>
                                        <option value="other" {{ old('gender') === 'other' ? 'selected' : '' }}>Khác</option>
                                    </select>
                                    @error('gender')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                    Địa chỉ
                                </label>
                                <textarea id="address"
                                          name="address"
                                          rows="3"
                                          class="w-full px-4 py-3 border @error('address') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                          placeholder="Nhập địa chỉ của bạn">{{ old('address') }}</textarea>
                                @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Professional Information -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Thông tin nghề nghiệp</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="occupation" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nghề nghiệp
                                    </label>
                                    <input type="text" 
                                           id="occupation" 
                                           name="occupation" 
                                           value="{{ old('occupation') }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('occupation') border-red-500 @enderror"
                                           placeholder="Ví dụ: Kế toán, Giáo viên, Sinh viên...">
                                    @error('occupation')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="education_level" class="block text-sm font-medium text-gray-700 mb-2">
                                        Trình độ học vấn
                                    </label>
                                    <select id="education_level"
                                            name="education_level"
                                            class="w-full px-4 py-3 border @error('education_level') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">Chọn trình độ</option>
                                        <option value="high_school" {{ old('education_level') === 'high_school' ? 'selected' : '' }}>Trung học phổ thông</option>
                                        <option value="college" {{ old('education_level') === 'college' ? 'selected' : '' }}>Cao đẳng</option>
                                        <option value="university" {{ old('education_level') === 'university' ? 'selected' : '' }}>Đại học</option>
                                        <option value="master" {{ old('education_level') === 'master' ? 'selected' : '' }}>Thạc sĩ</option>
                                        <option value="phd" {{ old('education_level') === 'phd' ? 'selected' : '' }}>Tiến sĩ</option>
                                        <option value="other" {{ old('education_level') === 'other' ? 'selected' : '' }}>Khác</option>
                                    </select>
                                    @error('education_level')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Learning Goals -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Mục tiêu học tập</h3>
                            
                            <div>
                                <label for="learning_goals" class="block text-sm font-medium text-gray-700 mb-2">
                                    Mục tiêu và kỳ vọng của bạn
                                </label>
                                <textarea id="learning_goals"
                                          name="learning_goals"
                                          rows="4"
                                          class="w-full px-4 py-3 border @error('learning_goals') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                          placeholder="Chia sẻ mục tiêu học tập và những gì bạn muốn đạt được...">{{ old('learning_goals') }}</textarea>
                                @error('learning_goals')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="interests" class="block text-sm font-medium text-gray-700 mb-2">
                                    Lĩnh vực quan tâm
                                </label>
                                <input type="text"
                                       id="interests"
                                       name="interests[]"
                                       value="{{ old('interests') ? implode(', ', old('interests')) : '' }}"
                                       class="w-full px-4 py-3 border @error('interests') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Ví dụ: Kế toán, Excel, Quản lý, Marketing... (cách nhau bằng dấu phẩy)">
                                @error('interests')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Avatar Upload -->
                        <div>
                            <label for="avatar" class="block text-sm font-medium text-gray-700 mb-2">
                                Ảnh đại diện
                            </label>
                            <input type="file"
                                   id="avatar"
                                   name="avatar"
                                   accept="image/*"
                                   class="w-full px-4 py-3 border @error('avatar') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p class="mt-1 text-sm text-gray-500">Chọn ảnh JPG, PNG hoặc GIF. Tối đa 2MB.</p>
                            @error('avatar')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-6">
                            <button type="submit" 
                                    class="w-full bg-blue-600 text-white font-semibold py-4 px-6 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                <i class="fas fa-user-plus mr-2"></i>
                                Đăng ký học viên
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Benefits Sidebar -->
                <div class="space-y-8">
                    <!-- Benefits -->
                    <div class="bg-white rounded-lg shadow-lg p-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-6">Quyền lợi học viên</h3>
                        <ul class="space-y-4">
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-500 mr-3 mt-1"></i>
                                <div>
                                    <h4 class="font-semibold">Học từ chuyên gia</h4>
                                    <p class="text-gray-600 text-sm">Được giảng dạy bởi các chuyên gia hàng đầu trong ngành</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-500 mr-3 mt-1"></i>
                                <div>
                                    <h4 class="font-semibold">Chứng chỉ hoàn thành</h4>
                                    <p class="text-gray-600 text-sm">Nhận chứng chỉ được công nhận sau khi hoàn thành khóa học</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-500 mr-3 mt-1"></i>
                                <div>
                                    <h4 class="font-semibold">Hỗ trợ 24/7</h4>
                                    <p class="text-gray-600 text-sm">Đội ngũ hỗ trợ luôn sẵn sàng giải đáp thắc mắc</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-500 mr-3 mt-1"></i>
                                <div>
                                    <h4 class="font-semibold">Cộng đồng học tập</h4>
                                    <p class="text-gray-600 text-sm">Kết nối với cộng đồng học viên năng động</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-500 mr-3 mt-1"></i>
                                <div>
                                    <h4 class="font-semibold">Tài liệu đầy đủ</h4>
                                    <p class="text-gray-600 text-sm">Tài liệu học tập phong phú và cập nhật</p>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <!-- Contact Info -->
                    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg p-8">
                        <h3 class="text-xl font-bold mb-4">Cần hỗ trợ?</h3>
                        <p class="mb-6 opacity-90">
                            Đội ngũ tư vấn của chúng tôi sẵn sàng hỗ trợ bạn trong quá trình đăng ký
                        </p>
                        <div class="space-y-3">
                            @if(isset($globalSettings))
                            @if($globalSettings->phone)
                            <div class="flex items-center">
                                <i class="fas fa-phone mr-3"></i>
                                <span>{{ $globalSettings->phone }}</span>
                            </div>
                            @endif
                            @if($globalSettings->email)
                            <div class="flex items-center">
                                <i class="fas fa-envelope mr-3"></i>
                                <span>{{ $globalSettings->email }}</span>
                            </div>
                            @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle interests input as tags
    const interestsInput = document.getElementById('interests');
    if (interestsInput) {
        interestsInput.addEventListener('blur', function() {
            // Convert comma-separated string to array for backend
            const value = this.value;
            if (value) {
                const interests = value.split(',').map(item => item.trim()).filter(item => item);
                // Create hidden inputs for each interest
                const form = this.closest('form');
                // Remove existing hidden interest inputs
                form.querySelectorAll('input[name="interests[]"][type="hidden"]').forEach(input => input.remove());
                
                // Add new hidden inputs
                interests.forEach(interest => {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'interests[]';
                    hiddenInput.value = interest;
                    form.appendChild(hiddenInput);
                });
            }
        });
    }

    // Avatar preview
    const avatarInput = document.getElementById('avatar');
    if (avatarInput) {
        avatarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Create preview if doesn't exist
                    let preview = document.getElementById('avatar-preview');
                    if (!preview) {
                        preview = document.createElement('img');
                        preview.id = 'avatar-preview';
                        preview.className = 'mt-2 w-20 h-20 object-cover rounded-full border-2 border-gray-300';
                        avatarInput.parentNode.appendChild(preview);
                    }
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
</script>
@endpush
