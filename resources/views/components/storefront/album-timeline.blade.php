
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
            <div class="relative grid lg:grid-cols-2 gap-4 sm:gap-6 lg:gap-8 items-center timeline-item pl-8 sm:pl-10 lg:pl-0" id="album-{{ $album->id }}">

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

                <!-- Media Side -->
                <div class="{{ $isEven ? 'lg:order-2' : 'lg:order-1' }}">
                    @if(($album->media_type === 'pdf' && $pdfUrl) || ($album->media_type === 'images' && $album->thumbnail))
                        <!-- Media Container -->
                        <div class="relative group bg-white border border-red-100 rounded-lg overflow-hidden hover:shadow-md transition-all duration-300">

                            <!-- Media Type Badge -->
                            @if($album->media_type === 'pdf')
                                <div class="absolute top-2 left-2 bg-red-500/90 backdrop-blur-sm px-2 py-1 text-xs text-white font-medium rounded-full z-20">
                                    <i class="fas fa-file-pdf mr-1"></i>PDF
                                </div>
                            @else
                                <div class="absolute top-2 left-2 bg-blue-500/90 backdrop-blur-sm px-2 py-1 text-xs text-white font-medium rounded-full z-20">
                                    <i class="fas fa-image mr-1"></i>Hình ảnh
                                </div>
                            @endif

                            @if($album->media_type === 'pdf' && $pdfUrl)
                                <!-- PDF Container -->
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
                                    <button id="pdf-prev-{{ $album->id }}" class="pdf-nav-btn pdf-prev absolute left-2 top-1/2 -translate-y-1/2 w-8 h-8 bg-white/80 hover:bg-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 shadow-md z-10"
                                            onclick="prevPage({{ $album->id }})" style="display: none;">
                                        <i class="fas fa-chevron-left text-gray-600 text-sm"></i>
                                    </button>
                                    <button id="pdf-next-{{ $album->id }}" class="pdf-nav-btn pdf-next absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 bg-white/80 hover:bg-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 shadow-md z-10"
                                            onclick="nextPage({{ $album->id }})" style="display: none;">
                                        <i class="fas fa-chevron-right text-gray-600 text-sm"></i>
                                    </button>

                                    <!-- Page Counter -->
                                    <div class="absolute top-2 right-2 bg-black/50 backdrop-blur-sm px-2 py-1 text-xs text-white font-medium rounded-full">
                                        <span id="current-page-{{ $album->id }}">1</span>/<span id="total-pages-{{ $album->id }}">{{ $album->total_pages ?? '?' }}</span>
                                    </div>

                                    <!-- Progress Bar -->
                                    <div class="absolute bottom-0 left-0 right-0 h-1 bg-black/20">
                                        <div id="pdf-progress-{{ $album->id }}" class="h-full bg-red-500 transition-all duration-300" style="width: 0%"></div>
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

                                    <!-- Navigation Hint -->
                                    <div class="absolute bottom-2 left-2 opacity-0 group-hover:opacity-100 transition-all duration-300 hidden lg:block">
                                        <div class="bg-black/70 backdrop-blur-sm px-2 py-1 text-xs text-white rounded-full">
                                            <i class="fas fa-info-circle mr-1"></i>Cuộn hoặc dùng mũi tên để xem trang
                                        </div>
                                    </div>

                                    <!-- Mobile Navigation Hint -->
                                    <div class="absolute bottom-2 left-2 opacity-0 group-hover:opacity-100 transition-all duration-300 lg:hidden">
                                        <div class="bg-black/70 backdrop-blur-sm px-2 py-1 text-xs text-white rounded-full">
                                            <i class="fas fa-hand-pointer mr-1"></i>Vuốt để xem trang
                                        </div>
                                    </div>
                                </div>
                            @elseif($album->media_type === 'images' && $album->thumbnail)
                                <!-- Image Display with Carousel Support -->
                                <div class="aspect-[4/3] relative bg-gray-50 overflow-hidden">
                                    @if($album->hasMultipleImages())
                                        <!-- Multiple Images Carousel -->
                                        <div id="image-carousel-{{ $album->id }}" class="relative w-full h-full">
                                            @foreach($album->thumbnail_urls as $index => $imageUrl)
                                                <div class="carousel-slide {{ $index === 0 ? 'active' : '' }} absolute inset-0 transition-opacity duration-300 {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}">
                                                    <img
                                                        src="{{ $imageUrl }}"
                                                        alt="{{ $album->title }} - Ảnh {{ $index + 1 }}"
                                                        class="w-full h-full object-cover"
                                                        loading="lazy"
                                                    >
                                                </div>
                                            @endforeach

                                            <!-- Navigation Buttons -->
                                            @if(count($album->thumbnail_urls) > 1)
                                                <button
                                                    onclick="prevImage({{ $album->id }})"
                                                    class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white p-2 rounded-full transition-all duration-200 z-10"
                                                >
                                                    <i class="fas fa-chevron-left text-sm"></i>
                                                </button>
                                                <button
                                                    onclick="nextImage({{ $album->id }})"
                                                    class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white p-2 rounded-full transition-all duration-200 z-10"
                                                >
                                                    <i class="fas fa-chevron-right text-sm"></i>
                                                </button>

                                                <!-- Dots Indicator -->
                                                <div class="absolute bottom-2 left-1/2 transform -translate-x-1/2 flex space-x-1 z-10">
                                                    @foreach($album->thumbnail_urls as $index => $imageUrl)
                                                        <button
                                                            onclick="goToImage({{ $album->id }}, {{ $index }})"
                                                            class="w-2 h-2 rounded-full transition-all duration-200 {{ $index === 0 ? 'bg-white' : 'bg-white/50' }}"
                                                            data-slide="{{ $index }}"
                                                        ></button>
                                                    @endforeach
                                                </div>

                                                <!-- Image Counter -->
                                                <div class="absolute top-2 right-2 bg-black/50 backdrop-blur-sm px-2 py-1 text-xs text-white font-medium rounded-full z-20">
                                                    <span id="current-image-{{ $album->id }}">1</span>/<span>{{ count($album->thumbnail_urls) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <!-- Single Image -->
                                        <img src="{{ $album->thumbnail_url }}"
                                             alt="{{ $album->title }}"
                                             class="w-full h-full object-cover"
                                             loading="lazy">
                                    @endif

                                    <!-- Image Overlay -->
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300"></div>
                                </div>
                            @endif
                        </div>

                        <!-- Initialize PDF for this album -->
                        @if($album->media_type === 'pdf' && $pdfUrl)
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    initPDFCarousel({{ $album->id }}, '{{ $pdfUrl }}');
                                });
                            </script>
                        @endif
                    @else
                        <!-- Fallback when no media -->
                        <div class="aspect-[4/3] bg-gray-50 border border-gray-100 flex items-center justify-center rounded-lg">
                            <div class="text-center">
                                <i class="fas fa-folder-open text-gray-300 text-3xl mb-2"></i>
                                <p class="text-gray-400 font-light text-sm">Chưa có tài liệu hoặc hình ảnh</p>
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
            const prevBtn = document.getElementById(`pdf-prev-${albumId}`);
            const nextBtn = document.getElementById(`pdf-next-${albumId}`);
            if (prevBtn) prevBtn.style.display = 'flex';
            if (nextBtn) nextBtn.style.display = 'flex';
        }

        // Render first page
        await renderPage(albumId, 1);

        // Hide loading
        document.getElementById(`pdf-loading-${albumId}`).style.display = 'none';

        // Add scroll wheel support for PDF navigation
        const canvas = document.getElementById(`pdf-canvas-${albumId}`);
        if (canvas && pdf.numPages > 1) {
            canvas.addEventListener('wheel', function(e) {
                e.preventDefault();
                if (e.deltaY > 0) {
                    nextPage(albumId);
                } else {
                    prevPage(albumId);
                }
            });

            // Add keyboard navigation (when canvas is focused)
            canvas.setAttribute('tabindex', '0');
            canvas.addEventListener('keydown', function(e) {
                switch(e.key) {
                    case 'ArrowRight':
                    case 'ArrowDown':
                    case ' ': // Space key
                        e.preventDefault();
                        nextPage(albumId);
                        break;
                    case 'ArrowLeft':
                    case 'ArrowUp':
                        e.preventDefault();
                        prevPage(albumId);
                        break;
                    case 'Home':
                        e.preventDefault();
                        renderPage(albumId, 1);
                        break;
                    case 'End':
                        e.preventDefault();
                        renderPage(albumId, state.totalPages);
                        break;
                }
            });

            // Add focus styles
            canvas.style.outline = 'none';
            canvas.addEventListener('focus', function() {
                this.style.boxShadow = '0 0 0 2px rgba(239, 68, 68, 0.5)';
            });
            canvas.addEventListener('blur', function() {
                this.style.boxShadow = '';
            });

            // Add touch gestures for mobile
            let touchStartX = 0;
            let touchStartY = 0;

            canvas.addEventListener('touchstart', function(e) {
                touchStartX = e.touches[0].clientX;
                touchStartY = e.touches[0].clientY;
            }, { passive: true });

            canvas.addEventListener('touchend', function(e) {
                if (!e.changedTouches[0]) return;

                const touchEndX = e.changedTouches[0].clientX;
                const touchEndY = e.changedTouches[0].clientY;
                const deltaX = touchEndX - touchStartX;
                const deltaY = touchEndY - touchStartY;

                // Minimum swipe distance
                const minSwipeDistance = 50;

                // Horizontal swipe (left/right)
                if (Math.abs(deltaX) > Math.abs(deltaY) && Math.abs(deltaX) > minSwipeDistance) {
                    if (deltaX > 0) {
                        // Swipe right - previous page
                        prevPage(albumId);
                    } else {
                        // Swipe left - next page
                        nextPage(albumId);
                    }
                }
                // Vertical swipe (up/down)
                else if (Math.abs(deltaY) > minSwipeDistance) {
                    if (deltaY < 0) {
                        // Swipe up - next page
                        nextPage(albumId);
                    } else {
                        // Swipe down - previous page
                        prevPage(albumId);
                    }
                }
            }, { passive: true });
        }

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

        // Update progress bar
        const progressBar = document.getElementById(`pdf-progress-${albumId}`);
        if (progressBar) {
            const progress = (pageNum / state.totalPages) * 100;
            progressBar.style.width = `${progress}%`;
        }

        // Update navigation button states
        updateNavigationButtons(albumId);

    } catch (error) {
        console.error('Error rendering page:', error);
    }
}

// Update navigation button states
function updateNavigationButtons(albumId) {
    const state = pdfStates[albumId];
    if (!state) return;

    const prevBtn = document.getElementById(`pdf-prev-${albumId}`);
    const nextBtn = document.getElementById(`pdf-next-${albumId}`);

    if (prevBtn) {
        prevBtn.disabled = state.currentPage <= 1;
        prevBtn.style.opacity = state.currentPage <= 1 ? '0.5' : '';
    }

    if (nextBtn) {
        nextBtn.disabled = state.currentPage >= state.totalPages;
        nextBtn.style.opacity = state.currentPage >= state.totalPages ? '0.5' : '';
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

// Image Carousel Functions
const imageStates = {};

function initImageCarousel(albumId, imageCount) {
    imageStates[albumId] = {
        currentImage: 0,
        totalImages: imageCount
    };
}

function nextImage(albumId) {
    const state = imageStates[albumId];
    if (!state) return;

    const nextIndex = (state.currentImage + 1) % state.totalImages;
    goToImage(albumId, nextIndex);
}

function prevImage(albumId) {
    const state = imageStates[albumId];
    if (!state) return;

    const prevIndex = (state.currentImage - 1 + state.totalImages) % state.totalImages;
    goToImage(albumId, prevIndex);
}

function goToImage(albumId, index) {
    const state = imageStates[albumId];
    if (!state || index < 0 || index >= state.totalImages) return;

    const carousel = document.getElementById(`image-carousel-${albumId}`);
    if (!carousel) return;

    // Hide all slides
    const slides = carousel.querySelectorAll('.carousel-slide');
    slides.forEach((slide, i) => {
        if (i === index) {
            slide.classList.add('opacity-100');
            slide.classList.remove('opacity-0');
        } else {
            slide.classList.add('opacity-0');
            slide.classList.remove('opacity-100');
        }
    });

    // Update dots
    const dots = carousel.querySelectorAll('[data-slide]');
    dots.forEach((dot, i) => {
        if (i === index) {
            dot.classList.add('bg-white');
            dot.classList.remove('bg-white/50');
        } else {
            dot.classList.add('bg-white/50');
            dot.classList.remove('bg-white');
        }
    });

    // Update counter
    const counter = document.getElementById(`current-image-${albumId}`);
    if (counter) {
        counter.textContent = index + 1;
    }

    state.currentImage = index;
}

// Initialize image carousels on page load
document.addEventListener('DOMContentLoaded', function() {
    // Auto-initialize image carousels
    document.querySelectorAll('[id^="image-carousel-"]').forEach(carousel => {
        const albumId = carousel.id.replace('image-carousel-', '');
        const imageCount = carousel.querySelectorAll('.carousel-slide').length;
        if (imageCount > 1) {
            initImageCarousel(parseInt(albumId), imageCount);
        }
    });
});

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
    position: relative;
}

.pdf-nav-btn {
    border: none;
    outline: none;
    cursor: pointer;
    transition: all 0.2s ease;
}

.pdf-nav-btn:focus {
    outline: 2px solid rgba(239, 68, 68, 0.5);
    outline-offset: 2px;
}

.pdf-nav-btn:disabled {
    opacity: 0.3 !important;
    cursor: not-allowed;
    pointer-events: none;
}

.pdf-nav-btn:hover:not(:disabled) {
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* PDF Canvas styling */
canvas {
    max-width: 100%;
    max-height: 100%;
    border-radius: 0.25rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: box-shadow 0.3s ease;
}

canvas:focus {
    box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.5), 0 2px 8px rgba(0, 0, 0, 0.1);
}

/* Progress bar animation */
#pdf-progress-[id] {
    transition: width 0.3s ease;
}

/* Mobile optimizations for PDF viewer */
@media (max-width: 768px) {
    .pdf-nav-btn {
        width: 2rem;
        height: 2rem;
    }

    .pdf-nav-btn i {
        font-size: 0.75rem;
    }

    /* Make navigation hint smaller on mobile */
    .absolute.bottom-2.left-2 {
        display: none;
    }
}

/* Touch-friendly navigation on mobile */
@media (max-width: 640px) {
    .pdf-nav-btn {
        opacity: 0.8 !important;
        background: rgba(255, 255, 255, 0.95) !important;
    }

    .group:hover .pdf-nav-btn {
        opacity: 0.8 !important;
    }
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

/* Loading animation improvements */
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Accessibility improvements */
.pdf-nav-btn:focus-visible {
    outline: 2px solid #ef4444;
    outline-offset: 2px;
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    .pdf-nav-btn {
        border: 1px solid #000;
    }

    canvas {
        border: 1px solid #000;
    }
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
    .pdf-nav-btn,
    canvas,
    .timeline-item {
        transition: none;
    }
}
</style>
