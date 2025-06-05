{{--
    Lazy Loading Demo Component
    Hiển thị các trường hợp timeout và fallback
--}}

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h3 class="text-lg font-semibold mb-4 text-gray-900">{{ $title ?? 'Lazy Loading Demo' }}</h3>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Test Case 1: Normal Image (should load) -->
        <div class="demo-case">
            <div class="h-32 bg-gray-50 rounded-lg overflow-hidden relative">
                @storefrontImage([
                    'src' => 'demo/sample-image.jpg',
                    'type' => 'course',
                    'options' => [
                        'alt' => 'Sample Image',
                        'class' => 'w-full h-full object-cover',
                        'priority' => false
                    ]
                ])
                <div class="absolute top-2 left-2 bg-green-500 text-white text-xs px-2 py-1 rounded">
                    Normal
                </div>
            </div>
            <div class="mt-2">
                <h4 class="font-medium text-sm">Normal Image</h4>
                <p class="text-xs text-gray-500">Should load normally</p>
            </div>
        </div>

        <!-- Test Case 2: 404 Image (fast fail) -->
        <div class="demo-case">
            <div class="h-32 bg-gray-50 rounded-lg overflow-hidden relative">
                @storefrontImage([
                    'src' => 'non-existent/404-image.jpg',
                    'type' => 'news',
                    'options' => [
                        'alt' => '404 Image',
                        'class' => 'w-full h-full object-cover',
                        'priority' => false
                    ]
                ])
                <div class="absolute top-2 left-2 bg-red-500 text-white text-xs px-2 py-1 rounded">
                    404
                </div>
            </div>
            <div class="mt-2">
                <h4 class="font-medium text-sm">404 Image</h4>
                <p class="text-xs text-gray-500">Fast fail (2s timeout)</p>
            </div>
        </div>

        <!-- Test Case 3: Empty Source -->
        <div class="demo-case">
            <div class="h-32 bg-gray-50 rounded-lg overflow-hidden relative">
                @storefrontImage([
                    'src' => '',
                    'type' => 'partner',
                    'options' => [
                        'alt' => 'Empty Source',
                        'class' => 'w-full h-full object-cover',
                        'priority' => false
                    ]
                ])
                <div class="absolute top-2 left-2 bg-orange-500 text-white text-xs px-2 py-1 rounded">
                    Empty
                </div>
            </div>
            <div class="mt-2">
                <h4 class="font-medium text-sm">Empty Source</h4>
                <p class="text-xs text-gray-500">Immediate fallback</p>
            </div>
        </div>

        <!-- Test Case 4: Invalid URL -->
        <div class="demo-case">
            <div class="h-32 bg-gray-50 rounded-lg overflow-hidden relative">
                @storefrontImage([
                    'src' => 'invalid://protocol/image.jpg',
                    'type' => 'album',
                    'options' => [
                        'alt' => 'Invalid URL',
                        'class' => 'w-full h-full object-cover',
                        'priority' => false
                    ]
                ])
                <div class="absolute top-2 left-2 bg-purple-500 text-white text-xs px-2 py-1 rounded">
                    Invalid
                </div>
            </div>
            <div class="mt-2">
                <h4 class="font-medium text-sm">Invalid URL</h4>
                <p class="text-xs text-gray-500">Protocol error</p>
            </div>
        </div>
    </div>

    <!-- Real-time Status Display -->
    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
        <h4 class="font-medium text-sm mb-3">Real-time Status</h4>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
            <div>
                <div class="text-lg font-bold text-blue-600" id="demo-total">0</div>
                <div class="text-xs text-gray-600">Total</div>
            </div>
            <div>
                <div class="text-lg font-bold text-green-600" id="demo-loaded">0</div>
                <div class="text-xs text-gray-600">Loaded</div>
            </div>
            <div>
                <div class="text-lg font-bold text-red-600" id="demo-error">0</div>
                <div class="text-xs text-gray-600">Error</div>
            </div>
            <div>
                <div class="text-lg font-bold text-orange-600" id="demo-loading">0</div>
                <div class="text-xs text-gray-600">Loading</div>
            </div>
        </div>
    </div>

    <!-- Control Buttons -->
    <div class="mt-4 flex flex-wrap gap-2">
        <button onclick="resetDemo()" class="px-3 py-1 bg-blue-500 text-white text-sm rounded hover:bg-blue-600">
            Reset Demo
        </button>
        <button onclick="forceLoadDemo()" class="px-3 py-1 bg-green-500 text-white text-sm rounded hover:bg-green-600">
            Force Load All
        </button>
        <button onclick="showDemoStats()" class="px-3 py-1 bg-purple-500 text-white text-sm rounded hover:bg-purple-600">
            Show Stats
        </button>
    </div>

    <!-- Timeline Log -->
    <div class="mt-4">
        <h4 class="font-medium text-sm mb-2">Event Timeline</h4>
        <div id="demo-timeline" class="bg-gray-900 text-green-400 text-xs p-3 rounded font-mono h-32 overflow-y-auto">
            <div>Demo initialized...</div>
        </div>
    </div>
</div>

<script>
let demoStatsInterval;
let demoStartTime = Date.now();

function initDemo() {
    demoStartTime = Date.now();
    logToTimeline('Demo started');
    
    // Start monitoring
    if (demoStatsInterval) clearInterval(demoStatsInterval);
    demoStatsInterval = setInterval(updateDemoStats, 500);
    
    // Monitor image events
    const demoImages = document.querySelectorAll('.demo-case img');
    demoImages.forEach((img, index) => {
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                    const classList = img.classList;
                    const elapsed = ((Date.now() - demoStartTime) / 1000).toFixed(1);
                    
                    if (classList.contains('lazy-loaded')) {
                        logToTimeline(`[${elapsed}s] Image ${index + 1}: LOADED`);
                    } else if (classList.contains('lazy-error')) {
                        logToTimeline(`[${elapsed}s] Image ${index + 1}: ERROR`);
                    }
                }
            });
        });
        
        observer.observe(img, { attributes: true });
    });
}

function updateDemoStats() {
    const demoContainer = document.querySelector('.demo-case').closest('.bg-white');
    const images = demoContainer.querySelectorAll('img[data-src], img.lazy-loading');
    
    let total = images.length;
    let loaded = 0;
    let error = 0;
    let loading = 0;
    
    images.forEach(img => {
        if (img.classList.contains('lazy-loaded')) {
            loaded++;
        } else if (img.classList.contains('lazy-error')) {
            error++;
        } else if (img.classList.contains('lazy-loading')) {
            loading++;
        }
    });
    
    document.getElementById('demo-total').textContent = total;
    document.getElementById('demo-loaded').textContent = loaded;
    document.getElementById('demo-error').textContent = error;
    document.getElementById('demo-loading').textContent = loading;
}

function logToTimeline(message) {
    const timeline = document.getElementById('demo-timeline');
    const div = document.createElement('div');
    div.textContent = message;
    timeline.appendChild(div);
    timeline.scrollTop = timeline.scrollHeight;
}

function resetDemo() {
    logToTimeline('Resetting demo...');
    location.reload();
}

function forceLoadDemo() {
    if (window.storefrontLazyLoader) {
        const demoImages = document.querySelectorAll('.demo-case img.lazy-loading:not(.lazy-loaded)');
        logToTimeline(`Force loading ${demoImages.length} images...`);
        
        demoImages.forEach((img, index) => {
            setTimeout(() => {
                window.storefrontLazyLoader.forceLoadImageWithTimeout(img, 1000);
                logToTimeline(`Force loading image ${index + 1}`);
            }, index * 200);
        });
    }
}

function showDemoStats() {
    if (window.storefrontLazyLoader) {
        const stats = window.storefrontLazyLoader.getLoadingStats();
        logToTimeline(`Stats: ${stats.loaded} loaded, ${stats.error} error, ${stats.loading} loading`);
        console.log('Demo Stats:', stats);
    }
}

// Initialize when component loads
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(initDemo, 100);
});
</script>

<style>
.demo-case {
    transition: transform 0.2s ease;
}

.demo-case:hover {
    transform: translateY(-2px);
}

#demo-timeline::-webkit-scrollbar {
    width: 4px;
}

#demo-timeline::-webkit-scrollbar-track {
    background: #374151;
}

#demo-timeline::-webkit-scrollbar-thumb {
    background: #10b981;
    border-radius: 2px;
}
</style>
