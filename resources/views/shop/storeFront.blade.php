@extends('layouts.shop')

@section('content')
    <!-- Hero Banner -->
    @include('components.storefront.hero-banner')

    <!-- Giới thiệu khóa học - Tối ưu hóa với tone đỏ-trắng minimalist -->
    <section class="storefront-section bg-white bg-pattern-subtle animate-fade-in-optimized">
        @livewire('courses-overview')
    </section>

    <!-- Album timeline khóa học -->
    <section class="storefront-section bg-gray-25 animate-fade-in-optimized">
        @include('components.storefront.album-timeline')
    </section>

    <!-- Nhóm khóa học -->
    <section class="storefront-section bg-white animate-fade-in-optimized">
        @include('components.storefront.course-groups')
    </section>

    <!-- Khóa học theo từng danh mục -->
    <section class="storefront-section bg-gray-25 animate-fade-in-optimized">
        @include('components.storefront.course-categories-sections')
    </section>

    <!-- Đánh giá của khách hàng -->
    <section class="storefront-section bg-white animate-fade-in-optimized">
        @include('components.storefront.testimonials')
    </section>

    <!-- FAQ - Câu hỏi thường gặp -->
    <section class="storefront-section bg-gray-25 animate-fade-in-optimized">
        @include('components.storefront.faq-section')
    </section>

    <!-- Trusted Partners Gallery -->
    <section class="storefront-section bg-white animate-fade-in-optimized">
        @include('components.storefront.partners')
    </section>

    <!-- Curated Articles & Insights -->
    <section class="storefront-section bg-gray-25 animate-fade-in-optimized">
        @include('components.storefront.course-posts')
    </section>

    <!-- Blog Posts -->
    <section class="storefront-section bg-white animate-fade-in-optimized">
        @include('components.storefront.blog-posts')
    </section>

    <!-- Call to Action -->
    <section class="storefront-section bg-gradient-to-r from-red-700 via-red-600 to-red-700 text-white relative overflow-hidden">
        @include('components.storefront.homepage-cta')
    </section>
@endsection

@push('styles')
    <style>
        /* Tối ưu hóa styling minimalist với tone đỏ-trắng */
        section {
            position: relative;
            transition: all 0.3s ease;
        }

        /* Smooth scroll animations - tối ưu performance */
        .animate-on-scroll {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.8s ease, transform 0.8s ease;
        }

        .animate-on-scroll.animate-visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Minimalist section separators */
        section::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(220, 38, 38, 0.1), transparent);
        }

        section:last-of-type::after {
            display: none;
        }

        /* Optimized scrollbar với tone đỏ */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #fafafa;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, #dc2626, #b91c1c);
            border-radius: 6px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(to bottom, #b91c1c, #991b1b);
        }

        /* Custom background colors */
        .bg-gray-25 {
            background-color: #fafafa;
        }

        .bg-red-25 {
            background-color: #fef7f7;
        }

        /* Responsive spacing optimization */
        @media (max-width: 768px) {
            section {
                padding-top: 1.5rem !important;
                padding-bottom: 1.5rem !important;
            }
        }

        /* Performance optimizations */
        .animate-on-scroll {
            will-change: opacity, transform;
        }

        .animate-on-scroll.animate-visible {
            will-change: auto;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tối ưu scroll animations với performance cao
            const observerOptions = {
                root: null,
                rootMargin: '50px',
                threshold: 0.1
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-visible');

                        // Staggered animation cho children elements
                        const animatedChildren = entry.target.querySelectorAll('.stagger-item');
                        if (animatedChildren.length) {
                            animatedChildren.forEach((child, index) => {
                                setTimeout(() => {
                                    child.classList.add('animate-visible');
                                }, 100 * index);
                            });
                        }

                        // Unobserve để tối ưu performance
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            const sections = document.querySelectorAll('.animate-on-scroll');
            sections.forEach(section => {
                observer.observe(section);
            });

            // Smooth scroll behavior cho internal links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        });

        // Initialize storefront lazy loading
        if (window.storefrontLazyLoader) {
            // Refresh lazy loading sau khi tất cả animations đã setup
            setTimeout(() => {
                window.storefrontLazyLoader.refresh();
            }, 100);
        }
    </script>
@endpush
