@extends('layouts.shop')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-center mb-8">Test Partners Component</h1>
    
    <!-- Test 1: Component với dữ liệu từ ViewServiceProvider -->
    <div class="mb-12">
        <h2 class="text-xl font-semibold mb-4">1. Component với dữ liệu từ ViewServiceProvider</h2>
        <div class="bg-white rounded-lg border p-6">
            @include('components.storefront.partners')
        </div>
    </div>
    
    <!-- Test 2: Component với dữ liệu rỗng (Fallback UI) -->
    <div class="mb-12">
        <h2 class="text-xl font-semibold mb-4">2. Component với dữ liệu rỗng (Fallback UI)</h2>
        @php
            $partners = collect(); // Empty collection để test fallback
        @endphp
        @include('components.storefront.partners')
    </div>
    
    <!-- Test 3: Component với dữ liệu demo -->
    <div class="mb-12">
        <h2 class="text-xl font-semibold mb-4">3. Component với dữ liệu demo</h2>
        @php
            $partners = collect([
                (object) [
                    'id' => 1,
                    'name' => 'Google',
                    'logo_link' => 'partners/google-logo.png',
                    'website_link' => 'https://google.com',
                    'order' => 1
                ],
                (object) [
                    'id' => 2,
                    'name' => 'Microsoft',
                    'logo_link' => 'partners/microsoft-logo.png',
                    'website_link' => 'https://microsoft.com',
                    'order' => 2
                ],
                (object) [
                    'id' => 3,
                    'name' => 'Apple',
                    'logo_link' => 'partners/apple-logo.png',
                    'website_link' => 'https://apple.com',
                    'order' => 3
                ],
                (object) [
                    'id' => 4,
                    'name' => 'Amazon',
                    'logo_link' => 'partners/amazon-logo.png',
                    'website_link' => 'https://amazon.com',
                    'order' => 4
                ],
                (object) [
                    'id' => 5,
                    'name' => 'Facebook',
                    'logo_link' => 'partners/facebook-logo.png',
                    'website_link' => 'https://facebook.com',
                    'order' => 5
                ],
                (object) [
                    'id' => 6,
                    'name' => 'Netflix',
                    'logo_link' => 'partners/netflix-logo.png',
                    'website_link' => 'https://netflix.com',
                    'order' => 6
                ],
                (object) [
                    'id' => 7,
                    'name' => 'Tesla',
                    'logo_link' => 'partners/tesla-logo.png',
                    'website_link' => 'https://tesla.com',
                    'order' => 7
                ],
                (object) [
                    'id' => 8,
                    'name' => 'Samsung',
                    'logo_link' => 'partners/samsung-logo.png',
                    'website_link' => 'https://samsung.com',
                    'order' => 8
                ]
            ]);
        @endphp
        @include('components.storefront.partners')
    </div>
    
    <!-- Test 4: Test ảnh bị lỗi -->
    <div class="mb-12">
        <h2 class="text-xl font-semibold mb-4">4. Test ảnh bị lỗi (Image Error Fallback)</h2>
        @php
            $partners = collect([
                (object) [
                    'id' => 1,
                    'name' => 'Partner với ảnh bình thường',
                    'logo_link' => null, // Không có ảnh
                    'website_link' => '#',
                    'order' => 1
                ],
                (object) [
                    'id' => 2,
                    'name' => 'Partner với ảnh bị lỗi',
                    'logo_link' => 'non-existent-image.jpg', // Ảnh không tồn tại
                    'website_link' => '#',
                    'order' => 2
                ],
                (object) [
                    'id' => 3,
                    'name' => 'Partner với URL sai',
                    'logo_link' => 'invalid/path/image.jpg', // URL sai
                    'website_link' => '#',
                    'order' => 3
                ]
            ]);
        @endphp
        @include('components.storefront.partners')
    </div>
    
    <!-- Test 5: Test responsive -->
    <div class="mb-12">
        <h2 class="text-xl font-semibold mb-4">5. Test Responsive Design</h2>
        <p class="text-gray-600 mb-4">Thay đổi kích thước màn hình để xem Swiper responsive</p>
        <div class="bg-red-50 p-4 rounded-lg">
            <p class="text-sm text-red-700">
                <strong>Hướng dẫn test:</strong><br>
                1. Mở Developer Tools (F12)<br>
                2. Chuyển sang chế độ responsive<br>
                3. Thay đổi kích thước để xem Swiper trên mobile/tablet/desktop<br>
                4. Kiểm tra autoplay và smooth transitions
            </p>
        </div>
    </div>
    
    <!-- Test 6: Performance test -->
    <div class="mb-12">
        <h2 class="text-xl font-semibold mb-4">6. Performance Test</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white p-4 rounded-lg border">
                <h3 class="font-semibold mb-2">Swiper Features</h3>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>✅ Auto-play với pause on hover</li>
                    <li>✅ Smooth transitions (800ms)</li>
                    <li>✅ Loop infinite</li>
                    <li>✅ Responsive breakpoints</li>
                    <li>✅ Dynamic pagination</li>
                    <li>✅ Navigation buttons</li>
                </ul>
            </div>
            <div class="bg-white p-4 rounded-lg border">
                <h3 class="font-semibold mb-2">Fallback Features</h3>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>✅ Image error handling</li>
                    <li>✅ Empty state UI</li>
                    <li>✅ Shimmer animations</li>
                    <li>✅ Font Awesome icons</li>
                    <li>✅ Responsive design</li>
                    <li>✅ Smooth hover effects</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
// Test console logging
console.log('Partners test page loaded');

// Monitor Swiper initialization
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        const swipers = document.querySelectorAll('.partners-swiper');
        console.log(`Found ${swipers.length} partner swipers`);
        
        swipers.forEach((swiper, index) => {
            console.log(`Swiper ${index + 1}:`, swiper);
        });
    }, 1000);
});
</script>
@endsection
