@extends('layouts.shop')

@section('title', 'Test Simple Lazy Loading')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Test Simple Lazy Loading</h1>
    
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8">
        <h2 class="text-lg font-semibold text-blue-800 mb-2">✅ Cách tiếp cận đơn giản</h2>
        <p class="text-blue-700">Sử dụng native <code>loading="lazy"</code> thay vì JavaScript phức tạp</p>
    </div>

    <!-- Method 1: Component đơn giản -->
    <section class="mb-12">
        <h2 class="text-2xl font-semibold mb-4">1. Component đơn giản</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="h-48 bg-gray-100 rounded-lg overflow-hidden">
                <x-simple-lazy-image 
                    src="courses/course-1.jpg"
                    alt="Khóa học Excel"
                    class="w-full h-full object-cover"
                    fallback-type="course"
                />
            </div>
            
            <div class="h-48 bg-gray-100 rounded-lg overflow-hidden">
                <x-simple-lazy-image 
                    src="news/news-1.jpg"
                    alt="Tin tức mới"
                    class="w-full h-full object-cover"
                    fallback-type="news"
                />
            </div>
            
            <div class="h-48 bg-gray-100 rounded-lg overflow-hidden">
                <x-simple-lazy-image 
                    src="non-existent.jpg"
                    alt="Ảnh không tồn tại"
                    class="w-full h-full object-cover"
                    fallback-type="course"
                />
            </div>
        </div>
    </section>

    <!-- Method 2: Component với các loại fallback khác nhau -->
    <section class="mb-12">
        <h2 class="text-2xl font-semibold mb-4">2. Các loại fallback icons</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="h-48 bg-gray-100 rounded-lg overflow-hidden">
                <x-simple-lazy-image
                    src="courses/course-2.jpg"
                    alt="Khóa học Word"
                    class="w-full h-full object-cover"
                    fallback-type="course"
                />
            </div>

            <div class="h-48 bg-gray-100 rounded-lg overflow-hidden">
                <x-simple-lazy-image
                    src="partners/partner-1.jpg"
                    alt="Đối tác"
                    class="w-full h-full object-cover"
                    fallback-type="partner"
                />
            </div>

            <div class="h-48 bg-gray-100 rounded-lg overflow-hidden">
                <x-simple-lazy-image
                    src="missing-image.jpg"
                    alt="Ảnh lỗi"
                    class="w-full h-full object-cover"
                    fallback-type="default"
                />
            </div>
        </div>
    </section>

    <!-- Method 3: Priority loading (eager vs lazy) -->
    <section class="mb-12">
        <h2 class="text-2xl font-semibold mb-4">3. Priority loading</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="h-48 bg-gray-100 rounded-lg overflow-hidden">
                <x-simple-lazy-image
                    src="albums/album-1.jpg"
                    alt="Album ảnh (Eager loading)"
                    class="w-full h-full object-cover"
                    fallback-type="album"
                    :priority="true"
                />
                <p class="text-sm text-gray-600 mt-2">✅ Priority: true (loading="eager")</p>
            </div>

            <div class="h-48 bg-gray-100 rounded-lg overflow-hidden">
                <x-simple-lazy-image
                    src="testimonials/testimonial-1.jpg"
                    alt="Nhận xét (Lazy loading)"
                    class="w-full h-full object-cover"
                    fallback-type="testimonial"
                    :priority="false"
                />
                <p class="text-sm text-gray-600 mt-2">⏳ Priority: false (loading="lazy")</p>
            </div>
        </div>
    </section>

    <!-- Method 4: Native HTML (đơn giản nhất) -->
    <section class="mb-12">
        <h2 class="text-2xl font-semibold mb-4">4. Native HTML (đơn giản nhất)</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="h-48 bg-gray-100 rounded-lg overflow-hidden">
                <img src="{{ asset('storage/courses/course-3.jpg') }}" 
                     alt="Khóa học PowerPoint" 
                     class="w-full h-full object-cover"
                     loading="lazy"
                     decoding="async"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="w-full h-full bg-gray-50 flex items-center justify-center" style="display: none;">
                    <i class="fas fa-graduation-cap text-2xl text-gray-400"></i>
                </div>
            </div>
            
            <div class="h-48 bg-gray-100 rounded-lg overflow-hidden">
                <img src="{{ asset('storage/news/news-2.jpg') }}" 
                     alt="Tin tức công nghệ" 
                     class="w-full h-full object-cover"
                     loading="lazy"
                     decoding="async"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="w-full h-full bg-gray-50 flex items-center justify-center" style="display: none;">
                    <i class="fas fa-newspaper text-2xl text-gray-400"></i>
                </div>
            </div>
            
            <div class="h-48 bg-gray-100 rounded-lg overflow-hidden">
                <img src="{{ asset('storage/broken-image.jpg') }}" 
                     alt="Ảnh bị lỗi" 
                     class="w-full h-full object-cover"
                     loading="lazy"
                     decoding="async"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="w-full h-full bg-gray-50 flex items-center justify-center" style="display: none;">
                    <i class="fas fa-image text-2xl text-gray-400"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Performance comparison -->
    <section class="mb-12">
        <div class="bg-green-50 border border-green-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-green-800 mb-4">🚀 So sánh Performance</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-medium text-green-700 mb-2">❌ Cách cũ (phức tạp)</h4>
                    <ul class="text-sm text-green-600 space-y-1">
                        <li>• JavaScript Intersection Observer</li>
                        <li>• Blur placeholder generation</li>
                        <li>• Timeout management</li>
                        <li>• Complex service dependencies</li>
                        <li>• Khó debug và maintain</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-medium text-green-700 mb-2">✅ Cách mới (đơn giản)</h4>
                    <ul class="text-sm text-green-600 space-y-1">
                        <li>• Native <code>loading="lazy"</code></li>
                        <li>• Browser optimization</li>
                        <li>• Simple fallback UI</li>
                        <li>• Easy to understand</li>
                        <li>• Dễ maintain và debug</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
// Simple global image error handler
function handleSimpleImageError(img) {
    console.log('Image error:', img.src);
    
    // Ẩn ảnh lỗi
    img.style.display = 'none';
    
    // Hiển thị fallback
    const fallback = img.nextElementSibling;
    if (fallback && fallback.classList.contains('fallback-placeholder')) {
        fallback.style.display = 'flex';
        fallback.style.opacity = '0';
        setTimeout(() => {
            fallback.style.transition = 'opacity 0.3s ease';
            fallback.style.opacity = '1';
        }, 50);
    }
}
</script>
@endpush
