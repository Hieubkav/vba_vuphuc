@extends('layouts.shop')

@section('title', 'Test Lazy Loading')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Test Lazy Loading Components</h1>

    <!-- Interactive Demo -->
    <section class="mb-12">
        @include('components.lazy-loading-demo', ['title' => 'Interactive Timeout Demo'])
    </section>

    <!-- Test Hero Banner Style -->
    <section class="mb-12">
        <h2 class="text-2xl font-semibold mb-4">Hero Banner Style</h2>
        <div class="relative h-64 overflow-hidden rounded-lg">
            @storefrontImage([
                'src' => 'sliders/test-image.jpg',
                'type' => 'default',
                'options' => [
                    'alt' => 'Test Hero Image',
                    'class' => 'w-full h-full object-cover hero-image-main',
                    'priority' => false
                ]
            ])
        </div>
    </section>

    <!-- Test Course Images -->
    <section class="mb-12">
        <h2 class="text-2xl font-semibold mb-4">Course Images</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @for($i = 1; $i <= 6; $i++)
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="h-48">
                    @storefrontImage([
                        'src' => 'courses/course-' . $i . '.jpg',
                        'type' => 'course',
                        'options' => [
                            'alt' => 'Test Course ' . $i,
                            'class' => 'w-full h-full object-cover',
                            'priority' => $i <= 2
                        ]
                    ])
                </div>
                <div class="p-4">
                    <h3 class="font-semibold">Test Course {{ $i }}</h3>
                    <p class="text-gray-600 text-sm">{{ $i <= 2 ? 'Priority loaded' : 'Lazy loaded' }}</p>
                </div>
            </div>
            @endfor
        </div>
    </section>

    <!-- Test News Images -->
    <section class="mb-12">
        <h2 class="text-2xl font-semibold mb-4">News Images</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @for($i = 1; $i <= 8; $i++)
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="h-32">
                    @storefrontImage([
                        'src' => 'posts/news-' . $i . '.jpg',
                        'type' => 'news',
                        'options' => [
                            'alt' => 'Test News ' . $i,
                            'class' => 'w-full h-full object-cover',
                            'priority' => false
                        ]
                    ])
                </div>
                <div class="p-3">
                    <h4 class="font-medium text-sm">Test News {{ $i }}</h4>
                </div>
            </div>
            @endfor
        </div>
    </section>

    <!-- Test Partner Images -->
    <section class="mb-12">
        <h2 class="text-2xl font-semibold mb-4">Partner Images</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @for($i = 1; $i <= 12; $i++)
            <div class="bg-white rounded-lg shadow-sm p-4 flex items-center justify-center h-20">
                @storefrontImage([
                    'src' => 'partners/partner-' . $i . '.jpg',
                    'type' => 'partner',
                    'options' => [
                        'alt' => 'Test Partner ' . $i,
                        'class' => 'h-12 w-auto max-w-full object-contain',
                        'priority' => false
                    ]
                ])
            </div>
            @endfor
        </div>
    </section>

    <!-- Test Album Images -->
    <section class="mb-12">
        <h2 class="text-2xl font-semibold mb-4">Album Images</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @for($i = 1; $i <= 4; $i++)
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="h-64">
                    @storefrontImage([
                        'src' => 'albums/album-' . $i . '.jpg',
                        'type' => 'album',
                        'options' => [
                            'alt' => 'Test Album ' . $i,
                            'class' => 'w-full h-full object-cover',
                            'priority' => false
                        ]
                    ])
                </div>
                <div class="p-4">
                    <h3 class="font-semibold">Test Album {{ $i }}</h3>
                    <p class="text-gray-600 text-sm">Album description</p>
                </div>
            </div>
            @endfor
        </div>
    </section>

    <!-- Test Testimonial Images -->
    <section class="mb-12">
        <h2 class="text-2xl font-semibold mb-4">Testimonial Images</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @for($i = 1; $i <= 6; $i++)
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 rounded-full overflow-hidden mr-4">
                        @storefrontImage([
                            'src' => 'testimonials/user-' . $i . '.jpg',
                            'type' => 'testimonial',
                            'options' => [
                                'alt' => 'Test User ' . $i,
                                'class' => 'w-full h-full object-cover',
                                'priority' => false
                            ]
                        ])
                    </div>
                    <div>
                        <h4 class="font-semibold">Test User {{ $i }}</h4>
                        <p class="text-gray-600 text-sm">Customer</p>
                    </div>
                </div>
                <p class="text-gray-700">"This is a test testimonial for user {{ $i }}."</p>
            </div>
            @endfor
        </div>
    </section>

    <!-- Test Timeout Scenarios -->
    <section class="mb-12">
        <h2 class="text-2xl font-semibold mb-4">Timeout Test Cases</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Non-existent image -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="h-32">
                    @storefrontImage([
                        'src' => 'non-existent/image-404.jpg',
                        'type' => 'course',
                        'options' => [
                            'alt' => 'Non-existent Image',
                            'class' => 'w-full h-full object-cover',
                            'priority' => false
                        ]
                    ])
                </div>
                <div class="p-3">
                    <h4 class="font-medium text-sm">404 Image Test</h4>
                    <p class="text-xs text-gray-500">Should show fallback quickly</p>
                </div>
            </div>

            <!-- Slow loading image (simulated) -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="h-32">
                    @storefrontImage([
                        'src' => 'slow-loading/timeout-test.jpg',
                        'type' => 'news',
                        'options' => [
                            'alt' => 'Slow Loading Image',
                            'class' => 'w-full h-full object-cover',
                            'priority' => false
                        ]
                    ])
                </div>
                <div class="p-3">
                    <h4 class="font-medium text-sm">Timeout Test</h4>
                    <p class="text-xs text-gray-500">Should timeout after 2s</p>
                </div>
            </div>

            <!-- Empty src test -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="h-32">
                    @storefrontImage([
                        'src' => '',
                        'type' => 'partner',
                        'options' => [
                            'alt' => 'Empty Source',
                            'class' => 'w-full h-full object-cover',
                            'priority' => false
                        ]
                    ])
                </div>
                <div class="p-3">
                    <h4 class="font-medium text-sm">Empty Src Test</h4>
                    <p class="text-xs text-gray-500">Should show fallback immediately</p>
                </div>
            </div>

            <!-- Invalid URL test -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="h-32">
                    @storefrontImage([
                        'src' => 'invalid://url/test.jpg',
                        'type' => 'album',
                        'options' => [
                            'alt' => 'Invalid URL',
                            'class' => 'w-full h-full object-cover',
                            'priority' => false
                        ]
                    ])
                </div>
                <div class="p-3">
                    <h4 class="font-medium text-sm">Invalid URL Test</h4>
                    <p class="text-xs text-gray-500">Should fail gracefully</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Test Performance Info -->
    <section class="mb-12">
        <h2 class="text-2xl font-semibold mb-4">Performance Info</h2>
        <div class="bg-gray-100 rounded-lg p-6">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 text-center">
                <div>
                    <div class="text-2xl font-bold text-blue-600" id="totalImages">0</div>
                    <div class="text-sm text-gray-600">Total Images</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-green-600" id="loadedImages">0</div>
                    <div class="text-sm text-gray-600">Loaded Images</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-red-600" id="errorImages">0</div>
                    <div class="text-sm text-gray-600">Error Images</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-orange-600" id="loadingImages">0</div>
                    <div class="text-sm text-gray-600">Loading Images</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-600" id="pendingImages">0</div>
                    <div class="text-sm text-gray-600">Pending Images</div>
                </div>
            </div>

            <!-- Control buttons -->
            <div class="mt-6 flex justify-center space-x-4">
                <button onclick="refreshStats()" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    Refresh Stats
                </button>
                <button onclick="forceLoadAll()" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                    Force Load All
                </button>
                <button onclick="showLoadingStats()" class="px-4 py-2 bg-purple-500 text-white rounded hover:bg-purple-600">
                    Show Details
                </button>
            </div>
        </div>
    </section>
</div>

<script>
let statsInterval;

document.addEventListener('DOMContentLoaded', function() {
    // Initial stats
    refreshStats();

    // Auto refresh stats every 2 seconds
    statsInterval = setInterval(refreshStats, 2000);

    // Log lazy loading events
    console.log('Lazy Loading Test Page Loaded');
    console.log('Storefront Lazy Loader:', window.storefrontLazyLoader ? 'Available' : 'Not Available');

    // Log timeout settings
    if (window.storefrontLazyLoader) {
        console.log('Timeout Settings:', {
            fastFailTimeout: window.storefrontLazyLoader.fastFailTimeout,
            loadingTimeout: window.storefrontLazyLoader.loadingTimeout,
            maxRetries: window.storefrontLazyLoader.maxRetries
        });
    }
});

function refreshStats() {
    if (window.storefrontLazyLoader) {
        const stats = window.storefrontLazyLoader.getLoadingStats();
        document.getElementById('totalImages').textContent = stats.total;
        document.getElementById('loadedImages').textContent = stats.loaded;
        document.getElementById('errorImages').textContent = stats.error;
        document.getElementById('loadingImages').textContent = stats.loading;
        document.getElementById('pendingImages').textContent = stats.pending;
    } else {
        // Fallback counting
        const totalImages = document.querySelectorAll('img[data-src], img.lazy-loading').length;
        const loadedImages = document.querySelectorAll('img.lazy-loaded').length;
        const errorImages = document.querySelectorAll('img.lazy-error').length;
        const loadingImages = document.querySelectorAll('img.lazy-loading:not(.lazy-loaded):not(.lazy-error)').length;

        document.getElementById('totalImages').textContent = totalImages;
        document.getElementById('loadedImages').textContent = loadedImages;
        document.getElementById('errorImages').textContent = errorImages;
        document.getElementById('loadingImages').textContent = loadingImages;
        document.getElementById('pendingImages').textContent = totalImages - loadedImages - errorImages - loadingImages;
    }
}

function forceLoadAll() {
    if (window.storefrontLazyLoader) {
        const lazyImages = document.querySelectorAll('img.lazy-loading:not(.lazy-loaded)');
        console.log(`Force loading ${lazyImages.length} images...`);

        lazyImages.forEach(img => {
            window.storefrontLazyLoader.forceLoadImageWithTimeout(img, 1000); // 1s timeout for force load
        });

        setTimeout(refreshStats, 100);
    }
}

function showLoadingStats() {
    if (window.storefrontLazyLoader) {
        const stats = window.storefrontLazyLoader.getLoadingStats();

        console.group('📊 Lazy Loading Statistics');
        console.log('Total Images:', stats.total);
        console.log('✅ Loaded:', stats.loaded);
        console.log('❌ Error:', stats.error);
        console.log('⏳ Loading:', stats.loading);
        console.log('⏸️ Pending:', stats.pending);
        console.groupEnd();

        // Show detailed image status
        const images = document.querySelectorAll('img[data-src], img.lazy-loading');
        console.group('🖼️ Individual Image Status');
        images.forEach((img, index) => {
            const status = window.storefrontLazyLoader.getImageStatus(img);
            const src = img.dataset.src || img.src;
            console.log(`${index + 1}. ${status.toUpperCase()}: ${src}`);
        });
        console.groupEnd();

        alert(`Stats logged to console!\n\nTotal: ${stats.total}\nLoaded: ${stats.loaded}\nError: ${stats.error}\nLoading: ${stats.loading}\nPending: ${stats.pending}`);
    }
}

// Cleanup on page unload
window.addEventListener('beforeunload', function() {
    if (statsInterval) {
        clearInterval(statsInterval);
    }
});
</script>
@endsection
