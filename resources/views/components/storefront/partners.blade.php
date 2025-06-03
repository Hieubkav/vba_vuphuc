@php
    // Sử dụng dữ liệu thực từ ViewServiceProvider với fallback
    $partnersData = $partners ?? collect();

    // Fallback: nếu không có dữ liệu từ ViewServiceProvider, lấy trực tiếp từ model
    if ($partnersData->isEmpty()) {
        try {
            $partnersData = \App\Models\Partner::where('status', 'active')
                ->orderBy('order')
                ->get();
        } catch (\Exception $e) {
            $partnersData = collect();
        }
    }

    $partnerCount = $partnersData->count();
    $maxGridItems = 12; // Số lượng tối đa để hiển thị dạng grid
@endphp

<div class="container mx-auto px-4">
    <div class="text-center mb-10">
            <h2 class="text-3xl font-bold text-gray-900">Đối tác của chúng tôi</h2>
            <div class="w-24 h-1 bg-red-600 mx-auto mt-4 mb-6"></div>
            <p class="text-gray-600 max-w-2xl mx-auto">Vũ Phúc Baking tự hào là đối tác chiến lược của nhiều thương hiệu lớn trong ngành bánh và pha chế</p>
        </div>

        @if(isset($partnersData) && !empty($partnersData) && $partnersData->count() > 0)
        <!-- Desktop View - Grid hoặc Swiper tùy thuộc vào số lượng -->
        <div class="hidden sm:block">
            @if($partnerCount <= $maxGridItems)
                <!-- Grid Layout cho số lượng nhỏ đối tác -->
                <div class="grid sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
                    @foreach($partnersData as $partner)
                        <div class="bg-white border border-gray-100 rounded-lg hover:shadow-md hover:border-red-100 transition-all p-4 flex flex-col items-center justify-center group">
                            @if($partner->logo_link)
                            <a href="{{ $partner->website_link ?? '#' }}"
                               target="{{ $partner->website_link ? '_blank' : '_self' }}"
                               class="mb-3 transition-transform duration-300 hover:scale-105">
                                <img
                                    src="{{ asset('storage/' . $partner->logo_link) }}"
                                    alt="{{ $partner->name }}"
                                    class="h-16 w-auto object-contain mx-auto"
                                    onerror="this.style.display='none'; this.parentElement.querySelector('.fallback-logo').style.display='flex';"
                                >
                                <!-- Fallback logo khi ảnh không load được -->
                                <div class="fallback-logo w-16 h-16 bg-gradient-to-br from-red-100 to-red-200 rounded-lg flex items-center justify-center mx-auto" style="display: none;">
                                    <svg class="w-8 h-8 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </a>
                            @else
                                <div class="mb-3 w-16 h-16 bg-gradient-to-br from-red-100 to-red-200 rounded-lg flex items-center justify-center">
                                    <svg class="w-8 h-8 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            @endif
                            <h3 class="text-sm font-medium text-center text-gray-800 group-hover:text-red-600 transition-colors">{{ $partner->name }}</h3>

                            @if($partner->description && strlen($partner->description) <= 100)
                            <p class="text-xs text-gray-500 text-center mt-2 hidden md:block">{{ $partner->description }}</p>
                            @endif

                            @if($partner->website_link)
                            <a href="{{ $partner->website_link }}" target="_blank" class="text-xs text-red-600 hover:text-red-700 mt-2 hidden md:inline-flex items-center">
                                <span>Xem thêm</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Swiper 3D Coverflow cho số lượng lớn đối tác trên desktop -->
                <div class="partner-desktop-carousel">
                    <div class="swiper-container partner-desktop-swiper">
                        <div class="swiper-wrapper">
                            @foreach($partnersData as $partner)
                            <div class="swiper-slide">
                                <div class="bg-white border border-gray-100 rounded-lg p-5 h-full flex flex-col items-center justify-center shadow-sm hover:shadow-md transition-shadow">
                                    @if($partner->logo_link)
                                    <a href="{{ $partner->website_link ?? '#' }}" target="{{ $partner->website_link ? '_blank' : '_self' }}" class="mb-3 transition-all duration-300 hover:scale-110 block">
                                        <img
                                            src="{{ asset('storage/' . $partner->logo_link) }}"
                                            alt="{{ $partner->name }}"
                                            class="h-20 w-auto object-contain mx-auto"
                                            onerror="this.style.display='none'; this.parentElement.querySelector('.fallback-logo').style.display='flex';"
                                        >
                                        <!-- Fallback logo khi ảnh không load được -->
                                        <div class="fallback-logo w-20 h-20 bg-gradient-to-br from-red-100 to-red-200 rounded-lg flex items-center justify-center mx-auto" style="display: none;">
                                            <svg class="w-10 h-10 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                    </a>
                                    @else
                                        <div class="mb-3 w-20 h-20 bg-gradient-to-br from-red-100 to-red-200 rounded-lg flex items-center justify-center">
                                            <svg class="w-10 h-10 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <h3 class="text-base font-medium text-center text-gray-800 mt-2">{{ $partner->name }}</h3>

                                    @if($partner->description && strlen($partner->description) <= 120)
                                    <p class="text-xs text-gray-500 text-center mt-3 line-clamp-2">{{ $partner->description }}</p>
                                    @endif

                                    @if($partner->website_link)
                                    <a href="{{ $partner->website_link }}" target="_blank" class="text-xs text-red-600 hover:text-red-700 mt-3 inline-flex items-center">
                                        <span>Xem thêm</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-1 transform transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Add Navigation -->
                        <div class="swiper-button-next partner-desktop-next"></div>
                        <div class="swiper-button-prev partner-desktop-prev"></div>

                        <!-- Add Pagination -->
                        <div class="swiper-pagination partner-desktop-pagination"></div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Mobile Swiper View (visible only on small screens) -->
        <div class="sm:hidden">
            <div class="swiper-container partner-mobile-swiper">
                <div class="swiper-wrapper">
                    @foreach($partnersData as $partner)
                    <div class="swiper-slide">
                        <div class="bg-white border border-gray-100 rounded-lg p-4 h-full flex flex-col items-center justify-center shadow-sm relative overflow-hidden group">
                            @if($partner->logo_link)
                            <a href="{{ $partner->website_link ?? '#' }}" target="{{ $partner->website_link ? '_blank' : '_self' }}" class="mb-3 transition-transform hover:scale-110 relative z-10">
                                <img
                                    src="{{ asset('storage/' . $partner->logo_link) }}"
                                    alt="{{ $partner->name }}"
                                    class="h-14 w-auto object-contain mx-auto"
                                    onerror="this.style.display='none'; this.parentElement.querySelector('.fallback-logo').style.display='flex';"
                                >
                                <!-- Fallback logo khi ảnh không load được -->
                                <div class="fallback-logo w-14 h-14 bg-gradient-to-br from-red-100 to-red-200 rounded-lg flex items-center justify-center mx-auto" style="display: none;">
                                    <svg class="w-7 h-7 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </a>
                            @else
                                <div class="mb-3 w-14 h-14 bg-gradient-to-br from-red-100 to-red-200 rounded-lg flex items-center justify-center relative z-10">
                                    <svg class="w-7 h-7 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            @endif
                            <h3 class="text-sm font-medium text-center text-gray-800 relative z-10">{{ $partner->name }}</h3>

                            @if($partner->website_link)
                            <a href="{{ $partner->website_link }}" target="_blank" class="text-xs text-red-600 hover:text-red-700 mt-2 inline-flex items-center relative z-10">
                                <span>Xem thêm</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </a>
                            @endif

                            <!-- Hiệu ứng gradient khi hover -->
                            <div class="absolute inset-0 bg-gradient-to-tr from-red-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <!-- Add Pagination -->
                <div class="swiper-pagination partner-mobile-pagination"></div>
            </div>
        </div>
        @endif
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
<style>
    /* Mobile Swiper Styles */
    .partner-mobile-swiper {
        width: 100%;
        padding-bottom: 35px;
    }

    .partner-mobile-swiper .swiper-pagination {
        bottom: 0;
    }

    .partner-mobile-swiper .swiper-pagination-bullet-active {
        background-color: #dc2626;
    }

    /* Desktop Swiper Styles */
    .partner-desktop-carousel {
        position: relative;
        padding: 20px 50px;
    }

    .partner-desktop-swiper {
        width: 100%;
        padding-bottom: 50px;
    }

    .partner-desktop-swiper .swiper-slide {
        transition: all 0.3s ease;
        opacity: 0.75;
    }

    .partner-desktop-swiper .swiper-slide-active {
        opacity: 1;
        transform: scale(1.05);
    }

    .partner-desktop-swiper .swiper-button-next,
    .partner-desktop-swiper .swiper-button-prev {
        color: #dc2626;
        background-color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .partner-desktop-swiper .swiper-button-next:after,
    .partner-desktop-swiper .swiper-button-prev:after {
        font-size: 18px;
        font-weight: bold;
    }

    .partner-desktop-swiper .swiper-button-next:hover,
    .partner-desktop-swiper .swiper-button-prev:hover {
        background-color: #fef2f2;
    }

    .partner-desktop-swiper .swiper-pagination-bullet-active {
        background-color: #dc2626;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Mobile Swiper
        const mobileSwiper = new Swiper('.partner-mobile-swiper', {
            slidesPerView: 2.2,
            spaceBetween: 12,
            centeredSlides: false,
            loop: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.partner-mobile-pagination',
                clickable: true,
            },
            breakpoints: {
                400: {
                    slidesPerView: 2.5,
                    spaceBetween: 15,
                },
            }
        });

        // Initialize Desktop Swiper if it exists
        if (document.querySelector('.partner-desktop-swiper')) {
            const desktopSwiper = new Swiper('.partner-desktop-swiper', {
                effect: 'coverflow',
                grabCursor: true,
                centeredSlides: true,
                slidesPerView: 'auto',
                loop: true,
                autoplay: {
                    delay: 3000,
                    disableOnInteraction: false,
                },
                coverflowEffect: {
                    rotate: 0,
                    stretch: 0,
                    depth: 100,
                    modifier: 2,
                    slideShadows: false,
                },
                navigation: {
                    nextEl: '.partner-desktop-next',
                    prevEl: '.partner-desktop-prev',
                },
                pagination: {
                    el: '.partner-desktop-pagination',
                    clickable: true,
                    dynamicBullets: true,
                },
                breakpoints: {
                    640: {
                        slidesPerView: 2,
                        spaceBetween: 20,
                    },
                    768: {
                        slidesPerView: 3,
                        spaceBetween: 30,
                    },
                    1024: {
                        slidesPerView: 4,
                        spaceBetween: 30,
                    },
                    1280: {
                        slidesPerView: 5,
                        spaceBetween: 40,
                    },
                }
            });
        }
    });
</script>
@endpush