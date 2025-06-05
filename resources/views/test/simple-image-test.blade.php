@extends('layouts.shop')

@section('title', 'Simple Image Test')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Simple Image Optimization Test</h1>
            <p class="text-gray-600">Test các tính năng tối ưu hóa hình ảnh cơ bản</p>
        </div>

        <!-- Test Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            
            <!-- Test 1: Normal Image -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">1. Ảnh thường (không tối ưu)</h2>
                <img src="{{ asset('storage/courses/excel-vba-nang-cao.jpg') }}" 
                     alt="Khóa học VBA Excel" 
                     class="w-full h-48 object-cover rounded-lg">
                <p class="text-sm text-gray-500 mt-2">Ảnh gốc không qua tối ưu</p>
            </div>

            <!-- Test 2: Smart Image Component -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">2. Smart Image Component</h2>
                <x-smart-image 
                    src="courses/hoi-thao-xu-huong-2024.jpg"
                    alt="Hội thảo xu hướng 2024"
                    class="w-full h-48 object-cover rounded-lg"
                    :lazy="true"
                    :blur="true"
                />
                <p class="text-sm text-gray-500 mt-2">Với lazy loading và blur placeholder</p>
            </div>

            <!-- Test 3: Priority Image -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">3. Priority Image (Above Fold)</h2>
                <x-smart-image 
                    src="courses/ky-nang-giao-tiep.jpg"
                    alt="Kỹ năng giao tiếp"
                    class="w-full h-48 object-cover rounded-lg"
                    :priority="true"
                    :blur="false"
                />
                <p class="text-sm text-gray-500 mt-2">Load ngay lập tức, không lazy</p>
            </div>

            <!-- Test 4: Fallback Image -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">4. Fallback Image</h2>
                <x-smart-image 
                    src="non-existent-image.jpg"
                    alt="Ảnh không tồn tại"
                    class="w-full h-48 object-cover rounded-lg"
                    fallback-icon="fas fa-graduation-cap"
                    fallback-type="course"
                />
                <p class="text-sm text-gray-500 mt-2">Hiển thị fallback khi ảnh không tồn tại</p>
            </div>

            <!-- Test 5: Aspect Ratio -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">5. Aspect Ratio 16:9</h2>
                <x-smart-image 
                    src="carousel/01JTT6QDEN5A3D1TSDPFK0MTW1.jpg"
                    alt="Banner carousel"
                    class="w-full rounded-lg"
                    aspect-ratio="16:9"
                    :lazy="true"
                />
                <p class="text-sm text-gray-500 mt-2">Giữ tỷ lệ 16:9</p>
            </div>

            <!-- Test 6: Square Image -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">6. Square Image 1:1</h2>
                <x-smart-image 
                    src="partners/01JTT720VY70GVMGPCC26TN0KS.jpg"
                    alt="Partner logo"
                    class="w-full rounded-lg"
                    aspect-ratio="1:1"
                    :lazy="true"
                />
                <p class="text-sm text-gray-500 mt-2">Tỷ lệ vuông 1:1</p>
            </div>
        </div>

        <!-- Performance Info -->
        <div class="mt-12 bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">📊 Performance Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <strong>Images Loaded:</strong> <span id="images-loaded">0</span>
                </div>
                <div>
                    <strong>Images Failed:</strong> <span id="images-failed">0</span>
                </div>
                <div>
                    <strong>Connection Type:</strong> <span id="connection-type">Unknown</span>
                </div>
            </div>
        </div>

        <!-- Test Controls -->
        <div class="mt-8 bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">🎛️ Test Controls</h2>
            <div class="flex flex-wrap gap-4">
                <button onclick="refreshPage()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    🔄 Refresh Page
                </button>
                <button onclick="showStats()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                    📈 Show Stats
                </button>
                <button onclick="simulateSlowConnection()" class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700">
                    🐌 Simulate Slow Connection
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Monitor image loading
    let loadedCount = 0;
    let failedCount = 0;
    
    document.addEventListener('DOMContentLoaded', function() {
        // Update connection type
        updateConnectionType();
        
        // Monitor image events
        document.addEventListener('imageLoaded', function(e) {
            loadedCount++;
            document.getElementById('images-loaded').textContent = loadedCount;
            console.log('Image loaded:', e.detail.src);
        });
        
        document.addEventListener('imageError', function(e) {
            failedCount++;
            document.getElementById('images-failed').textContent = failedCount;
            console.log('Image error:', e.detail.src);
        });
    });
    
    function updateConnectionType() {
        if ('connection' in navigator) {
            document.getElementById('connection-type').textContent = 
                navigator.connection.effectiveType || 'Unknown';
        } else {
            document.getElementById('connection-type').textContent = 'Not Available';
        }
    }
    
    function refreshPage() {
        window.location.reload();
    }
    
    function showStats() {
        alert(`Loaded: ${loadedCount}, Failed: ${failedCount}`);
    }
    
    function simulateSlowConnection() {
        alert('Sử dụng Chrome DevTools > Network tab > Throttling để simulate slow connection');
    }
</script>
@endpush
@endsection
