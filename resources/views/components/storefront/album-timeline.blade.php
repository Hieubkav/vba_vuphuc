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

@if($albums->isNotEmpty())
<div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
    <!-- Header Section - Minimalist -->
    <div class="text-center mb-12 sm:mb-20">
        <div class="w-12 sm:w-16 h-1 bg-red-500 mx-auto mb-6 sm:mb-8"></div>

        <h2 class="text-2xl sm:text-3xl md:text-4xl font-light text-gray-900 mb-3 sm:mb-4 tracking-wide">
            Timeline
        </h2>

        <p class="text-gray-500 max-w-lg mx-auto font-light text-sm sm:text-base">
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
        <div class="space-y-12 sm:space-y-24 lg:space-y-32">
            @foreach($albums as $index => $album)
            @php
                $isEven = $index % 2 === 0;
                $animationDelay = $index * 0.3;
            @endphp

            <!-- Timeline Item - Responsive Layout -->
            <div class="relative grid lg:grid-cols-2 gap-6 sm:gap-12 items-center timeline-item pl-12 sm:pl-16 lg:pl-0"
                 style="animation-delay: {{ $animationDelay }}s;">

                <!-- Timeline Dot - Desktop -->
                <div class="absolute left-1/2 transform -translate-x-1/2 w-3 h-3 bg-red-500 rounded-full z-20 hidden lg:block timeline-dot"></div>

                <!-- Timeline Dot - Mobile -->
                <div class="absolute left-4 sm:left-6 transform -translate-x-1/2 w-2 sm:w-3 h-2 sm:h-3 bg-red-500 rounded-full z-20 lg:hidden timeline-dot"></div>

                <!-- Album Content Side -->
                <div class="{{ $isEven ? 'lg:order-1' : 'lg:order-2' }}">
                    <div class="bg-white border border-red-100 hover:border-red-200 hover:shadow-lg transition-all duration-500 group transform hover:-translate-y-1 p-4 sm:p-6 lg:p-8 rounded-lg">

                        <!-- Date Badge & Order - Responsive -->
                        <div class="mb-4 sm:mb-6 flex items-center gap-2 sm:gap-3 flex-wrap">
                            <span class="inline-block px-2 sm:px-3 py-1 bg-red-50 text-red-600 text-xs sm:text-sm font-medium rounded-md">
                                {{ $album->published_date ? $album->published_date->format('M Y') : 'Chưa xuất bản' }}
                            </span>
                            <span class="inline-block px-2 py-1 bg-gray-100 text-gray-600 text-xs font-medium rounded-md">
                                #{{ $album->order ?? 0 }}
                            </span>
                        </div>

                        <!-- Album Title -->
                        <h3 class="text-lg sm:text-xl lg:text-2xl font-light text-gray-900 mb-3 sm:mb-4 leading-relaxed">
                            {{ $album->title }}
                        </h3>

                        <!-- Description -->
                        <p class="text-gray-600 mb-4 sm:mb-6 leading-relaxed font-light text-sm sm:text-base">
                            {{ Str::limit($album->description, 120) }}
                        </p>

                        <!-- PDF Info - Responsive -->
                        @if($album->pdf_file)
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-3 sm:p-4 bg-red-50 border-l-4 border-red-500 mb-4 sm:mb-6 gap-3 sm:gap-0">
                                <div class="flex items-center space-x-2 sm:space-x-3">
                                    <div class="w-6 sm:w-8 h-6 sm:h-8 bg-red-500 rounded flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-file-pdf text-white text-xs sm:text-sm"></i>
                                    </div>
                                    <div>
                                        <div class="text-xs sm:text-sm font-medium text-gray-900">Tài liệu PDF</div>
                                        @if($album->total_pages)
                                            <div class="text-xs text-gray-500">{{ $album->total_pages }} trang</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex space-x-2 text-xs sm:text-sm">
                                    <button onclick="openPDFViewer('{{ $album->pdf_url }}', '{{ $album->title }}', {{ $album->id }})"
                                            class="text-red-600 hover:text-red-700 font-medium transition-colors">
                                        Xem
                                    </button>
                                    <span class="text-gray-300">|</span>
                                    <a href="{{ $album->pdf_url }}"
                                       download="{{ $album->title }}.pdf"
                                       onclick="incrementDownload({{ $album->id }})"
                                       class="text-red-600 hover:text-red-700 font-medium transition-colors">
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
                            <!-- KISS: Click để mở ảnh trong tab mới -->
                            <div class="relative group cursor-pointer" onclick="window.open('{{ $featuredImage->image_url }}', '_blank')" data-album="{{ $album->id }}">
                                <div class="aspect-[5/4] overflow-hidden bg-gray-50 border border-red-100 group-hover:border-red-200 group-hover:shadow-lg transition-all duration-500 rounded-lg">
                                    <!-- KISS: Ảnh đơn giản -->
                                    <img src="{{ $featuredImage->image_url }}"
                                         alt="{{ $featuredImage->alt_text ?? $album->title }}"
                                         class="w-full h-full object-cover"
                                         loading="lazy"
                                         onerror="handleImageError(this)">
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

                            <!-- Small Images Grid - Responsive -->
                            @if($album->images->count() > 1)
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 sm:gap-3 mt-3 sm:mt-4">
                                    @foreach($album->images->skip(1)->take(4) as $image)
                                        <!-- KISS: Click để mở ảnh trong tab mới -->
                                        <div class="relative group cursor-pointer aspect-square" onclick="window.open('{{ $image->image_url }}', '_blank')" data-album="{{ $album->id }}">
                                            <div class="w-full h-full overflow-hidden bg-gray-50 border border-red-100 rounded-md group-hover:border-red-200 transition-all duration-300">
                                                @if($image && $image->image_url)
                                                    <!-- KISS: Ảnh đơn giản -->
                                                    <img src="{{ $image->image_url }}"
                                                         alt="{{ $image->alt_text ?? $album->title }}"
                                                         class="w-full h-full object-cover"
                                                         loading="lazy"
                                                         onerror="handleImageError(this)">
                                                @else
                                                    <!-- No image placeholder -->
                                                    <div class="w-full h-full bg-gradient-to-br from-red-50 to-red-100 flex items-center justify-center">
                                                        <i class="fas fa-image text-red-300 text-sm sm:text-lg"></i>
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
                            <div class="absolute top-2 sm:top-3 right-2 sm:right-3 bg-white/90 backdrop-blur-sm px-2 sm:px-3 py-1 text-xs text-gray-600 font-medium rounded-full">
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

<!-- KISS: Không cần modal gallery phức tạp -->

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
// KISS: JavaScript đơn giản cho PDF viewer
function openPDFViewer(pdfUrl, title, albumId) {
    const modal = document.getElementById('pdfModal');
    const pdfTitle = document.getElementById('pdfTitle');
    const pdfFrame = document.getElementById('pdfFrame');

    if (pdfTitle) pdfTitle.textContent = title;
    if (pdfFrame) pdfFrame.src = pdfUrl;
    if (modal) modal.classList.remove('hidden');
}

function closePDFViewer() {
    const modal = document.getElementById('pdfModal');
    const pdfFrame = document.getElementById('pdfFrame');

    if (modal) modal.classList.add('hidden');
    if (pdfFrame) pdfFrame.src = '';
}

// KISS: Gallery đơn giản - chỉ hiển thị ảnh
function openImageGallery(albumId, startIndex = 0) {
    // Đơn giản: chỉ mở ảnh trong tab mới
    const images = document.querySelectorAll(`[data-album="${albumId}"] img`);
    if (images[startIndex]) {
        window.open(images[startIndex].src, '_blank');
    }
}

// KISS: Chỉ giữ keyboard navigation đơn giản
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePDFViewer();
    }
});
</script>
@endpush

{{-- KISS: Không cần global handler phức tạp --}}

<style>
/* KISS: CSS đơn giản, không animation phức tạp */

/* KISS: Responsive cải thiện cho mobile */
@media (max-width: 640px) {
    .timeline-item {
        padding-left: 2.5rem; /* 40px */
    }

    .timeline-dot {
        left: 0.75rem; /* 12px */
    }

    /* Giảm khoảng cách giữa các timeline item */
    .space-y-12 > * + * {
        margin-top: 2rem; /* 32px */
    }
}

@media (max-width: 480px) {
    .timeline-item {
        padding-left: 2rem; /* 32px */
    }

    .timeline-dot {
        left: 0.5rem; /* 8px */
    }
}
</style>
