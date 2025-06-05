{{--
    Album Timeline Component - Giao diện timeline album
    - Hiển thị các album PDF theo thứ tự thời gian
    - Mỗi album có thể lật trang và xem ảnh phụ
    - Responsive design với Tailwind CSS
    - Tích hợp dữ liệu thực từ ViewServiceProvider
--}}

@php
    // Lấy dữ liệu albums từ ViewServiceProvider hoặc tạo collection rỗng
    $albums = $albums ?? collect();
    $isLoading = !isset($albums) || $albums === null;
@endphp

@if($isLoading)
<!-- Loading State -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
        <div class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-200 text-gray-600 rounded-full text-sm font-medium mb-4">
            <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-gray-600 mr-2"></div>
            Đang tải...
        </div>
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
            Thư Viện Hình Ảnh
        </h2>
        <p class="text-gray-600 max-w-2xl mx-auto">
            Đang tải các album khóa học...
        </p>
    </div>

    <!-- Loading Skeleton -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @for($i = 0; $i < 6; $i++)
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100 animate-pulse">
            <div class="h-48 bg-gray-200"></div>
            <div class="p-6">
                <div class="h-4 bg-gray-200 rounded mb-2"></div>
                <div class="h-3 bg-gray-200 rounded w-3/4 mb-4"></div>
                <div class="h-10 bg-gray-200 rounded mb-4"></div>
                <div class="grid grid-cols-4 gap-2 mb-4">
                    @for($j = 0; $j < 4; $j++)
                    <div class="aspect-square bg-gray-200 rounded"></div>
                    @endfor
                </div>
                <div class="flex justify-between">
                    <div class="h-3 bg-gray-200 rounded w-1/3"></div>
                    <div class="h-3 bg-gray-200 rounded w-1/4"></div>
                </div>
            </div>
        </div>
        @endfor
    </div>
</div>
@elseif($albums->isNotEmpty())
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header Section - Minimalist -->
    <div class="text-center mb-20">
        <div class="w-16 h-1 bg-red-500 mx-auto mb-8"></div>

        <h2 class="text-3xl md:text-4xl font-light text-gray-900 mb-4 tracking-wide">
            Timeline
        </h2>

        <p class="text-gray-500 max-w-lg mx-auto font-light">
            Hành trình học tập qua thời gian
        </p>
    </div>

    <!-- Timeline Container -->
    <div class="relative max-w-6xl mx-auto">
        <!-- Timeline Line - Desktop -->
        <div class="absolute left-1/2 transform -translate-x-1/2 w-px bg-red-200 h-full hidden lg:block"></div>

        <!-- Timeline Line - Mobile -->
        <div class="absolute left-6 top-0 w-px bg-red-200 h-full lg:hidden"></div>

        <!-- Timeline Items -->
        <div class="space-y-24 lg:space-y-32">
            @foreach($albums as $index => $album)
            @php
                $isEven = $index % 2 === 0;
                $animationDelay = $index * 0.3;
            @endphp

            <!-- Timeline Item - Minimalist Layout -->
            <div class="relative grid lg:grid-cols-2 gap-12 items-center timeline-item pl-16 lg:pl-0"
                 style="animation-delay: {{ $animationDelay }}s;">

                <!-- Timeline Dot - Desktop -->
                <div class="absolute left-1/2 transform -translate-x-1/2 w-3 h-3 bg-red-500 rounded-full z-20 hidden lg:block timeline-dot"></div>

                <!-- Timeline Dot - Mobile -->
                <div class="absolute left-6 transform -translate-x-1/2 w-3 h-3 bg-red-500 rounded-full z-20 lg:hidden timeline-dot"></div>

                <!-- Album Content Side -->
                <div class="{{ $isEven ? 'lg:order-1' : 'lg:order-2' }}">
                    <div class="bg-white border border-red-100 hover:border-red-200 hover:shadow-lg transition-all duration-500 group transform hover:-translate-y-1 p-8 rounded-lg">

                        <!-- Date Badge - Minimalist -->
                        <div class="mb-6">
                            <span class="inline-block px-3 py-1 bg-red-50 text-red-600 text-sm font-medium rounded-md">
                                {{ $album->published_date ? $album->published_date->format('M Y') : 'Chưa xuất bản' }}
                            </span>
                        </div>

                        <!-- Album Title -->
                        <h3 class="text-2xl font-light text-gray-900 mb-4 leading-relaxed">
                            {{ $album->title }}
                        </h3>

                        <!-- Description -->
                        <p class="text-gray-600 mb-6 leading-relaxed font-light">
                            {{ Str::limit($album->description, 120) }}
                        </p>

                        <!-- PDF Info - Minimalist -->
                        @if($album->pdf_file)
                            <div class="flex items-center justify-between p-4 bg-red-50 border-l-4 border-red-500 mb-6">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-red-500 rounded flex items-center justify-center">
                                        <i class="fas fa-file-pdf text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">Tài liệu PDF</div>
                                        @if($album->total_pages)
                                            <div class="text-xs text-gray-500">{{ $album->total_pages }} trang</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <button onclick="openPDFViewer('{{ $album->pdf_url }}', '{{ $album->title }}', {{ $album->id }})"
                                            class="text-red-600 hover:text-red-700 text-sm font-medium transition-colors">
                                        Xem
                                    </button>
                                    <span class="text-gray-300">|</span>
                                    <a href="{{ $album->pdf_url }}"
                                       download="{{ $album->title }}.pdf"
                                       onclick="incrementDownload({{ $album->id }})"
                                       class="text-red-600 hover:text-red-700 text-sm font-medium transition-colors">
                                        Tải về
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Images Side -->
                <div class="{{ $isEven ? 'lg:order-2' : 'lg:order-1' }}">
                    @if($album->images && $album->images->count() > 0)
                        <!-- Creative Image Layout -->
                        <div class="relative">
                            <!-- Main Featured Image -->
                            @php $featuredImage = $album->images->where('is_featured', true)->first() ?? $album->images->first(); @endphp
                            <div class="relative group cursor-pointer transform hover:-translate-y-2 transition-all duration-500" onclick="openImageGallery({{ $album->id }}, 0)">
                                <div class="aspect-[5/4] overflow-hidden bg-gray-50 border border-red-100 group-hover:border-red-200 group-hover:shadow-lg transition-all duration-500 rounded-lg">
                                    <img data-src="{{ $featuredImage->image_url }}"
                                         alt="{{ $featuredImage->alt_text ?? $album->title }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 album-image lazy-loading"
                                         loading="lazy"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                         style="opacity: 0; transition: opacity 0.3s ease, filter 0.3s ease, transform 0.3s ease;">
                                    <!-- Fallback -->
                                    <div class="w-full h-full bg-red-50 items-center justify-center hidden">
                                        <i class="fas fa-image text-red-300 text-5xl"></i>
                                    </div>
                                </div>
                                <!-- Hover overlay -->
                                <div class="absolute inset-0 bg-red-500/0 group-hover:bg-red-500/5 transition-colors duration-300 rounded-lg"></div>
                                <!-- Hover icon -->
                                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <div class="w-14 h-14 bg-white/90 rounded-full flex items-center justify-center shadow-lg">
                                        <i class="fas fa-search-plus text-red-500 text-lg"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Small Images Grid -->
                            @if($album->images->count() > 1)
                                <div class="grid grid-cols-4 gap-3 mt-4">
                                    @foreach($album->images->skip(1)->take(4) as $image)
                                        <div class="relative group cursor-pointer aspect-square" onclick="openImageGallery({{ $album->id }}, {{ $loop->index + 1 }})">
                                            <div class="w-full h-full overflow-hidden bg-gray-50 border border-red-100 rounded-md group-hover:border-red-200 transition-all duration-300">
                                                @if($image && $image->image_url)
                                                    <img data-src="{{ $image->image_url }}"
                                                         alt="{{ $image->alt_text ?? $album->title }}"
                                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 album-image lazy-loading"
                                                         loading="lazy"
                                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                                         style="opacity: 0; transition: opacity 0.3s ease, filter 0.3s ease, transform 0.3s ease;">
                                                    <!-- Fallback -->
                                                    <div class="w-full h-full bg-red-50 items-center justify-center hidden">
                                                        <i class="fas fa-image text-red-300 text-lg"></i>
                                                    </div>
                                                @else
                                                    <!-- No image fallback -->
                                                    <div class="w-full h-full bg-red-50 flex items-center justify-center">
                                                        <i class="fas fa-image text-red-300 text-lg"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <!-- More indicator -->
                                            @if($loop->last && $album->images->count() > 5)
                                                <div class="absolute inset-0 bg-red-500/80 flex items-center justify-center rounded-md">
                                                    <span class="text-white font-medium text-sm">+{{ $album->images->count() - 5 }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Image count badge -->
                            <div class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm px-3 py-1 text-xs text-gray-600 font-medium rounded-full">
                                {{ $album->images->count() }} ảnh
                            </div>
                        </div>
                    @else
                        <!-- Fallback when no images -->
                        <div class="aspect-[5/4] bg-red-50 border border-red-100 flex items-center justify-center rounded-lg">
                            <div class="text-center">
                                <i class="fas fa-images text-red-300 text-5xl mb-4"></i>
                                <p class="text-red-400 font-light">Chưa có hình ảnh</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>



<!-- PDF Viewer Modal - Tối ưu z-index và responsive -->
<div id="pdfModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[9999] hidden overflow-hidden">
    <div class="flex items-center justify-center min-h-screen p-2 sm:p-4">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-2xl w-full max-w-7xl h-[95vh] sm:h-[90vh] flex flex-col animate-modal-in">
            <!-- Header -->
            <div class="flex items-center justify-between p-3 sm:p-6 border-b border-gray-200 flex-shrink-0">
                <h3 id="pdfTitle" class="text-lg sm:text-xl font-bold text-gray-900 truncate pr-4"></h3>
                <button onclick="closePDFViewer()"
                        class="text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-full p-2 transition-all duration-200 flex-shrink-0">
                    <i class="fas fa-times text-xl sm:text-2xl"></i>
                </button>
            </div>
            <!-- Content -->
            <div class="flex-1 p-3 sm:p-6 overflow-hidden">
                <iframe id="pdfFrame" class="w-full h-full rounded-lg border border-gray-200 bg-gray-50"></iframe>
            </div>
        </div>
    </div>
</div>

<!-- Image Gallery Modal - Tối ưu kích thước và responsive -->
<div id="imageModal" class="fixed inset-0 bg-black/95 backdrop-blur-sm z-[9999] hidden overflow-hidden">
    <div class="flex items-center justify-center min-h-screen p-2 sm:p-4">
        <div class="relative w-full max-w-6xl h-full flex flex-col animate-modal-in">
            <!-- Close Button - Cải thiện vị trí và kích thước -->
            <button onclick="closeImageGallery()"
                    class="absolute top-4 right-4 z-20 text-white hover:text-gray-300 bg-black/50 hover:bg-black/70 rounded-full p-3 transition-all duration-200 backdrop-blur-sm">
                <i class="fas fa-times text-xl sm:text-2xl"></i>
            </button>

            <!-- Image Container - Tối ưu kích thước -->
            <div class="flex-1 flex items-center justify-center px-4 sm:px-8 py-16 sm:py-20">
                <div id="imageContainer" class="text-center w-full max-w-5xl">
                    <!-- Images will be loaded here -->
                </div>
            </div>

            <!-- Navigation Controls - Cải thiện responsive -->
            <div class="flex-shrink-0 flex justify-center items-center pb-6 sm:pb-8 px-4">
                <div class="flex items-center space-x-4 sm:space-x-6">
                    <button onclick="previousImage()"
                            class="bg-white/20 hover:bg-white/30 text-white px-4 py-3 sm:px-6 sm:py-3 rounded-lg transition-all duration-200 backdrop-blur-sm flex items-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-chevron-left"></i>
                        <span class="hidden sm:inline">Trước</span>
                    </button>

                    <!-- Image Counter -->
                    <div class="bg-white/20 text-white px-4 py-3 rounded-lg backdrop-blur-sm">
                        <span id="imageCounter" class="text-sm sm:text-base font-medium">1 / 1</span>
                    </div>

                    <button onclick="nextImage()"
                            class="bg-white/20 hover:bg-white/30 text-white px-4 py-3 sm:px-6 sm:py-3 rounded-lg transition-all duration-200 backdrop-blur-sm flex items-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span class="hidden sm:inline">Sau</span>
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@else
<!-- Empty State - Minimalist -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header Section - Minimalist -->
    <div class="text-center mb-20">
        <div class="w-16 h-1 bg-gray-300 mx-auto mb-8"></div>

        <h2 class="text-3xl md:text-4xl font-light text-gray-900 mb-4 tracking-wide">
            Timeline
        </h2>

        <p class="text-gray-500 max-w-lg mx-auto font-light">
            Hành trình học tập qua thời gian
        </p>
    </div>

    <!-- Empty Timeline -->
    <div class="relative max-w-6xl mx-auto">
        <!-- Timeline Line - Desktop -->
        <div class="absolute left-1/2 transform -translate-x-1/2 w-px bg-gray-200 h-96 hidden lg:block"></div>

        <!-- Timeline Line - Mobile -->
        <div class="absolute left-6 top-0 w-px bg-gray-200 h-96 lg:hidden"></div>

        <!-- Empty State Content -->
        <div class="relative grid lg:grid-cols-2 gap-12 items-center pl-16 lg:pl-0">
            <!-- Timeline Dot - Desktop -->
            <div class="absolute left-1/2 transform -translate-x-1/2 w-3 h-3 bg-gray-300 rounded-full z-20 hidden lg:block"></div>

            <!-- Timeline Dot - Mobile -->
            <div class="absolute left-6 transform -translate-x-1/2 w-3 h-3 bg-gray-300 rounded-full z-20 lg:hidden"></div>

            <!-- Empty Content -->
            <div class="lg:order-1">
                <div class="bg-white border border-gray-100 p-8 rounded-lg">
                    <div class="mb-6">
                        <span class="inline-block px-3 py-1 bg-gray-50 text-gray-500 text-sm font-medium rounded-md">
                            Chưa có dữ liệu
                        </span>
                    </div>

                    <h3 class="text-2xl font-light text-gray-900 mb-4 leading-relaxed">
                        Timeline trống
                    </h3>

                    <p class="text-gray-600 mb-6 leading-relaxed font-light">
                        Các album khóa học sẽ sớm được thêm vào timeline này.
                    </p>
                </div>
            </div>

            <!-- Empty Image -->
            <div class="lg:order-2">
                <div class="aspect-[5/4] bg-gray-50 border border-gray-100 flex items-center justify-center rounded-lg">
                    <div class="text-center">
                        <i class="fas fa-images text-gray-300 text-6xl mb-4"></i>
                        <p class="text-gray-400 font-light">Chưa có hình ảnh</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Call to action - Minimalist -->
        <div class="text-center mt-16">
            <div class="flex justify-center space-x-8">
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

@push('scripts')
<script>
// Utility function to safely get element
function safeGetElement(id) {
    const element = document.getElementById(id);
    if (!element) {
        console.warn(`Element with id '${id}' not found`);
    }
    return element;
}

// PDF Viewer Functions
function openPDFViewer(pdfUrl, title, albumId) {
    const modal = safeGetElement('pdfModal');
    if (!modal) return;

    const modalContent = modal.querySelector('.animate-modal-in');
    const pdfTitle = safeGetElement('pdfTitle');
    const pdfFrame = safeGetElement('pdfFrame');

    if (pdfTitle) pdfTitle.textContent = title;
    if (pdfFrame) pdfFrame.src = pdfUrl;

    // Show modal with animation
    modal.classList.remove('hidden');
    document.body.classList.add('modal-open');

    // Reset animation
    if (modalContent) {
        modalContent.classList.remove('animate-modal-in');
        void modalContent.offsetWidth; // Force reflow
        modalContent.classList.add('animate-modal-in');
    }

    // Increment view count
    if (albumId) {
        incrementView(albumId);
    }
}

function closePDFViewer() {
    const modal = safeGetElement('pdfModal');
    if (!modal) return;

    const modalContent = modal.querySelector('.animate-modal-in');
    const pdfFrame = safeGetElement('pdfFrame');

    // Add exit animation
    if (modalContent) {
        modalContent.classList.remove('animate-modal-in');
        modalContent.classList.add('animate-modal-out');
    }

    // Hide modal after animation
    setTimeout(() => {
        modal.classList.add('hidden');
        if (pdfFrame) pdfFrame.src = '';
        document.body.classList.remove('modal-open');
        if (modalContent) modalContent.classList.remove('animate-modal-out');
    }, 200);
}

// Image Gallery Functions
let currentAlbumImages = [];
let currentImageIndex = 0;
let imageLoadingTimeout = null;

function openImageGallery(albumId, startIndex = 0) {
    const modal = safeGetElement('imageModal');
    if (!modal) return;

    const modalContent = modal.querySelector('.animate-modal-in');
    const imageContainer = safeGetElement('imageContainer');

    if (!imageContainer) return;

    // Clear any existing timeout
    if (imageLoadingTimeout) {
        clearTimeout(imageLoadingTimeout);
    }

    // Show loading state with timeout fallback
    imageContainer.innerHTML = `
        <div class="text-center text-white">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-white mx-auto mb-4"></div>
            <p class="text-lg">Đang tải ảnh...</p>
        </div>
    `;

    // Show modal with animation
    modal.classList.remove('hidden');
    document.body.classList.add('modal-open');

    // Reset animation
    if (modalContent) {
        modalContent.classList.remove('animate-modal-in');
        void modalContent.offsetWidth; // Force reflow
        modalContent.classList.add('animate-modal-in');
    }

    // Set timeout for loading fallback
    imageLoadingTimeout = setTimeout(() => {
        showImageError('Tải ảnh quá lâu, vui lòng thử lại');
    }, 10000); // 10 seconds timeout

    // Fetch images from API with better error handling
    fetch(`/api/albums/${albumId}/images`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        clearTimeout(imageLoadingTimeout);

        if (data.success && data.images && data.images.length > 0) {
            currentAlbumImages = data.images;
            currentImageIndex = Math.min(startIndex, data.images.length - 1);
            displayCurrentImage();
        } else {
            showNoImages();
        }
    })
    .catch(error => {
        clearTimeout(imageLoadingTimeout);
        console.error('Error loading images:', error);
        showImageError('Không thể tải ảnh. Vui lòng kiểm tra kết nối mạng.');
    });
}

function showNoImages() {
    const imageContainer = safeGetElement('imageContainer');
    const imageCounter = safeGetElement('imageCounter');

    if (imageContainer) {
        imageContainer.innerHTML = `
            <div class="text-center text-white">
                <i class="fas fa-images text-6xl mb-4 opacity-50"></i>
                <p class="text-lg mb-2">Không có ảnh nào</p>
                <p class="text-sm opacity-75">Album này chưa có hình ảnh</p>
            </div>
        `;
    }

    if (imageCounter) {
        imageCounter.textContent = '0 / 0';
    }
}

function showImageError(message) {
    const imageContainer = safeGetElement('imageContainer');
    const imageCounter = safeGetElement('imageCounter');

    if (imageContainer) {
        imageContainer.innerHTML = `
            <div class="text-center text-white">
                <i class="fas fa-exclamation-triangle text-6xl mb-4 opacity-50"></i>
                <p class="text-lg mb-2">Lỗi tải ảnh</p>
                <p class="text-sm opacity-75">${message}</p>
                <button onclick="location.reload()" class="mt-4 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                    Tải lại trang
                </button>
            </div>
        `;
    }

    if (imageCounter) {
        imageCounter.textContent = '0 / 0';
    }
}

function displayCurrentImage() {
    const container = safeGetElement('imageContainer');
    const counter = safeGetElement('imageCounter');

    if (!container) return;

    if (currentAlbumImages.length > 0 && currentAlbumImages[currentImageIndex]) {
        const image = currentAlbumImages[currentImageIndex];

        // Update counter
        if (counter) {
            counter.textContent = `${currentImageIndex + 1} / ${currentAlbumImages.length}`;
        }

        // Update navigation buttons state
        updateNavigationButtons();

        // Create image with lazy loading and better error handling
        container.innerHTML = `
            <div class="relative w-full h-full flex items-center justify-center">
                <div class="relative">
                    <!-- Loading placeholder -->
                    <div id="imageLoader" class="absolute inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 rounded-lg">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-white"></div>
                    </div>

                    <img id="currentImage"
                         src="${image.url}"
                         alt="${image.alt_text || 'Ảnh album'}"
                         class="max-w-full max-h-[80vh] w-auto h-auto object-contain rounded-lg shadow-2xl transition-all duration-300 opacity-0"
                         style="max-width: 90vw;"
                         onload="handleImageLoad(this)"
                         onerror="handleImageError(this)">
                </div>

                ${image.caption ? `
                    <div class="absolute bottom-4 left-4 right-4 bg-black bg-opacity-75 text-white p-3 rounded-lg backdrop-blur-sm">
                        <p class="text-sm">${image.caption}</p>
                    </div>
                ` : ''}
            </div>
        `;
    } else {
        showNoImages();
    }
}

// Handle successful image load
function handleImageLoad(img) {
    const loader = document.getElementById('imageLoader');
    if (loader) {
        loader.style.display = 'none';
    }
    img.style.opacity = '1';
}

// Handle image load error
function handleImageError(img) {
    const loader = document.getElementById('imageLoader');
    if (loader) {
        loader.style.display = 'none';
    }

    img.style.display = 'none';
    img.parentElement.innerHTML = `
        <div class="text-center text-white p-8">
            <i class="fas fa-image text-6xl mb-4 opacity-50"></i>
            <p class="text-lg mb-2">Không thể tải ảnh</p>
            <p class="text-sm opacity-75">Ảnh có thể đã bị xóa hoặc di chuyển</p>
        </div>
    `;
}

function updateNavigationButtons() {
    const prevBtn = document.querySelector('button[onclick="previousImage()"]');
    const nextBtn = document.querySelector('button[onclick="nextImage()"]');

    if (prevBtn) {
        const isDisabled = currentImageIndex <= 0;
        prevBtn.disabled = isDisabled;
        prevBtn.classList.toggle('opacity-50', isDisabled);
        prevBtn.classList.toggle('cursor-not-allowed', isDisabled);
        prevBtn.classList.toggle('hover:bg-gray-700', !isDisabled);
    }

    if (nextBtn) {
        const isDisabled = currentImageIndex >= currentAlbumImages.length - 1;
        nextBtn.disabled = isDisabled;
        nextBtn.classList.toggle('opacity-50', isDisabled);
        nextBtn.classList.toggle('cursor-not-allowed', isDisabled);
        nextBtn.classList.toggle('hover:bg-gray-700', !isDisabled);
    }
}

function closeImageGallery() {
    const modal = safeGetElement('imageModal');
    if (!modal) return;

    const modalContent = modal.querySelector('.animate-modal-in');
    const imageContainer = safeGetElement('imageContainer');
    const imageCounter = safeGetElement('imageCounter');

    // Clear any loading timeout
    if (imageLoadingTimeout) {
        clearTimeout(imageLoadingTimeout);
        imageLoadingTimeout = null;
    }

    // Add exit animation
    if (modalContent) {
        modalContent.classList.remove('animate-modal-in');
        modalContent.classList.add('animate-modal-out');
    }

    // Hide modal after animation
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.classList.remove('modal-open');
        if (modalContent) modalContent.classList.remove('animate-modal-out');

        // Reset state
        currentAlbumImages = [];
        currentImageIndex = 0;
        if (imageContainer) imageContainer.innerHTML = '';
        if (imageCounter) imageCounter.textContent = '1 / 1';
    }, 200);
}

function previousImage() {
    if (currentImageIndex > 0) {
        currentImageIndex--;
        displayCurrentImage();
    }
}

function nextImage() {
    if (currentImageIndex < currentAlbumImages.length - 1) {
        currentImageIndex++;
        displayCurrentImage();
    }
}

// Download counter
function incrementDownload(albumId) {
    fetch(`/api/albums/${albumId}/download`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    }).then(response => response.json())
      .then(data => {
          if (data.success) {
              console.log('Download count updated:', data.download_count);
          }
      })
      .catch(error => console.error('Error:', error));
}

// View counter
function incrementView(albumId) {
    fetch(`/api/albums/${albumId}/view`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    }).then(response => response.json())
      .then(data => {
          if (data.success) {
              console.log('View count updated:', data.view_count);
          }
      })
      .catch(error => console.error('Error:', error));
}

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePDFViewer();
        closeImageGallery();
    }

    // Gallery navigation
    const imageModal = safeGetElement('imageModal');
    if (imageModal && !imageModal.classList.contains('hidden')) {
        if (e.key === 'ArrowLeft') {
            e.preventDefault();
            previousImage();
        } else if (e.key === 'ArrowRight') {
            e.preventDefault();
            nextImage();
        }
    }
});

// Touch gestures for mobile
let touchStartX = 0;
let touchEndX = 0;

// Initialize touch events safely
document.addEventListener('DOMContentLoaded', function() {
    const imageModal = safeGetElement('imageModal');
    const pdfModal = safeGetElement('pdfModal');

    if (imageModal) {
        imageModal.addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });

        imageModal.addEventListener('touchend', function(e) {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        }, { passive: true });

        // Click outside modal to close
        imageModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageGallery();
            }
        });
    }

    if (pdfModal) {
        // Click outside modal to close
        pdfModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closePDFViewer();
            }
        });
    }

    // Preload critical images for better performance
    preloadCriticalImages();
});

// Preload first few images for better UX
function preloadCriticalImages() {
    const firstImages = document.querySelectorAll('.timeline-item img[loading="lazy"]');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                if (img.dataset.src) {
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                }
                observer.unobserve(img);
            }
        });
    }, {
        rootMargin: '50px'
    });

    firstImages.forEach(img => {
        if (img.dataset.src) {
            observer.observe(img);
        }
    });
}

function handleSwipe() {
    const swipeThreshold = 50;
    const swipeDistance = touchEndX - touchStartX;

    if (Math.abs(swipeDistance) > swipeThreshold) {
        if (swipeDistance > 0) {
            // Swipe right - previous image
            previousImage();
        } else {
            // Swipe left - next image
            nextImage();
        }
    }
}
</script>
@endpush

<style>
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes scaleIn {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

@keyframes modalIn {
    from {
        opacity: 0;
        transform: scale(0.95) translateY(20px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

@keyframes modalOut {
    from {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
    to {
        opacity: 0;
        transform: scale(0.95) translateY(20px);
    }
}

.animate-on-scroll {
    animation: fadeInUp 0.6s ease-out forwards;
}

.animate-modal-in {
    animation: modalIn 0.3s ease-out forwards;
}

.animate-modal-out {
    animation: modalOut 0.2s ease-in forwards;
}

/* Timeline animations - Minimalist */
.timeline-item {
    animation: fadeInUp 0.8s ease-out forwards;
    opacity: 0;
}

.timeline-dot {
    transition: all 0.3s ease;
}

.timeline-dot:hover {
    transform: scale(1.2);
}

/* Minimalist hover effects */
.timeline-item:hover .timeline-dot {
    background-color: #ef4444;
}

/* Timeline responsive */
@media (max-width: 1024px) {
    .timeline-item {
        animation: fadeInUp 0.6s ease-out forwards;
    }
}

/* Aspect ratio utilities */
.aspect-square {
    aspect-ratio: 1 / 1;
}

/* Line clamp utilities */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Custom scrollbar for modals */
.modal-content::-webkit-scrollbar {
    width: 6px;
}

.modal-content::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, 0.1);
    border-radius: 3px;
}

.modal-content::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.3);
    border-radius: 3px;
}

.modal-content::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.5);
}

/* Loading animation */
@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.animate-spin {
    animation: spin 1s linear infinite;
}

/* Bounce animation for empty state */
@keyframes bounce {
    0%, 20%, 53%, 80%, 100% {
        transform: translate3d(0,0,0);
    }
    40%, 43% {
        transform: translate3d(0, -30px, 0);
    }
    70% {
        transform: translate3d(0, -15px, 0);
    }
    90% {
        transform: translate3d(0, -4px, 0);
    }
}

.animate-bounce {
    animation: bounce 2s infinite;
}

/* Pulse animation */
@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: .5;
    }
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Modal Improvements */
.modal-backdrop {
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
}

/* Prevent body scroll when modal is open */
body.modal-open {
    overflow: hidden;
    position: fixed;
    width: 100%;
}

/* Image gallery specific styles */
#imageModal img {
    transition: opacity 0.3s ease-in-out;
}

/* Touch gestures for mobile */
@media (max-width: 768px) {
    #imageModal .relative {
        touch-action: pan-x;
    }
}

/* High z-index to ensure modals are always on top */
.modal-overlay {
    z-index: 9999 !important;
}

/* Responsive grid improvements */
@media (max-width: 640px) {
    .grid-cols-4 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    /* Modal adjustments for mobile */
    #pdfModal .max-w-7xl {
        max-width: 100vw;
        margin: 0;
    }

    #imageModal .max-w-6xl {
        max-width: 100vw;
        margin: 0;
    }
}

@media (max-width: 768px) {
    .md\:grid-cols-2 {
        grid-template-columns: repeat(1, minmax(0, 1fr));
    }

    /* Improve modal spacing on tablets */
    #imageModal .py-16 {
        padding-top: 4rem;
        padding-bottom: 4rem;
    }
}

/* Landscape orientation adjustments */
@media (orientation: landscape) and (max-height: 600px) {
    #imageModal .py-16 {
        padding-top: 2rem;
        padding-bottom: 2rem;
    }

    #imageModal .pb-6 {
        padding-bottom: 1rem;
    }
}
</style>
