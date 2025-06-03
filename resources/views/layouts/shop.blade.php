<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">

    <meta name="application-name" content="{{ config('app.name') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="canonical" href="{{ url()->current() }}">
    <meta name="description" content="@yield('description', 'Vũ Phúc Baking - Nhà phân phối nguyên phụ liệu, dụng cụ, thiết bị ngành bánh, pha chế, nhà hàng tại khu vực ĐBSCL. Nhà phân phối độc quyền các sản phẩm Rich Products Vietnam khu vực Tây Nam.')">
    <meta name="keywords"
        content="Vũ Phúc Baking, nguyên liệu ngành bánh, pha chế, nhà hàng, ĐBSCL, Rich Products Vietnam, dụng cụ làm bánh, thiết bị pha chế">
    <meta name="robots" content="all">
    <meta property="og:title" content="@yield('title', isset($seoData) ? $seoData['title'] : ($settings->site_name ?? 'Vũ Phúc Baking - Nhà phân phối nguyên liệu ngành bánh và pha chế'))">
    <meta property="og:description" content="@yield('description', isset($seoData) ? $seoData['description'] : ($settings->seo_description ?? 'Vũ Phúc Baking - Nhà phân phối nguyên phụ liệu, dụng cụ, thiết bị ngành bánh, pha chế, nhà hàng tại khu vực ĐBSCL. Nhà phân phối độc quyền các sản phẩm Rich Products Vietnam khu vực Tây Nam.'))">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ isset($seoData) ? $seoData['ogImage'] : ($settings->og_image_link ? asset('storage/' . $settings->og_image_link) : ($settings->logo_link ? asset('storage/' . $settings->logo_link) : \App\Helpers\PlaceholderHelper::getLogo())) }}">
    <script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebPage",
  "name": "Vũ Phúc Baking - Nhà phân phối nguyên liệu ngành bánh và pha chế",
  "description": "Vũ Phúc Baking cung cấp nguyên phụ liệu, dụng cụ, thiết bị ngành bánh, pha chế, nhà hàng tại khu vực ĐBSCL, với vai trò nhà phân phối độc quyền các sản phẩm Rich Products Vietnam khu vực Tây Nam.",
  "url": "https://vuphucbaking.com"
}
</script>
    <meta name="revisit-after" content="1 day">
    <meta name="HandheldFriendly" content="true">
    <meta http-equiv="x-dns-prefetch-control" content="on">
    <meta name="author" content="Manh Hieu">
    <meta name="theme-color" content="#b91c1c">

    <!-- Preconnects & Preloads -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Google Fonts - Montserrat & Open Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Open+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!-- Favicon -->
    <link rel="icon" href="{{ $settings->favicon_link ? asset('storage/' . $settings->favicon_link) : ($settings->logo_link ? asset('storage/' . $settings->logo_link) : \App\Helpers\PlaceholderHelper::getLogo()) }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ $settings->favicon_link ? asset('storage/' . $settings->favicon_link) : ($settings->logo_link ? asset('storage/' . $settings->logo_link) : \App\Helpers\PlaceholderHelper::getLogo()) }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ $settings->favicon_link ? asset('storage/' . $settings->favicon_link) : ($settings->logo_link ? asset('storage/' . $settings->logo_link) : \App\Helpers\PlaceholderHelper::getLogo()) }}">

    <title>@yield('title', isset($seoData) ? $seoData['title'] : ($settings->seo_title ?? $settings->site_name ?? config('app.name')))</title>

    <!-- Structured Data -->
    @if(isset($seoData) && isset($seoData['structuredData']))
    <script type="application/ld+json">
    {!! json_encode($seoData['structuredData'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    @endif

    @if(isset($seoData) && isset($seoData['breadcrumbs']))
    <script type="application/ld+json">
    {!! json_encode(\App\Services\SeoService::getBreadcrumbStructuredData($seoData['breadcrumbs']), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    @endif

    <style>
        :root {
            --primary: #b91c1c;
            --primary-dark: #991b1b;
            --secondary: #1f2937;
            --light: #f9fafb;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --gray-light: #f3f4f6;
        }

        [x-cloak] {
            display: none !important;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Open Sans', system-ui, sans-serif;
            color: #374151;
        }

        h1, h2, h3, h4, h5, h6, .heading {
            font-family: 'Montserrat', system-ui, sans-serif;
        }

        .section-transition {
            transition: all 0.5s ease-in-out;
        }

        .section-transition:hover {
            transform: translateY(-5px);
        }

        .text-primary {
            color: var(--primary);
        }

        .bg-primary {
            background-color: var(--primary);
        }

        .transition-transform {
            transition-property: transform;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 300ms;
        }

        .hover-up:hover {
            transform: translateY(-5px);
        }

        .page-container {
            max-width: 1440px;
            margin-left: auto;
            margin-right: auto;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in {
            animation: fadeIn 0.7s ease-out forwards;
        }

        /* Enhanced Product Card Animations */
        .product-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .product-image {
            transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .product-card:hover .product-image {
            transform: scale(1.1);
        }

        /* Gradient Animations */
        .gradient-animate {
            background-size: 200% 200%;
            animation: gradientShift 3s ease infinite;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Badge Animations */
        .badge-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: .8; }
        }

        /* Loading Skeleton */
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        /* Aspect Ratio Utilities */
        .aspect-w-1 {
            position: relative;
            padding-bottom: calc(var(--tw-aspect-h) / var(--tw-aspect-w) * 100%);
            --tw-aspect-w: 1;
        }

        .aspect-h-1 {
            --tw-aspect-h: 1;
        }

        .aspect-w-1 > * {
            position: absolute;
            height: 100%;
            width: 100%;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
        }

        /* Line Clamp Utilities */
        .line-clamp-2 {
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
        }

        .line-clamp-3 {
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 3;
        }

        /* Prose Styles */
        .prose {
            color: #374151;
            max-width: none;
        }

        .prose h1, .prose h2, .prose h3, .prose h4, .prose h5, .prose h6 {
            color: #111827;
            font-weight: 600;
            font-family: 'Montserrat', system-ui, sans-serif;
        }

        .prose p {
            margin-top: 1.25em;
            margin-bottom: 1.25em;
        }

        .prose-red a {
            color: var(--primary);
        }

        .prose-red a:hover {
            color: var(--primary-dark);
        }

        /* Pagination Styles */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
        }

        .pagination .page-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            color: #374151;
            text-decoration: none;
            transition: all 0.2s;
        }

        .pagination .page-link:hover {
            background-color: #f3f4f6;
            border-color: var(--primary);
            color: var(--primary);
        }

        .pagination .page-link.active {
            background-color: var(--primary);
            border-color: var(--primary);
            color: white;
        }

        .pagination .page-link.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Filter Sidebar */
        .filter-sidebar-desktop {
            position: static !important;
            width: auto !important;
            height: auto !important;
            background: transparent !important;
            z-index: auto !important;
            transition: none !important;
            overflow-y: visible !important;
        }

        /* Mobile Filter Toggle */
        @media (max-width: 1023px) {
            .filter-sidebar {
                position: fixed;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100vh;
                background: white;
                z-index: 50;
                transition: left 0.3s ease;
                overflow-y: auto;
            }

            .filter-sidebar.active {
                left: 0;
            }

            .filter-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100vh;
                background: rgba(0, 0, 0, 0.5);
                z-index: 40;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
            }

            .filter-overlay.active {
                opacity: 1;
                visibility: visible;
            }
        }

        @media (min-width: 1024px) {
            .filter-sidebar {
                position: static !important;
                width: auto !important;
                height: auto !important;
                background: transparent !important;
                z-index: auto !important;
                transition: none !important;
                overflow-y: visible !important;
                left: auto !important;
            }
        }
    </style>

    @filamentStyles
    @vite('resources/css/app.css')

    <!-- Custom CSS cho responsive images -->
    <link rel="stylesheet" href="{{ asset('css/image-responsive.css') }}">

    @stack('styles')

</head>

<body class="antialiased min-h-screen flex flex-col">
    <!-- Preloader - Optimized -->
    <div id="page-preloader" class="fixed inset-0 bg-white z-50 flex items-center justify-center transition-opacity duration-500">
        <div class="loader-content flex flex-col items-center">
            @if($settings->logo_link)
                <img src="{{ asset('storage/' . $settings->logo_link) }}" alt="{{ $settings->site_name ?? config('app.name') }}" class="h-16 w-auto mb-4 animate-pulse" loading="eager">
            @elseif($settings->placeholder_image)
                <img src="{{ asset('storage/' . $settings->placeholder_image) }}" alt="{{ $settings->site_name ?? config('app.name') }}" class="h-16 w-auto mb-4 animate-pulse" loading="eager">
            @else
                <div class="h-16 w-16 mb-4 bg-red-600 rounded-lg flex items-center justify-center animate-pulse">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2L3 7v11a1 1 0 001 1h12a1 1 0 001-1V7l-7-5z"/>
                    </svg>
                </div>
            @endif
            <div class="w-32 h-1 bg-gradient-to-r from-red-700 to-red-500 rounded-full animate-pulse"></div>
            <p class="text-sm text-gray-600 mt-2 animate-pulse">{{ $settings->site_name ?? 'Đang tải...' }}</p>
        </div>
    </div>

    <!-- Top Navigation Bar -->
    @include('components.public.subnav')

    <!-- Main Navigation -->
    @include('components.public.navbar')

    <!-- Main Content -->
    <main class="flex-grow bg-gray-50 overflow-hidden">
        @yield('content')
    </main>

    <!-- Footer -->
    @include('components.public.footer')

    <!-- Action Buttons -->
    @include('components.public.speedial')

    <!-- Notifications -->
    @livewire('notifications')

    <!-- Scripts -->
    @filamentScripts
    @vite('resources/js/app.js')

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!-- Custom JS cho smart images -->
    <script src="{{ asset('js/image-smart.js') }}"></script>

    @stack('scripts')

    <script>
        // Preloader
        document.addEventListener('DOMContentLoaded', function() {
            const preloader = document.getElementById('page-preloader');
            if (preloader) {
                setTimeout(() => {
                    preloader.style.opacity = 0;
                    setTimeout(() => {
                        preloader.style.display = 'none';
                    }, 500);
                }, 500);
            }

            // Animate sections on scroll
            const animateSections = document.querySelectorAll('.animate-on-scroll');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-fade-in');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1 });

            animateSections.forEach(section => {
                observer.observe(section);
            });
        });
    </script>
</body>
</html>
