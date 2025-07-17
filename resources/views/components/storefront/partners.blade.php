@php
    // Lấy dữ liệu từ WebDesign configuration
    $partnersWebDesign = webDesignData('partners');
    $isVisible = webDesignVisible('partners');

    // Lấy dữ liệu partners từ ViewServiceProvider hoặc trực tiếp từ model
    $partnersData = $partners ?? collect();

    if ($partnersData->isEmpty()) {
        try {
            $partnersData = \App\Models\Partner::where('status', 'active')
                ->orderBy('order')
                ->orderBy('name')
                ->get();
        } catch (\Exception $e) {
            $partnersData = collect();
        }
    }

    $partnerCount = $partnersData->count();
@endphp

@if($isVisible && $partnerCount > 0)
<div class="container mx-auto px-4">
    <!-- Header Section -->
    <div class="text-center mb-12">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
            {{ $partnersWebDesign->title ?? 'Đối tác tin cậy' }}
        </h2>
        <div class="w-20 h-1 bg-gradient-to-r from-red-600 to-red-500 mx-auto mb-6"></div>
        @if($partnersWebDesign->subtitle ?? null)
        <p class="text-gray-600 text-lg max-w-3xl mx-auto leading-relaxed">
            {{ $partnersWebDesign->subtitle }}
        </p>
        @endif
    </div>

    <!-- Partners Swiper -->
    <div class="partners-swiper-container relative">
        <div class="swiper partners-swiper">
            <div class="swiper-wrapper">
                @foreach($partnersData as $partner)
                <div class="swiper-slide">
                    <div class="partner-card group">
                        <div class="partner-logo-container">
                            @if($partner->logo_link)
                                @if($partner->website_link)
                                <a href="{{ $partner->website_link }}"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="partner-link">
                                @endif
                                    <img
                                        src="{{ asset('storage/' . $partner->logo_link) }}"
                                        alt="{{ $partner->name }}"
                                        class="partner-logo"
                                        loading="lazy"
                                        onerror="this.style.display='none'; this.parentElement.querySelector('.partner-fallback').style.display='flex';">
                                    <!-- Fallback icon -->
                                    <div class="partner-fallback">
                                        <svg class="w-8 h-8 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                                        </svg>
                                    </div>
                                @if($partner->website_link)
                                </a>
                                @endif
                            @else
                                <div class="partner-fallback">
                                    <svg class="w-8 h-8 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Partner name (optional, can be hidden for cleaner look) -->
                        <div class="partner-name">
                            {{ $partner->name }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Navigation buttons -->
        <div class="swiper-button-prev partners-prev"></div>
        <div class="swiper-button-next partners-next"></div>

        <!-- Pagination -->
        <div class="swiper-pagination partners-pagination"></div>
    </div>
</div>

@elseif($isVisible && $partnerCount === 0)
<!-- Empty state -->
<div class="container mx-auto px-4">
    <div class="text-center py-16">
        <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
            </svg>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">Chưa có đối tác</h3>
        <p class="text-gray-600">Chúng tôi đang mở rộng mạng lưới đối tác</p>
    </div>
</div>
@endif

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
<style>
    /* Partners Swiper Styles */
    .partners-swiper-container {
        padding: 0 60px 60px 60px;
    }

    .partners-swiper {
        overflow: visible;
    }

    .partners-swiper .swiper-slide {
        height: auto;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Partner Card Styles */
    .partner-card {
        background: white;
        border-radius: 16px;
        padding: 32px 24px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(229, 231, 235, 0.8);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        height: 140px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .partner-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #dc2626, #ef4444);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .partner-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        border-color: rgba(220, 38, 38, 0.2);
    }

    .partner-card:hover::before {
        transform: scaleX(1);
    }

    /* Partner Logo Container */
    .partner-logo-container {
        width: 100%;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
    }

    .partner-link {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        transition: transform 0.2s ease;
    }

    .partner-link:hover {
        transform: scale(1.05);
    }

    .partner-logo {
        max-height: 60px;
        max-width: 120px;
        width: auto;
        height: auto;
        object-fit: contain;
        opacity: 1;
        transition: all 0.3s ease;
    }

    .partner-card:hover .partner-logo {
        transform: scale(1.05);
    }

    .partner-fallback {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, #fef2f2, #fee2e2);
        border-radius: 12px;
        display: none;
        align-items: center;
        justify-content: center;
    }

    /* Partner Name */
    .partner-name {
        font-size: 12px;
        font-weight: 500;
        color: #6b7280;
        text-align: center;
        line-height: 1.4;
        opacity: 0;
        transform: translateY(4px);
        transition: all 0.3s ease;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .partner-card:hover .partner-name {
        opacity: 1;
        transform: translateY(0);
        color: #374151;
    }

    /* Swiper Navigation */
    .partners-prev,
    .partners-next {
        width: 44px;
        height: 44px;
        background: white;
        border-radius: 50%;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(229, 231, 235, 0.8);
        color: #6b7280;
        transition: all 0.3s ease;
    }

    .partners-prev:hover,
    .partners-next:hover {
        background: #dc2626;
        color: white;
        transform: scale(1.1);
        box-shadow: 0 8px 20px rgba(220, 38, 38, 0.3);
    }

    .partners-prev::after,
    .partners-next::after {
        font-size: 16px;
        font-weight: 600;
    }

    .partners-prev {
        left: 10px;
    }

    .partners-next {
        right: 10px;
    }

    /* Swiper Pagination */
    .partners-pagination {
        bottom: 20px !important;
        text-align: center;
    }

    .partners-pagination .swiper-pagination-bullet {
        width: 8px;
        height: 8px;
        background: #d1d5db;
        opacity: 1;
        transition: all 0.3s ease;
    }

    .partners-pagination .swiper-pagination-bullet-active {
        background: #dc2626;
        transform: scale(1.2);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .partners-swiper-container {
            padding: 0 20px 40px 20px;
        }

        .partner-card {
            padding: 24px 16px;
            height: 120px;
        }

        .partner-logo-container {
            height: 50px;
        }

        .partner-logo {
            max-height: 50px;
            max-width: 100px;
        }

        .partners-prev,
        .partners-next {
            width: 36px;
            height: 36px;
        }

        .partners-prev::after,
        .partners-next::after {
            font-size: 14px;
        }

        .partners-prev {
            left: 5px;
        }

        .partners-next {
            right: 5px;
        }
    }

    @media (max-width: 480px) {
        .partners-swiper-container {
            padding: 0 10px 30px 10px;
        }

        .partner-card {
            padding: 20px 12px;
            height: 100px;
        }

        .partner-name {
            font-size: 11px;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const partnersSwiper = document.querySelector('.partners-swiper');

    if (partnersSwiper) {
        const slideCount = partnersSwiper.querySelectorAll('.swiper-slide').length;

        // Chỉ khởi tạo Swiper nếu có slides
        if (slideCount > 0) {
            const swiper = new Swiper('.partners-swiper', {
                // Cấu hình cơ bản
                slidesPerView: 1,
                spaceBetween: 20,
                centeredSlides: false,

                // Auto play với điều kiện
                autoplay: slideCount > 3 ? {
                    delay: 4000,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true,
                } : false,

                // Loop chỉ khi có đủ slides
                loop: slideCount > 3,

                // Hiệu ứng mượt mà
                speed: 800,
                effect: 'slide',

                // Lazy loading
                lazy: {
                    loadPrevNext: true,
                    loadPrevNextAmount: 2,
                },

                // Navigation
                navigation: {
                    nextEl: '.partners-next',
                    prevEl: '.partners-prev',
                },

                // Pagination
                pagination: {
                    el: '.partners-pagination',
                    clickable: true,
                    dynamicBullets: true,
                    dynamicMainBullets: 3,
                },

                // Responsive breakpoints
                breakpoints: {
                    // Mobile
                    320: {
                        slidesPerView: 1.2,
                        spaceBetween: 16,
                    },
                    // Mobile landscape
                    480: {
                        slidesPerView: 1.5,
                        spaceBetween: 20,
                    },
                    // Tablet
                    640: {
                        slidesPerView: 2,
                        spaceBetween: 24,
                    },
                    // Tablet landscape
                    768: {
                        slidesPerView: 3,
                        spaceBetween: 28,
                    },
                    // Desktop
                    1024: {
                        slidesPerView: 4,
                        spaceBetween: 32,
                    },
                    // Large desktop
                    1280: {
                        slidesPerView: 5,
                        spaceBetween: 36,
                    },
                    // Extra large
                    1536: {
                        slidesPerView: 6,
                        spaceBetween: 40,
                    }
                },

                // Callbacks
                on: {
                    init: function() {
                        // Fade in animation khi swiper được khởi tạo
                        this.el.style.opacity = '1';
                        this.el.style.transform = 'translateY(0)';
                    },

                    slideChange: function() {
                        // Optional: Analytics tracking hoặc custom logic
                        // console.log('Slide changed to:', this.activeIndex);
                    },

                    reachEnd: function() {
                        // Optional: Custom behavior khi đến slide cuối
                        if (!this.params.loop && this.params.autoplay) {
                            // Restart autoplay từ đầu
                            setTimeout(() => {
                                this.slideTo(0);
                            }, 2000);
                        }
                    }
                }
            });

            // Pause autoplay khi hover vào container
            const swiperContainer = document.querySelector('.partners-swiper-container');
            if (swiperContainer && swiper.autoplay) {
                swiperContainer.addEventListener('mouseenter', () => {
                    swiper.autoplay.stop();
                });

                swiperContainer.addEventListener('mouseleave', () => {
                    swiper.autoplay.start();
                });
            }

            // Keyboard navigation
            document.addEventListener('keydown', function(e) {
                if (e.key === 'ArrowLeft') {
                    swiper.slidePrev();
                } else if (e.key === 'ArrowRight') {
                    swiper.slideNext();
                }
            });
        }
    }
});
</script>
@endpush