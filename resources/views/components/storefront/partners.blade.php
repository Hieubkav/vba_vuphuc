@php
    // Sử dụng dữ liệu từ ViewServiceProvider (tránh N+1)
    $partnersData = $partners ?? collect();

    // Fallback: nếu ViewServiceProvider chưa load, lấy trực tiếp từ model
    if ($partnersData->isEmpty()) {
        try {
            $partnersData = \App\Models\Partner::where('status', 'active')
                ->select(['id', 'name', 'logo_link', 'website_link', 'order'])
                ->orderBy('order')
                ->orderBy('name')
                ->get();
        } catch (\Exception $e) {
            $partnersData = collect();
        }
    }

    $partnerCount = $partnersData->count();
@endphp

@if($partnerCount > 0)
<div class="container mx-auto px-4">
    <!-- Tiêu đề minimalist tone đỏ-trắng -->
    <div class="text-center mb-10">
        <div class="inline-block relative">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">Đối tác tin cậy</h2>
            <div class="w-12 h-0.5 bg-red-600 mx-auto rounded-full"></div>
        </div>
        <p class="text-gray-600 text-sm md:text-base max-w-lg mx-auto mt-4">Những đối tác đồng hành cùng chúng tôi</p>
    </div>

    <!-- Partners Swiper - Minimalist Design -->
    <div class="partners-carousel relative">
        <div class="swiper partners-swiper overflow-hidden">
            <div class="swiper-wrapper">
                @foreach($partnersData as $partner)
                <div class="swiper-slide">
                    <div class="group">
                        <!-- Partner Card - Minimalist -->
                        <div class="bg-white rounded-xl p-6 transition-all duration-300 hover:shadow-lg border border-gray-100 hover:border-red-200 flex flex-col items-center justify-center min-h-[140px]">
                            <!-- Logo Container -->
                            <div class="mb-3 transition-transform duration-300 group-hover:scale-105">
                                @if(isset($partner->logo_link) && !empty($partner->logo_link))
                                    <img
                                        data-src="{{ asset('storage/' . $partner->logo_link) }}"
                                        alt="{{ $partner->name ?? 'Đối tác' }}"
                                        class="h-12 w-auto max-w-[80px] object-contain mx-auto partner-logo partner-image filter grayscale group-hover:grayscale-0 transition-all duration-300 lazy-loading"
                                        loading="lazy"
                                        onerror="handlePartnerImageError(this)"
                                        style="opacity: 0; transition: opacity 0.3s ease, filter 0.3s ease, transform 0.3s ease;"
                                    >
                                    <!-- Fallback khi ảnh lỗi -->
                                    <div class="partner-fallback w-12 h-12 bg-gradient-to-br from-red-50 to-red-100 rounded-lg flex items-center justify-center mx-auto" style="display: none;">
                                        <i class="fas fa-handshake text-lg text-red-500"></i>
                                    </div>
                                @else
                                    <!-- Default icon khi không có logo -->
                                    <div class="w-12 h-12 bg-gradient-to-br from-red-50 to-red-100 rounded-lg flex items-center justify-center mx-auto">
                                        <i class="fas fa-handshake text-lg text-red-500"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Partner Name -->
                            <h3 class="text-sm font-medium text-gray-700 group-hover:text-red-600 transition-colors duration-300 text-center leading-tight">
                                {{ $partner->name ?? 'Đối tác' }}
                            </h3>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Navigation - Minimalist -->
            <div class="swiper-button-next partners-next"></div>
            <div class="swiper-button-prev partners-prev"></div>
        </div>

        <!-- Pagination - Minimalist -->
        <div class="swiper-pagination partners-pagination mt-6"></div>
    </div>
</div>
@else
<!-- Fallback UI - Minimalist tone đỏ-trắng -->
<div class="container mx-auto px-4">
    <!-- Tiêu đề vẫn hiển thị -->
    <div class="text-center mb-10">
        <div class="inline-block relative">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">Đối tác tin cậy</h2>
            <div class="w-12 h-0.5 bg-red-600 mx-auto rounded-full"></div>
        </div>
        <p class="text-gray-600 text-sm md:text-base max-w-lg mx-auto mt-4">Những đối tác đồng hành cùng chúng tôi</p>
    </div>

    <!-- Fallback Content - Minimalist -->
    <div class="max-w-md mx-auto text-center">
        <div class="bg-white rounded-2xl border border-gray-100 p-8 relative overflow-hidden">
            <!-- Background decoration -->
            <div class="absolute top-0 right-0 w-20 h-20 bg-red-50 rounded-full -translate-y-10 translate-x-10"></div>
            <div class="absolute bottom-0 left-0 w-16 h-16 bg-red-50 rounded-full translate-y-8 -translate-x-8"></div>

            <!-- Icon -->
            <div class="relative w-16 h-16 mx-auto mb-6 bg-gradient-to-br from-red-500 to-red-600 rounded-2xl flex items-center justify-center shadow-lg">
                <i class="fas fa-handshake text-2xl text-white"></i>
            </div>

            <!-- Content -->
            <h3 class="text-xl font-bold text-gray-900 mb-3">
                Đang mở rộng đối tác
            </h3>

            <p class="text-gray-600 mb-6 text-sm leading-relaxed">
                Chúng tôi luôn chào đón những đối tác tiềm năng để cùng phát triển.
            </p>

            <!-- CTA Button -->
            <a href="#contact" class="inline-flex items-center px-6 py-3 bg-red-600 text-white font-medium rounded-xl hover:bg-red-700 transition-all duration-300 shadow-sm hover:shadow-md hover:-translate-y-0.5">
                <i class="fas fa-envelope mr-2"></i>
                <span>Liên hệ hợp tác</span>
            </a>
        </div>

        <!-- Decorative dots -->
        <div class="mt-6 flex justify-center space-x-1.5">
            <div class="w-1.5 h-1.5 bg-red-300 rounded-full animate-pulse"></div>
            <div class="w-1.5 h-1.5 bg-red-400 rounded-full animate-pulse" style="animation-delay: 0.2s;"></div>
            <div class="w-1.5 h-1.5 bg-red-500 rounded-full animate-pulse" style="animation-delay: 0.4s;"></div>
        </div>
    </div>
</div>
@endif
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
<style>
    /* Partners Component - Minimalist Red-White Theme */
    .partners-carousel {
        position: relative;
        padding: 0;
    }

    .partners-swiper {
        width: 100%;
        overflow: visible;
        padding: 0 2rem 0.5rem;
    }

    .partners-swiper .swiper-slide {
        height: auto;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Partner Card Hover Effects */
    .partners-swiper .swiper-slide .group:hover {
        transform: translateY(-2px);
    }

    /* Navigation Buttons - Minimalist */
    .partners-swiper .swiper-button-next,
    .partners-swiper .swiper-button-prev {
        color: #dc2626;
        --swiper-navigation-size: 14px;
        background: white;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        box-shadow: 0 2px 12px rgba(220, 38, 38, 0.15);
        border: 1px solid #fee2e2;
        opacity: 0;
        transition: all 0.3s ease;
        top: 50%;
        margin-top: -16px;
    }

    .partners-carousel:hover .swiper-button-next,
    .partners-carousel:hover .swiper-button-prev {
        opacity: 1;
    }

    .partners-swiper .swiper-button-next:after,
    .partners-swiper .swiper-button-prev:after {
        font-size: 12px;
        font-weight: 700;
    }

    .partners-swiper .swiper-button-next:hover,
    .partners-swiper .swiper-button-prev:hover {
        background: #dc2626;
        color: white;
        transform: scale(1.05);
        box-shadow: 0 4px 16px rgba(220, 38, 38, 0.25);
    }

    /* Pagination - Minimalist */
    .partners-pagination {
        position: static !important;
        text-align: center;
    }

    .partners-pagination .swiper-pagination-bullet {
        width: 6px;
        height: 6px;
        background: #fecaca;
        opacity: 1;
        transition: all 0.3s ease;
        margin: 0 3px;
    }

    .partners-pagination .swiper-pagination-bullet-active {
        background: #dc2626;
        width: 18px;
        border-radius: 3px;
    }

    /* Partner Logo Effects */
    .partner-logo {
        transition: all 0.3s ease;
    }

    /* Fallback Styling */
    .partner-fallback {
        background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
        position: relative;
        overflow: hidden;
    }

    .partner-fallback::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.6), transparent);
        animation: shimmer 2.5s infinite;
    }

    @keyframes shimmer {
        0% { left: -100%; }
        100% { left: 100%; }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .partners-swiper {
            padding: 0 1rem 0.5rem;
        }

        .partners-swiper .swiper-button-next,
        .partners-swiper .swiper-button-prev {
            display: none;
        }
    }

    @media (max-width: 640px) {
        .partners-swiper {
            padding: 0 0.5rem 0.5rem;
        }
    }

    /* Smooth animations */
    .animate-on-scroll {
        animation: fadeInUp 0.6s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Enhanced image error handling for partners
        window.handlePartnerImageError = function(img) {
            console.log('Partner image error detected:', img.src);

            // Hide the broken image smoothly
            img.style.transition = 'opacity 0.3s ease';
            img.style.opacity = '0';

            setTimeout(() => {
                img.style.display = 'none';

                // Show the fallback placeholder
                const fallback = img.nextElementSibling;
                if (fallback && fallback.classList.contains('partner-fallback')) {
                    fallback.style.display = 'flex';
                    fallback.style.opacity = '0';

                    // Smooth fade-in animation
                    setTimeout(() => {
                        fallback.style.transition = 'opacity 0.4s ease';
                        fallback.style.opacity = '1';
                    }, 100);
                }
            }, 300);
        };

        // Initialize Partners Swiper - Minimalist & Smooth
        if (document.querySelector('.partners-swiper')) {
            const partnersSwiper = new Swiper('.partners-swiper', {
                slidesPerView: 2,
                spaceBetween: 20,
                grabCursor: true,
                loop: true,
                speed: 600,
                autoplay: {
                    delay: 4000,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true,
                },
                pagination: {
                    el: '.partners-pagination',
                    clickable: true,
                    dynamicBullets: false,
                },
                navigation: {
                    nextEl: '.partners-next',
                    prevEl: '.partners-prev',
                },
                breakpoints: {
                    480: {
                        slidesPerView: 3,
                        spaceBetween: 24,
                    },
                    768: {
                        slidesPerView: 4,
                        spaceBetween: 28,
                    },
                    1024: {
                        slidesPerView: 5,
                        spaceBetween: 32,
                    },
                    1280: {
                        slidesPerView: 6,
                        spaceBetween: 36,
                    }
                },
                // Smooth slide effects
                effect: 'slide',
                centeredSlides: false,
                watchSlidesProgress: false,

                // Event handlers
                on: {
                    init: function() {
                        console.log('Partners Swiper initialized');
                    },
                    slideChange: function() {
                        // Optional: Add slide change effects
                    }
                }
            });

            // Pause autoplay on hover
            const swiperContainer = document.querySelector('.partners-swiper');
            if (swiperContainer) {
                swiperContainer.addEventListener('mouseenter', () => {
                    partnersSwiper.autoplay.stop();
                });

                swiperContainer.addEventListener('mouseleave', () => {
                    partnersSwiper.autoplay.start();
                });
            }
        }

        // Apply error handling to all partner images
        document.querySelectorAll('.partner-logo').forEach(img => {
            img.addEventListener('error', function() {
                window.handlePartnerImageError(this);
            });

            // Check if image is already broken on load
            if (img.complete && img.naturalHeight === 0) {
                window.handlePartnerImageError(img);
            }
        });

        // Smooth scroll for CTA button in fallback
        document.querySelectorAll('a[href="#contact"]').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const contactSection = document.querySelector('#contact');
                if (contactSection) {
                    contactSection.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    });
</script>
@endpush