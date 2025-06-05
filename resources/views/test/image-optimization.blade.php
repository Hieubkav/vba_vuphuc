@extends('layouts.shop')

@section('title', 'Test Image Optimization & Lazy Loading')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Test Header -->
    <div class="bg-white shadow-sm py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900">Test Image Optimization & Lazy Loading</h1>
            <p class="text-gray-600 mt-2">Kiểm tra các tính năng tối ưu hóa hình ảnh và lazy loading</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-16">
        
        <!-- Smart Image Component Test -->
        <section>
            <h2 class="text-2xl font-bold text-gray-900 mb-6">1. Smart Image Component</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <!-- Priority Image (Above fold) -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <h3 class="text-lg font-semibold p-4 border-b">Priority Image (Above Fold)</h3>
                    <div class="p-4">
                        <x-smart-image
                            src="courses/excel-vba-nang-cao.jpg"
                            alt="Khóa học VBA Excel cơ bản"
                            class="w-full rounded-lg"
                            aspect-ratio="16:9"
                            :priority="true"
                            :blur="false"
                        />
                    </div>
                </div>

                <!-- Lazy Image with Blur -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <h3 class="text-lg font-semibold p-4 border-b">Lazy Image with Blur</h3>
                    <div class="p-4">
                        <x-smart-image
                            src="courses/hoi-thao-xu-huong-2024.jpg"
                            alt="Khóa học VBA Excel nâng cao"
                            class="w-full rounded-lg"
                            aspect-ratio="4:3"
                            :lazy="true"
                            :blur="true"
                        />
                    </div>
                </div>

                <!-- Responsive Image -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <h3 class="text-lg font-semibold p-4 border-b">Responsive Image</h3>
                    <div class="p-4">
                        <x-smart-image
                            src="courses/ky-nang-giao-tiep.jpg"
                            alt="Khóa học VBA Excel thực hành"
                            class="w-full rounded-lg"
                            aspect-ratio="1:1"
                            :responsive="true"
                            :sizes="[320, 480, 768, 1024]"
                        />
                    </div>
                </div>

                <!-- Fallback Image (Non-existent) -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <h3 class="text-lg font-semibold p-4 border-b">Fallback Image</h3>
                    <div class="p-4">
                        <x-smart-image 
                            src="non-existent-image.jpg"
                            alt="Ảnh không tồn tại"
                            class="w-full rounded-lg"
                            aspect-ratio="16:9"
                            fallback-icon="fas fa-graduation-cap"
                            fallback-type="course"
                        />
                    </div>
                </div>

                <!-- Square Image -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <h3 class="text-lg font-semibold p-4 border-b">Square Image</h3>
                    <div class="p-4">
                        <x-smart-image 
                            src="instructors/instructor-1.jpg"
                            alt="Giảng viên VBA"
                            class="w-full rounded-lg"
                            aspect-ratio="1:1"
                            :enable-skeleton="true"
                            skeleton-height="200px"
                        />
                    </div>
                </div>

                <!-- Custom Skeleton -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <h3 class="text-lg font-semibold p-4 border-b">Custom Skeleton</h3>
                    <div class="p-4">
                        <x-smart-image 
                            src="posts/post-1.jpg"
                            alt="Bài viết VBA"
                            class="w-full rounded-lg"
                            aspect-ratio="3:2"
                            :enable-skeleton="true"
                            skeleton-height="150px"
                        />
                    </div>
                </div>
            </div>
        </section>

        <!-- Progressive Gallery Test -->
        <section>
            <h2 class="text-2xl font-bold text-gray-900 mb-6">2. Progressive Gallery</h2>
            <div class="bg-white rounded-lg shadow-md p-6">
                @php
                    $galleryImages = [
                        ['path' => 'courses/course-1.jpg', 'alt' => 'Khóa học VBA 1'],
                        ['path' => 'courses/course-2.jpg', 'alt' => 'Khóa học VBA 2'],
                        ['path' => 'courses/course-3.jpg', 'alt' => 'Khóa học VBA 3'],
                        ['path' => 'instructors/instructor-1.jpg', 'alt' => 'Giảng viên 1'],
                        ['path' => 'instructors/instructor-2.jpg', 'alt' => 'Giảng viên 2'],
                        ['path' => 'posts/post-1.jpg', 'alt' => 'Bài viết 1'],
                        ['path' => 'posts/post-2.jpg', 'alt' => 'Bài viết 2'],
                        ['path' => 'posts/post-3.jpg', 'alt' => 'Bài viết 3'],
                        ['path' => 'albums/album-1.jpg', 'alt' => 'Album 1'],
                        ['path' => 'albums/album-2.jpg', 'alt' => 'Album 2'],
                    ];
                @endphp
                
                <x-progressive-gallery 
                    :images="$galleryImages"
                    :batch-size="4"
                    :enable-thumbnails="true"
                    aspect-ratio="4:3"
                    columns="grid-cols-2 md:grid-cols-3 lg:grid-cols-4"
                    gap="gap-4"
                    :lightbox="true"
                    :enable-infinite-scroll="true"
                />
            </div>
        </section>

        <!-- Performance Stats -->
        <section>
            <h2 class="text-2xl font-bold text-gray-900 mb-6">3. Performance Stats</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <!-- Loading Stats -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Loading Statistics</h3>
                    <div id="loading-stats" class="space-y-2 text-sm text-gray-600">
                        <div>Images Loaded: <span id="images-loaded">0</span></div>
                        <div>Images Failed: <span id="images-failed">0</span></div>
                        <div>Connection Type: <span id="connection-type">Unknown</span></div>
                        <div>Page Load Time: <span id="page-load-time">Calculating...</span></div>
                    </div>
                </div>

                <!-- Optimization Features -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Optimization Features</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span>WebP Format Support</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span>Responsive Images</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span>Lazy Loading</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span>Blur Placeholders</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span>Progressive Loading</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span>Adaptive Quality</span>
                        </div>
                    </div>
                </div>

                <!-- Browser Support -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Browser Support</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span>IntersectionObserver: <span id="intersection-support">Checking...</span></span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span>WebP Support: <span id="webp-support">Checking...</span></span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span>Connection API: <span id="connection-support">Checking...</span></span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span>Aspect Ratio: <span id="aspect-ratio-support">Checking...</span></span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Test Controls -->
        <section>
            <h2 class="text-2xl font-bold text-gray-900 mb-6">4. Test Controls</h2>
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <button onclick="refreshLazyLoader()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-refresh mr-2"></i>
                        Refresh Lazy Loader
                    </button>
                    
                    <button onclick="clearImageCache()" class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition-colors">
                        <i class="fas fa-trash mr-2"></i>
                        Clear Cache
                    </button>
                    
                    <button onclick="showStats()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-chart-bar mr-2"></i>
                        Show Stats
                    </button>
                    
                    <button onclick="simulateSlowConnection()" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                        <i class="fas fa-wifi mr-2"></i>
                        Simulate Slow Connection
                    </button>
                </div>
            </div>
        </section>
    </div>
</div>

@push('scripts')
<script>
    // Performance monitoring
    const startTime = performance.now();
    
    document.addEventListener('DOMContentLoaded', function() {
        // Update page load time
        const loadTime = performance.now() - startTime;
        document.getElementById('page-load-time').textContent = Math.round(loadTime) + 'ms';
        
        // Check browser support
        checkBrowserSupport();
        
        // Monitor image loading
        monitorImageLoading();
        
        // Update connection type
        updateConnectionType();
    });
    
    function checkBrowserSupport() {
        // IntersectionObserver
        document.getElementById('intersection-support').textContent = 
            'IntersectionObserver' in window ? 'Supported' : 'Not Supported';
        
        // WebP Support
        const webp = new Image();
        webp.onload = webp.onerror = function () {
            document.getElementById('webp-support').textContent = 
                (webp.height == 2) ? 'Supported' : 'Not Supported';
        };
        webp.src = 'data:image/webp;base64,UklGRjoAAABXRUJQVlA4IC4AAACyAgCdASoCAAIALmk0mk0iIiIiIgBoSygABc6WWgAA/veff/0PP8bA//LwYAAA';
        
        // Connection API
        document.getElementById('connection-support').textContent = 
            'connection' in navigator ? 'Supported' : 'Not Supported';
        
        // Aspect Ratio
        document.getElementById('aspect-ratio-support').textContent = 
            CSS.supports('aspect-ratio', '1') ? 'Supported' : 'Not Supported';
    }
    
    function monitorImageLoading() {
        let loadedCount = 0;
        let failedCount = 0;
        
        document.addEventListener('imageLoaded', function() {
            loadedCount++;
            document.getElementById('images-loaded').textContent = loadedCount;
        });
        
        document.addEventListener('imageError', function() {
            failedCount++;
            document.getElementById('images-failed').textContent = failedCount;
        });
    }
    
    function updateConnectionType() {
        if ('connection' in navigator) {
            document.getElementById('connection-type').textContent = 
                navigator.connection.effectiveType || 'Unknown';
        } else {
            document.getElementById('connection-type').textContent = 'Not Available';
        }
    }
    
    function refreshLazyLoader() {
        if (window.smartLazyLoader) {
            window.smartLazyLoader.refresh();
            alert('Lazy loader refreshed!');
        }
    }
    
    function clearImageCache() {
        if ('caches' in window) {
            caches.keys().then(function(names) {
                names.forEach(function(name) {
                    caches.delete(name);
                });
            });
        }
        alert('Cache cleared!');
    }
    
    function showStats() {
        if (window.smartLazyLoader) {
            const stats = window.smartLazyLoader.getStats();
            alert(`Loaded: ${stats.loaded}, Failed: ${stats.failed}, Connection: ${stats.connectionType}`);
        }
    }
    
    function simulateSlowConnection() {
        // This would typically be done through browser dev tools
        alert('Use browser dev tools to simulate slow connection (Network tab > Throttling)');
    }
</script>
@endpush
@endsection
