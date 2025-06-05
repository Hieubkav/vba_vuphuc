@extends('layouts.shop')

@section('title', $category->seo_title ?: 'Khóa học ' . $category->name . ' - VBA Vũ Phúc')
@section('description', $category->seo_description ?: 'Khám phá các khóa học ' . $category->name . ' chất lượng cao tại VBA Vũ Phúc.')

@if($category->og_image_link)
@section('og_image', asset('storage/' . $category->og_image_link))
@elseif($category->image)
@section('og_image', asset('storage/' . $category->image))
@endif

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Category Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-16">
        <div class="container mx-auto px-4">
            <!-- Breadcrumb -->
            <nav class="text-sm mb-6 opacity-80">
                <ol class="flex items-center space-x-2">
                    <li><a href="{{ route('storeFront') }}" class="hover:text-blue-200">Trang chủ</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li><a href="{{ route('courses.index') }}" class="hover:text-blue-200">Khóa học</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li>{{ $category->name }}</li>
                </ol>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold mb-4">
                        Khóa học {{ $category->name }}
                    </h1>
                    
                    @if($category->description)
                    <p class="text-xl opacity-90 mb-6">
                        {{ $category->description }}
                    </p>
                    @endif

                    <div class="flex items-center space-x-6">
                        <div class="text-center">
                            <div class="text-2xl font-bold">{{ $courses->total() }}</div>
                            <div class="text-sm opacity-80">Khóa học</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold">{{ $category->getCoursesCount() }}</div>
                            <div class="text-sm opacity-80">Đang hoạt động</div>
                        </div>
                    </div>
                </div>

                @if($category->image)
                <div class="text-center lg:text-right">
                    <img src="{{ asset('storage/' . $category->image) }}" 
                         alt="{{ $category->name }}"
                         class="w-64 h-64 object-cover rounded-full mx-auto lg:mx-0 shadow-2xl">
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Courses Grid -->
    <div class="container mx-auto px-4 py-12">
        @if($courses->count() > 0)
            <!-- Sort and Filter Options -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">
                        {{ $courses->total() }} khóa học được tìm thấy
                    </h2>
                    <p class="text-gray-600">Trong danh mục "{{ $category->name }}"</p>
                </div>

                <!-- Sort Dropdown -->
                <div class="relative">
                    <select id="sortSelect" class="appearance-none bg-white border border-gray-300 rounded-lg px-4 py-2 pr-8 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="order">Sắp xếp mặc định</option>
                        <option value="newest">Mới nhất</option>
                        <option value="price_asc">Giá thấp đến cao</option>
                        <option value="price_desc">Giá cao đến thấp</option>
                        <option value="popular">Phổ biến nhất</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Courses Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-12">
                @foreach($courses as $course)
                <livewire:course-card :course="$course" :key="'course-'.$course->id" />
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="flex justify-center">
                {{ $courses->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="max-w-md mx-auto">
                    <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-graduation-cap text-3xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">
                        Chưa có khóa học nào
                    </h3>
                    <p class="text-gray-600 mb-6">
                        Danh mục "{{ $category->name }}" hiện chưa có khóa học nào. Vui lòng quay lại sau hoặc khám phá các danh mục khác.
                    </p>
                    <a href="{{ route('courses.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Xem tất cả khóa học
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Related Categories -->
    @if(isset($courseCategories) && $courseCategories->count() > 1)
    <div class="bg-white py-16">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl font-bold text-gray-900 text-center mb-8">
                Khám phá danh mục khác
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($courseCategories->where('id', '!=', $category->id)->take(8) as $relatedCategory)
                <a href="{{ route('courses.category', $relatedCategory->slug) }}" 
                   class="group text-center p-6 bg-gray-50 rounded-lg hover:bg-blue-50 transition-colors">
                    @if($relatedCategory->image)
                    <img src="{{ asset('storage/' . $relatedCategory->image) }}" 
                         alt="{{ $relatedCategory->name }}"
                         class="w-16 h-16 object-cover rounded-full mx-auto mb-4 group-hover:scale-110 transition-transform">
                    @else
                    <div class="w-16 h-16 bg-blue-100 rounded-full mx-auto mb-4 flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                        <i class="fas fa-folder text-blue-600 text-xl"></i>
                    </div>
                    @endif
                    <h3 class="font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">
                        {{ $relatedCategory->name }}
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">
                        {{ $relatedCategory->courses_count ?? 0 }} khóa học
                    </p>
                </a>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Call to Action -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-16">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-4">
                Sẵn sàng bắt đầu học?
            </h2>
            <p class="text-xl opacity-90 mb-8 max-w-2xl mx-auto">
                Tham gia cùng hàng nghìn học viên đã tin tưởng và đạt được thành công với các khóa học {{ $category->name }} của chúng tôi
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('students.register') }}" 
                   class="inline-flex items-center px-8 py-4 bg-white text-blue-600 font-semibold rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="fas fa-user-plus mr-2"></i>
                    Đăng ký học viên
                </a>
                <a href="{{ route('courses.index') }}" 
                   class="inline-flex items-center px-8 py-4 border-2 border-white text-white font-semibold rounded-lg hover:bg-white hover:text-blue-600 transition-colors">
                    <i class="fas fa-search mr-2"></i>
                    Khám phá thêm
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sort functionality
    const sortSelect = document.getElementById('sortSelect');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            const currentUrl = new URL(window.location);
            currentUrl.searchParams.set('sort', this.value);
            window.location.href = currentUrl.toString();
        });

        // Set current sort value
        const urlParams = new URLSearchParams(window.location.search);
        const currentSort = urlParams.get('sort');
        if (currentSort) {
            sortSelect.value = currentSort;
        }
    }
});
</script>
@endpush
