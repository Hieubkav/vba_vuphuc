
@php
    // Lấy dữ liệu albums từ ViewServiceProvider hoặc tạo collection rỗng
    $albums = $albums ?? collect();
    $isLoading = !isset($albums) || $albums === null;
@endphp

@if($albums->isNotEmpty())
<div class="max-w-6xl mx-auto px-3 sm:px-4 lg:px-6">
    <!-- Timeline Container -->
    <div class="relative max-w-5xl mx-auto">
        <!-- Timeline Line - Desktop -->
        <div class="absolute left-1/2 transform -translate-x-1/2 w-px bg-red-200 h-full hidden lg:block"></div>

        <!-- Timeline Line - Mobile -->
        <div class="absolute left-4 top-0 w-px bg-red-200 h-full lg:hidden"></div>

        <!-- Timeline Items -->
        <div class="space-y-6 sm:space-y-8 lg:space-y-10">
            @foreach($albums as $index => $album)
            @php
                $isEven = $index % 2 === 0;
                $pdfUrl = $album->pdf_file ? asset('storage/' . $album->pdf_file) : null;
            @endphp

            <!-- Timeline Item - Responsive Layout -->
            <div class="relative grid lg:grid-cols-2 gap-4 sm:gap-6 lg:gap-8 items-center timeline-item pl-8 sm:pl-10 lg:pl-0">

                <!-- Timeline Dot - Desktop -->
                <div class="absolute left-1/2 transform -translate-x-1/2 w-2.5 h-2.5 bg-red-500 rounded-full z-20 hidden lg:block timeline-dot"></div>

                <!-- Timeline Dot - Mobile -->
                <div class="absolute left-3 transform -translate-x-1/2 w-2 h-2 bg-red-500 rounded-full z-20 lg:hidden timeline-dot"></div>

                <!-- Album Content Side -->
                <div class="{{ $isEven ? 'lg:order-1' : 'lg:order-2' }}">
                    <div class="timeline-content bg-white border border-red-100 hover:border-red-200 hover:shadow-md transition-all duration-300 group p-3 sm:p-4 lg:p-5 rounded-lg">

                        <!-- Date Badge -->
                        <div class="mb-2 flex items-center gap-2 flex-wrap">
                            <span class="inline-block px-2 py-1 bg-red-50 text-red-600 text-xs font-medium rounded">
                                @php
                                    if ($album->published_date) {
                                        $months = [
                                            1 => 'Tháng 1', 2 => 'Tháng 2', 3 => 'Tháng 3', 4 => 'Tháng 4',
                                            5 => 'Tháng 5', 6 => 'Tháng 6', 7 => 'Tháng 7', 8 => 'Tháng 8',
                                            9 => 'Tháng 9', 10 => 'Tháng 10', 11 => 'Tháng 11', 12 => 'Tháng 12'
                                        ];
                                        $month = $months[$album->published_date->month];
                                        $year = $album->published_date->year;
                                        echo "$month $year";
                                    } else {
                                        echo 'Chưa xuất bản';
                                    }
                                @endphp
                            </span>
                            @if($album->total_pages)
                                <span class="inline-block px-2 py-1 bg-gray-100 text-gray-600 text-xs font-medium rounded">
                                    {{ $album->total_pages }} trang
                                </span>
                            @endif
                        </div>

                        <!-- Album Title -->
                        <h3 class="text-base sm:text-lg lg:text-xl font-light text-gray-900 mb-2 leading-snug">
                            {{ $album->title }}
                        </h3>

                        <!-- Description -->
                        <p class="text-gray-600 mb-3 leading-relaxed font-light text-sm">
                            {{ Str::limit($album->description, 100) }}
                        </p>

                        <!-- Download Stats -->
                        @if($album->download_count > 0)
                            <div class="text-xs text-gray-500 mb-2">
                                <i class="fas fa-download mr-1"></i>{{ $album->download_count }} lượt tải
                            </div>
                        @endif
                    </div>
                </div>

                <!-- PDF Carousel Side -->
                <div class="{{ $isEven ? 'lg:order-2' : 'lg:order-1' }}">
                    @if($pdfUrl)
                        <!-- PDF Carousel Container -->
                        <div class="relative group bg-white border border-red-100 rounded-lg overflow-hidden hover:shadow-md transition-all duration-300" id="pdf-carousel-{{ $album->id }}">
                            <!-- PDF Pages Container -->
                            <div class="aspect-[4/3] relative bg-gray-50">
                                <!-- PDF Page Display -->
                                <div class="pdf-page-container w-full h-full flex items-center justify-center">
                                    <canvas id="pdf-canvas-{{ $album->id }}" class="max-w-full max-h-full"></canvas>
                                </div>

                                <!-- Loading State -->
                                <div id="pdf-loading-{{ $album->id }}" class="absolute inset-0 flex items-center justify-center bg-gray-50">
                                    <div class="text-center">
                                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-red-500 mx-auto mb-2"></div>
                                        <p class="text-gray-500 text-sm">Đang tải PDF...</p>
                                    </div>
                                </div>

                                <!-- Navigation Arrows -->
                                <button class="pdf-nav-btn pdf-prev absolute left-2 top-1/2 -translate-y-1/2 w-8 h-8 bg-white/80 hover:bg-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 shadow-md z-10"
                                        onclick="prevPage({{ $album->id }})" style="display: none;">
                                    <i class="fas fa-chevron-left text-gray-600 text-sm"></i>
                                </button>
                                <button class="pdf-nav-btn pdf-next absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 bg-white/80 hover:bg-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 shadow-md z-10"
                                        onclick="nextPage({{ $album->id }})" style="display: none;">
                                    <i class="fas fa-chevron-right text-gray-600 text-sm"></i>
                                </button>

                                <!-- Page Counter -->
                                <div class="absolute top-2 right-2 bg-black/50 backdrop-blur-sm px-2 py-1 text-xs text-white font-medium rounded-full">
                                    <span id="current-page-{{ $album->id }}">1</span>/<span id="total-pages-{{ $album->id }}">{{ $album->total_pages ?? '?' }}</span>
                                </div>

                                <!-- PDF Icon -->
                                <div class="absolute top-2 left-2 bg-red-500/90 backdrop-blur-sm px-2 py-1 text-xs text-white font-medium rounded-full">
                                    <i class="fas fa-file-pdf mr-1"></i>PDF
                                </div>
                            </div>

                            <!-- PDF Actions -->
                            <div class="absolute bottom-2 right-2 flex gap-2 opacity-0 group-hover:opacity-100 transition-all duration-300">
                                <button onclick="window.open('{{ $pdfUrl }}', '_blank')"
                                        class="bg-white/90 hover:bg-white backdrop-blur-sm px-2 py-1 text-xs text-gray-700 hover:text-red-600 font-medium rounded-full transition-all duration-300 shadow-sm hover:shadow-md">
                                    <i class="fas fa-external-link-alt mr-1"></i>Mở PDF
                                </button>
                                <a href="{{ $pdfUrl }}" download="{{ $album->title }}.pdf"
                                   class="bg-red-500/90 hover:bg-red-500 backdrop-blur-sm px-2 py-1 text-xs text-white font-medium rounded-full transition-all duration-300 shadow-sm hover:shadow-md">
                                    <i class="fas fa-download mr-1"></i>Tải về
                                </a>
                            </div>
                        </div>

                        <!-- Initialize PDF for this album -->
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                initPDFCarousel({{ $album->id }}, '{{ $pdfUrl }}');
                            });
                        </script>
                    @else
                        <!-- Fallback when no PDF -->
                        <div class="aspect-[4/3] bg-red-50 border border-red-100 flex items-center justify-center rounded-lg">
                            <div class="text-center">
                                <i class="fas fa-file-pdf text-red-300 text-3xl mb-2"></i>
                                <p class="text-red-400 font-light text-sm">Chưa có tài liệu PDF</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>




@else
<!-- Empty State - Minimalist -->
<div class="max-w-5xl mx-auto px-3 sm:px-4 lg:px-6">
    <!-- Header Section - Minimalist -->
    <div class="text-center mb-12">
        <div class="w-12 h-1 bg-gray-300 mx-auto mb-6"></div>
        <h2 class="section-title mb-3 bg-gradient-to-r from-red-600 via-red-700 to-red-800 bg-clip-text text-transparent">
            Timeline
        </h2>
        <p class="subtitle max-w-2xl mx-auto">
            Tài liệu PDF từ các khóa học
        </p>
    </div>

    <!-- Empty Timeline -->
    <div class="relative max-w-4xl mx-auto">
        <!-- Timeline Line - Desktop -->
        <div class="absolute left-1/2 transform -translate-x-1/2 w-px bg-gray-200 h-64 hidden lg:block"></div>

        <!-- Timeline Line - Mobile -->
        <div class="absolute left-4 top-0 w-px bg-gray-200 h-64 lg:hidden"></div>

        <!-- Empty State Content -->
        <div class="relative grid lg:grid-cols-2 gap-8 items-center pl-10 lg:pl-0">
            <!-- Timeline Dot - Desktop -->
            <div class="absolute left-1/2 transform -translate-x-1/2 w-2.5 h-2.5 bg-gray-300 rounded-full z-20 hidden lg:block"></div>

            <!-- Timeline Dot - Mobile -->
            <div class="absolute left-3 transform -translate-x-1/2 w-2.5 h-2.5 bg-gray-300 rounded-full z-20 lg:hidden"></div>

            <!-- Empty Content -->
            <div class="lg:order-1">
                <div class="bg-white border border-gray-100 p-5 rounded-lg">
                    <div class="mb-4">
                        <span class="inline-block px-2 py-1 bg-gray-50 text-gray-500 text-xs font-medium rounded">
                            Chưa có dữ liệu
                        </span>
                    </div>

                    <h3 class="text-lg font-light text-gray-900 mb-3 leading-snug">
                        Timeline trống
                    </h3>

                    <p class="text-gray-600 mb-4 leading-relaxed font-light text-sm">
                        Các tài liệu PDF sẽ sớm được thêm vào timeline này.
                    </p>
                </div>
            </div>

            <!-- Empty PDF -->
            <div class="lg:order-2">
                <div class="aspect-[4/3] bg-red-50 border border-red-100 flex items-center justify-center rounded-lg">
                    <div class="text-center">
                        <i class="fas fa-file-pdf text-red-300 text-4xl mb-3"></i>
                        <p class="text-red-400 font-light text-sm">Chưa có tài liệu PDF</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Call to action - Minimalist -->
        <div class="text-center mt-12">
            <div class="flex justify-center space-x-6 text-sm">
                <a href="{{ route('courses.index') }}"
                   class="text-red-500 hover:text-red-600 font-medium transition-colors">
                    Khám phá khóa học
                </a>
                <a href="{{ route('posts.index') }}"
                   class="text-gray-500 hover:text-gray-600 font-medium transition-colors">
                    Đọc tin tức
                </a>
            </div>
        </div>
    </div>
</div>
@endif

{{-- PDF Timeline with Carousel Navigation --}}

@push('scripts')
<!-- PDF.js Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
// PDF Carousel State Management
const pdfStates = {};

// Initialize PDF Carousel
async function initPDFCarousel(albumId, pdfUrl) {
    try {
        const loadingTask = pdfjsLib.getDocument(pdfUrl);
        const pdf = await loadingTask.promise;

        pdfStates[albumId] = {
            pdf: pdf,
            currentPage: 1,
            totalPages: pdf.numPages,
            scale: 1.5
        };

        // Update total pages display
        document.getElementById(`total-pages-${albumId}`).textContent = pdf.numPages;

        // Show navigation if more than 1 page
        if (pdf.numPages > 1) {
            const navBtns = document.querySelectorAll(`#pdf-carousel-${albumId} .pdf-nav-btn`);
            navBtns.forEach(btn => btn.style.display = 'flex');
        }

        // Render first page
        await renderPage(albumId, 1);

        // Hide loading
        document.getElementById(`pdf-loading-${albumId}`).style.display = 'none';

    } catch (error) {
        console.error('Error loading PDF:', error);
        document.getElementById(`pdf-loading-${albumId}`).innerHTML =
            '<div class="text-center"><i class="fas fa-exclamation-triangle text-red-400 text-2xl mb-2"></i><p class="text-red-400 text-sm">Lỗi tải PDF</p></div>';
    }
}

// Render specific page
async function renderPage(albumId, pageNum) {
    const state = pdfStates[albumId];
    if (!state || !state.pdf) return;

    try {
        const page = await state.pdf.getPage(pageNum);
        const canvas = document.getElementById(`pdf-canvas-${albumId}`);
        const context = canvas.getContext('2d');

        // Calculate scale to fit container
        const container = canvas.parentElement;
        const containerWidth = container.clientWidth;
        const containerHeight = container.clientHeight;

        const viewport = page.getViewport({ scale: 1 });
        const scaleX = containerWidth / viewport.width;
        const scaleY = containerHeight / viewport.height;
        const scale = Math.min(scaleX, scaleY) * 0.9; // 90% to add some padding

        const scaledViewport = page.getViewport({ scale: scale });

        canvas.width = scaledViewport.width;
        canvas.height = scaledViewport.height;

        const renderContext = {
            canvasContext: context,
            viewport: scaledViewport
        };

        await page.render(renderContext).promise;

        // Update current page display
        document.getElementById(`current-page-${albumId}`).textContent = pageNum;
        state.currentPage = pageNum;

    } catch (error) {
        console.error('Error rendering page:', error);
    }
}

// Navigation functions
function nextPage(albumId) {
    const state = pdfStates[albumId];
    if (!state) return;

    if (state.currentPage < state.totalPages) {
        renderPage(albumId, state.currentPage + 1);
    }
}

function prevPage(albumId) {
    const state = pdfStates[albumId];
    if (!state) return;

    if (state.currentPage > 1) {
        renderPage(albumId, state.currentPage - 1);
    }
}

// Handle window resize
window.addEventListener('resize', function() {
    Object.keys(pdfStates).forEach(albumId => {
        const state = pdfStates[albumId];
        if (state && state.pdf) {
            renderPage(albumId, state.currentPage);
        }
    });
});
</script>
@endpush

<style>
/* Timeline Optimizations */
.timeline-item {
    transition: all 0.3s ease;
}

/* PDF Carousel Styles */
.pdf-page-container {
    display: flex;
    align-items: center;
    justify-content: center;
}

.pdf-nav-btn {
    border: none;
    outline: none;
    cursor: pointer;
}

.pdf-nav-btn:focus {
    outline: 2px solid rgba(239, 68, 68, 0.5);
    outline-offset: 2px;
}

.pdf-nav-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* Responsive spacing optimizations */
@media (max-width: 640px) {
    .timeline-item {
        padding-left: 2rem;
    }

    .timeline-dot {
        left: 0.5rem;
    }

    /* Tối ưu spacing cho mobile */
    .space-y-6 > * + * {
        margin-top: 1rem; /* 16px */
    }
}

@media (max-width: 480px) {
    .timeline-item {
        padding-left: 1.75rem;
    }

    .timeline-dot {
        left: 0.375rem;
    }

    /* Spacing tối thiểu cho mobile nhỏ */
    .space-y-6 > * + * {
        margin-top: 0.75rem; /* 12px */
    }

    /* Giảm padding trong content */
    .timeline-content {
        padding: 0.75rem; /* 12px */
    }
}

/* Smooth transitions */
* {
    scroll-behavior: smooth;
}

/* PDF Canvas styling */
canvas {
    max-width: 100%;
    max-height: 100%;
    border-radius: 0.25rem;
}
</style>
