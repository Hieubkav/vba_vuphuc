<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">

    <meta name="application-name" content="{{ config('app.name') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="canonical" href="{{ url()->current() }}">
    @php
        $defaultDesc = 'Vũ Phúc Baking - Nhà phân phối nguyên phụ liệu, dụng cụ, thiết bị ngành bánh, pha chế, nhà hàng tại khu vực ĐBSCL. Nhà phân phối độc quyền các sản phẩm Rich Products Vietnam khu vực Tây Nam.';
        $pageDesc = isset($seoData) ? $seoData['description'] : (isset($settings) && $settings ? $settings->seo_description : $defaultDesc);
        $pageTitle = isset($seoData) ? $seoData['title'] : (isset($settings) && $settings ? $settings->site_name : 'Vũ Phúc Baking - Nhà phân phối nguyên liệu ngành bánh và pha chế');
        $ogImage = isset($seoData) ? $seoData['ogImage'] : (isset($settings) && $settings && $settings->og_image_link ? asset('storage/' . $settings->og_image_link) : (isset($settings) && $settings && $settings->logo_link ? asset('storage/' . $settings->logo_link) : \App\Helpers\PlaceholderHelper::getLogo()));
    @endphp
    <meta name="description" content="@yield('description', $pageDesc)">
    <meta name="keywords" content="Vũ Phúc Baking, nguyên liệu ngành bánh, pha chế, nhà hàng, ĐBSCL, Rich Products Vietnam, dụng cụ làm bánh, thiết bị pha chế">
    <meta name="robots" content="all">
    <meta property="og:title" content="@yield('title', $pageTitle)">
    <meta property="og:description" content="@yield('description', $pageDesc)">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ $ogImage }}">
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

    <!-- KISS: Không preload để tránh lỗi 404 -->

    <!-- Preconnects & DNS prefetch -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">

    <!-- Critical CSS inline - Optimized -->
    @if(function_exists('criticalCss'))
        {!! criticalCss() !!}
    @else
        <style>body{font-family:ui-sans-serif,system-ui,sans-serif}.container{max-width:1200px;margin:0 auto;padding:0 1rem}.btn{display:inline-flex;align-items:center;padding:.5rem 1rem;border-radius:.375rem;font-weight:500;transition:all .2s}.btn-primary{background-color:#3b82f6;color:white}.btn-primary:hover{background-color:#2563eb}</style>
    @endif

    <!-- Font Awesome CDN - Production ready -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
          integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Alpine.js CDN - For interactive components -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Font Awesome Fallback CSS - Optimized -->
    <style>.fa,.fas,.far,.fab,.fal,.fad{font-family:"Font Awesome 6 Free","Font Awesome 6 Pro","Font Awesome 6 Brands","FontAwesome"!important;font-style:normal;font-variant:normal;text-rendering:auto;line-height:1;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;display:inline-block}.fas{font-weight:900}.far{font-weight:400}.fab{font-family:"Font Awesome 6 Brands"!important;font-weight:400}.fa:before,.fas:before,.far:before,.fab:before{content:"\f03e"}.fa-graduation-cap:before{content:"\f19d"}.fa-newspaper:before{content:"\f1ea"}.fa-handshake:before{content:"\f2b5"}.fa-images:before{content:"\f302"}.fa-user:before{content:"\f007"}.fa-folder:before{content:"\f07b"}.fa-chalkboard-teacher:before{content:"\f51c"}.fa-image:before{content:"\f03e"}</style>

    <!-- Defer non-critical CSS -->
    @if(function_exists('deferNonCriticalCss'))
        {!! deferNonCriticalCss('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Open+Sans:wght@300;400;500;600;700&display=swap') !!}
        {!! deferNonCriticalCss('https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css') !!}
    @else
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Open+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" rel="stylesheet">
    @endif

    @php
        $faviconUrl = isset($settings) && $settings && $settings->favicon_link ? asset('storage/' . $settings->favicon_link) : (isset($settings) && $settings && $settings->logo_link ? asset('storage/' . $settings->logo_link) : \App\Helpers\PlaceholderHelper::getLogo());
    @endphp
    <!-- Favicon -->
    <link rel="icon" href="{{ $faviconUrl }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ $faviconUrl }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ $faviconUrl }}">

    <title>@yield('title', isset($seoData) ? $seoData['title'] : (isset($settings) && $settings ? ($settings->seo_title ?? $settings->site_name) : config('app.name')))</title>

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

    <!-- Shop Layout Styles - Moved to external file -->
    <link rel="stylesheet" href="{{ asset('css/shop-layout.css') }}">

    @filamentStyles
    @livewireStyles
    @vite('resources/css/app.css')

    <!-- Simple Storefront CSS - Clean & Minimal -->
    <link rel="stylesheet" href="{{ asset('css/simple-storefront.css') }}">

    @stack('styles')

</head>

<body class="antialiased min-h-screen flex flex-col">
    <!-- Preloader - Optimized -->
    <div id="page-preloader" class="fixed inset-0 bg-white z-50 flex items-center justify-center transition-opacity duration-500">
        <div class="loader-content flex flex-col items-center">
            @php $siteName = isset($settings) && $settings ? $settings->site_name : config('app.name'); @endphp
            @if(isset($settings) && $settings && $settings->logo_link)
                <img src="{{ asset('storage/' . $settings->logo_link) }}" alt="{{ $siteName }}" class="h-16 w-auto mb-4 animate-pulse" loading="eager">
            @elseif(isset($settings) && $settings && $settings->placeholder_image)
                <img src="{{ asset('storage/' . $settings->placeholder_image) }}" alt="{{ $siteName }}" class="h-16 w-auto mb-4 animate-pulse" loading="eager">
            @else
                <div class="h-16 w-16 mb-4 bg-red-600 rounded-lg flex items-center justify-center animate-pulse">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2L3 7v11a1 1 0 001 1h12a1 1 0 001-1V7l-7-5z"/>
                    </svg>
                </div>
            @endif
            <div class="w-32 h-1 bg-gradient-to-r from-red-700 to-red-500 rounded-full animate-pulse"></div>
            <p class="text-sm text-gray-600 mt-2 animate-pulse">{{ $siteName === config('app.name') ? 'Đang tải...' : $siteName }}</p>
        </div>
    </div>

    <!-- Top Navigation Bar -->
    @include('components.public.subnav')

    <!-- Main Navigation -->
    @include('components.public.navbar')

    <!-- Main Content -->
    <main class="flex-grow bg-white overflow-hidden">
        @yield('content')
    </main>

    <!-- Global CTA Section -->
    @include('components.global.cta-section')

    <!-- Footer -->
    @if(webDesignVisible('footer'))
        <footer>
            @include('components.public.footer')
        </footer>
    @endif

    <!-- Action Buttons -->
    @include('components.public.speedial')

    <!-- Notifications -->
    @livewire('notifications')

    <!-- Critical Scripts -->
    @filamentScripts
    @livewireScripts

    <!-- KISS: Không dùng Vite để tránh lỗi 404 -->
    @if(file_exists(public_path('build/assets/app-UxdMiINA.js')))
        <script src="{{ asset('build/assets/app-UxdMiINA.js') }}"></script>
    @endif
    @if(file_exists(public_path('build/assets/app-GEV_umWj.css')))
        <link rel="stylesheet" href="{{ asset('build/assets/app-GEV_umWj.css') }}">
    @endif

    {{-- KISS: Bỏ performance.css phức tạp gây conflict với lazy loading --}}

    <!-- Shop Layout Scripts - Moved to external file -->
    <script src="{{ asset('js/shop-layout.js') }}"></script>

    <!-- Global Image Error Handler -->
    <script>
        // Global image error handler
        function handleImageError(img) {
            // Ẩn ảnh lỗi
            img.style.display = 'none';

            // Tìm container để hiển thị fallback
            const container = img.closest('.association-item') || img.parentElement;
            if (container) {
                // Tạo fallback placeholder nếu chưa có
                let fallback = container.querySelector('.image-fallback');
                if (!fallback) {
                    fallback = document.createElement('div');
                    fallback.className = 'image-fallback w-12 h-12 bg-gradient-to-br from-red-50 to-red-100 rounded-lg flex items-center justify-center';
                    fallback.innerHTML = '<i class="fas fa-building text-red-400 text-sm"></i>';
                    container.appendChild(fallback);
                }
                fallback.style.display = 'flex';
            }

            console.log('Image error:', img.src);
        }
    </script>

    @stack('scripts')
</body>
</html>
