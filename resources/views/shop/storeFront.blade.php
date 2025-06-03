@extends('layouts.shop')

@section('content')
    {{--
        Hero Banner Slider - Component chính hiển thị ở đầu trang chủ
        - Sử dụng dữ liệu từ model Sliders thông qua ViewServiceProvider
        - Responsive design với ảnh WebP tối ưu cho cả mobile và desktop
        - Tự động ẩn nếu không có dữ liệu slider
        - Hỗ trợ auto-play, navigation controls và indicators
        - Tích hợp SEO-friendly alt text và lazy loading
    --}}
    @include('components.storefront.hero-banner')

    <!-- Giới thiệu tổng quan về Vũ Phúc Baking -->
    <section class="animate-on-scroll py-12 md:py-16 bg-white">
        @include('components.storefront.about-us')
    </section>

    <!-- Thống kê số liệu -->
    <section class="animate-on-scroll py-12 md:py-16 bg-gray-50">
        @include('components.storefront.stats-counter')
    </section>

    <!-- Giới thiệu sản phẩm tiêu biểu -->
    <section class="animate-on-scroll py-12 md:py-16 bg-gray-50">
        @include('components.storefront.featured-products')
    </section>

    <!-- Services Section -->
    <section class="animate-on-scroll py-12 md:py-16 bg-gray-50">
        @include('components.storefront.services')
    </section>

    <!-- Slogan Section -->
    <section class="animate-on-scroll py-6 md:py-8">
        @include('components.storefront.slogan')
    </section>

    <!-- Giới thiệu khóa học - Chỉ hiển thị tổng quan và liên kết đến trang khóa học -->
    <section class="animate-on-scroll py-12 md:py-16 bg-white">
        @include('components.storefront.courses-overview')
    </section>

    <!-- Trusted Partners Gallery -->
    <section class="animate-on-scroll py-12 md:py-16 bg-white">
        @include('components.storefront.partners')
    </section>

    <!-- Curated Articles & Insights -->
    <section class="animate-on-scroll py-12 md:py-16 bg-gray-50">
        @include('components.storefront.blog-posts')
    </section>

    <section class="py-12 md:py-16 bg-gradient-to-r from-red-800 via-red-700 to-red-600 text-white relative overflow-hidden">
        @include('components.storefront.homepage-cta')
    </section>

    {{-- <!-- Elegant Category Showcase - Giới thiệu ngắn gọn các danh mục -->
    <section class="animate-on-scroll py-16 md:py-24">
        @include('components.storefront.categories')
    </section>

    <!-- Brand Story & Heritage -->
    <section class="animate-on-scroll py-16 md:py-24 bg-gray-50">
        @include('components.storefront.about-section')
    </section>

    



    <!-- Đánh giá của khách hàng -->
    <section class="animate-on-scroll py-16 md:py-24 bg-white">
        @include('components.storefront.testimonials')
    </section>

    <!-- FAQ - Câu hỏi thường gặp -->
    <section class="animate-on-scroll py-16 md:py-24 bg-gray-50">
        @include('components.storefront.faq-section')
    </section>

    
    

    <!-- Premium Call To Action -->
    <section class="py-14 md:py-20 bg-gradient-to-r from-red-800 via-red-700 to-red-600 text-white relative overflow-hidden">
        @include('components.storefront.homepage-cta')
    </section>

    <!-- Premium Services Showcase -->
    <section class="animate-on-scroll py-16 md:py-24 bg-white">
        @include('components.storefront.services')
    </section>
    --}}
@endsection

@push('styles')
    <style>
        /* Premium styling and animations */
        section {
            position: relative;
            transition: all 0.5s ease;
        }

        /* Enhanced scroll animations */
        .animate-on-scroll {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 1.2s ease, transform 1.2s ease;
        }

        .animate-on-scroll.animate-visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Section dividers for elegant separation */
        section::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 150px;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(0, 0, 0, 0.1), transparent);
        }

        section:last-of-type::after {
            display: none;
        }

        /* Luxury scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f8f8f8;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, #c53030, #9b2c2c);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(to bottom, #b91c1c, #7f1d1d);
        }

        /* Subtle background pattern */
        .bg-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.15'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.5;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Enhanced scroll animations
            const observerOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.15
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-visible');

                        // Add staggered animation to children if they exist
                        const animatedChildren = entry.target.querySelectorAll('.stagger-item');
                        if (animatedChildren.length) {
                            animatedChildren.forEach((child, index) => {
                                setTimeout(() => {
                                    child.classList.add('animate-visible');
                                }, 150 * index);
                            });
                        }
                    }
                });
            }, observerOptions);

            const sections = document.querySelectorAll('.animate-on-scroll');
            sections.forEach(section => {
                observer.observe(section);
            });

            // Subtle parallax effect for premium feel
            window.addEventListener('scroll', function() {
                const scrollTop = window.pageYOffset;
                const parallaxElements = document.querySelectorAll('.parallax-bg');

                parallaxElements.forEach(element => {
                    const speed = element.dataset.speed || 0.5;
                    element.style.transform = `translateY(${scrollTop * speed}px)`;
                });
            });
        });
    </script>
@endpush
