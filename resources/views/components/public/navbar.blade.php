<header class="bg-white dark:bg-gray-900 shadow-lg sticky top-0 z-50 border-b border-red-100 dark:border-gray-700 backdrop-blur-md bg-white/95 dark:bg-gray-900/95">
    <!-- Main Navigation -->
    <div class="container mx-auto px-4 py-4">
        <div class="flex justify-between items-center">
            <!-- Logo -->
            <a href="{{ route('storeFront') }}" class="flex-shrink-0 flex items-center group">
                @if(isset($settings) && !empty($settings) && $settings->logo_link)
                    <div class="h-14 md:h-18 flex items-center">
                        <img src="{{ asset('storage/' . $settings->logo_link) }}"
                            alt="{{ $settings->site_name ?? 'Vũ Phúc Baking' }}"
                            class="h-auto max-h-full object-contain transition-transform duration-300 group-hover:scale-105"
                            onerror="this.src='{{ asset('images/logo.png') }}'; this.onerror=null;">
                    </div>
                @else
                    <div class="h-14 md:h-18 flex items-center">
                        <img src="{{ asset('images/logo.png') }}"
                            alt="Vũ Phúc Baking"
                            class="h-auto max-h-full object-contain transition-transform duration-300 group-hover:scale-105">
                    </div>
                @endif
            </a>

            <!-- Thanh tìm kiếm - Desktop -->
            <div class="hidden lg:block flex-1 max-w-2xl mx-8">
                @livewire('public.search-bar', ['isMobile' => false])
            </div>

            <!-- Icons - Desktop -->
            <div class="hidden lg:flex items-center space-x-3">
                @livewire('public.cart-icon')
                @livewire('public.user-account')
            </div>

            <!-- Menu mobile (hamburger) -->
            <div class="lg:hidden flex items-center gap-3">
                <!-- Search icon for tablet -->
                <button type="button" class="md:hidden p-2 text-gray-600 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 transition-colors" aria-label="Tìm kiếm" id="mobile-search-button">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>

                <!-- Cart icon for mobile -->
                <div class="md:hidden">
                    @livewire('public.cart-icon')
                </div>

                @if(isset($settings) && !empty($settings) && $settings->hotline)
                    <a href="tel:{{ $settings->hotline }}" class="p-2 text-red-600 bg-red-50 dark:bg-red-900/20 dark:text-red-400 rounded-full hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors shadow-sm" aria-label="Gọi điện">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                    </a>
                @endif

                <button type="button" class="p-2 rounded-lg text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors shadow-sm" aria-label="Menu" id="mobile-menu-button">
                    <svg class="h-6 w-6 transition-transform duration-200" id="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg class="h-6 w-6 hidden transition-transform duration-200" id="close-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div class="lg:hidden hidden bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 shadow-lg" id="mobile-menu">
        <div class="max-h-screen overflow-y-auto">
            <!-- Thanh tìm kiếm Mobile -->
            <div class="p-4 bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                @livewire('public.search-bar', ['isMobile' => true])
            </div>

            <!-- Menu Mobile -->
            <div class="py-2">
                @livewire('public.dynamic-menu', ['isMobile' => true])
            </div>

            <!-- Tài khoản Mobile -->
            <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                <div class="flex items-center justify-between">
                    <span class="text-base font-semibold text-gray-700 dark:text-gray-300">Tài khoản</span>
                    @livewire('public.user-account')
                </div>
            </div>

            <!-- Phần thông tin liên hệ hiển thị trên mobile -->
            <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700 px-4">
                <h4 class="text-sm font-medium text-red-700 dark:text-red-400 mb-3">Thông tin liên hệ:</h4>

                <div class="space-y-2">
                    @if(isset($settings) && !empty($settings) && $settings->email)
                    <a href="mailto:{{ $settings->email }}" class="flex items-center text-gray-700 dark:text-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-red-700 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        {{ $settings->email }}
                    </a>
                    @endif

                    @if(isset($settings) && !empty($settings) && $settings->address)
                    <div class="flex items-start text-gray-700 dark:text-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 mt-0.5 text-red-700 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="text-sm">
                            {{ $settings->address }}
                        </span>
                    </div>
                    @endif
                </div>

                <!-- Social links mobile -->
                <div class="flex items-center space-x-4 mt-4">
                    @if(isset($settings) && !empty($settings) && $settings->facebook_link)
                    <a href="{{ $settings->facebook_link }}" target="_blank" class="bg-red-50 dark:bg-gray-700 p-2 rounded-full text-red-700 dark:text-red-400 hover:bg-red-100 dark:hover:bg-gray-600 transition-colors" aria-label="Facebook">
                        <img src="{{ asset('images/icon_facebook.webp') }}" alt="Facebook" class="h-4 w-4">
                    </a>
                    @endif
                    @if(isset($settings) && !empty($settings) && $settings->youtube_link)
                    <a href="{{ $settings->youtube_link }}" target="_blank" class="bg-red-50 dark:bg-gray-700 p-2 rounded-full text-red-700 dark:text-red-400 hover:bg-red-100 dark:hover:bg-gray-600 transition-colors" aria-label="Youtube">
                        <img src="{{ asset('images/youtube_icon.webp') }}" alt="YouTube" class="h-4 w-4">
                    </a>
                    @endif
                    @if(isset($settings) && !empty($settings) && $settings->zalo_link)
                    <a href="{{ $settings->zalo_link }}" target="_blank" class="bg-red-50 dark:bg-gray-700 p-2 rounded-full hover:bg-red-100 dark:hover:bg-gray-600 transition-colors" aria-label="Zalo">
                        <img src="{{ asset('images/icon_zalo.webp') }}" alt="Zalo" class="h-4 w-4">
                    </a>
                    @endif
                    @if(isset($settings) && !empty($settings) && $settings->messenger_link)
                    <a href="{{ $settings->messenger_link }}" target="_blank" class="bg-red-50 dark:bg-gray-700 p-2 rounded-full text-red-700 dark:text-red-400 hover:bg-red-100 dark:hover:bg-gray-600 transition-colors" aria-label="Messenger">
                        <img src="{{ asset('images/icon_messenger.webp') }}" alt="Messenger" class="h-4 w-4">
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Menu Navigation Bar - Separate horizontal bar -->
    <div class="bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 hidden lg:block">
        <div class="container mx-auto px-4">
            @livewire('public.dynamic-menu', ['isMobile' => false])
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const menuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        const menuIcon = document.getElementById('menu-icon');
        const closeIcon = document.getElementById('close-icon');
        const searchButton = document.getElementById('mobile-search-button');

        if (menuButton && mobileMenu) {
            menuButton.addEventListener('click', function() {
                const isHidden = mobileMenu.classList.contains('hidden');

                if (isHidden) {
                    // Show menu
                    mobileMenu.classList.remove('hidden');
                    mobileMenu.style.animation = 'slideDown 0.3s ease-out';
                    menuIcon.classList.add('hidden');
                    closeIcon.classList.remove('hidden');
                    document.body.style.overflow = 'hidden'; // Prevent scroll
                } else {
                    // Hide menu
                    mobileMenu.style.animation = 'slideUp 0.3s ease-out';
                    setTimeout(() => {
                        mobileMenu.classList.add('hidden');
                        menuIcon.classList.remove('hidden');
                        closeIcon.classList.add('hidden');
                        document.body.style.overflow = ''; // Restore scroll
                    }, 300);
                }
            });
        }

        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            if (mobileMenu && !mobileMenu.contains(event.target) && !menuButton.contains(event.target)) {
                if (!mobileMenu.classList.contains('hidden')) {
                    mobileMenu.style.animation = 'slideUp 0.3s ease-out';
                    setTimeout(() => {
                        mobileMenu.classList.add('hidden');
                        menuIcon.classList.remove('hidden');
                        closeIcon.classList.add('hidden');
                        document.body.style.overflow = '';
                    }, 300);
                }
            }
        });

        // Mobile search button functionality
        if (searchButton) {
            searchButton.addEventListener('click', function() {
                // Toggle mobile menu to show search
                if (mobileMenu.classList.contains('hidden')) {
                    menuButton.click();
                }
                // Focus on search input after menu opens
                setTimeout(() => {
                    const searchInput = mobileMenu.querySelector('input[type="text"]');
                    if (searchInput) {
                        searchInput.focus();
                    }
                }, 350);
            });
        }
    });
</script>

<style>
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideUp {
        from {
            opacity: 1;
            transform: translateY(0);
        }
        to {
            opacity: 0;
            transform: translateY(-10px);
        }
    }

    /* Smooth transitions for navbar */
    header {
        transition: all 0.3s ease;
    }

    /* Backdrop blur effect */
    .backdrop-blur-md {
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
    }

    /* Custom scrollbar for mobile menu */
    #mobile-menu::-webkit-scrollbar {
        width: 4px;
    }

    #mobile-menu::-webkit-scrollbar-track {
        background: transparent;
    }

    #mobile-menu::-webkit-scrollbar-thumb {
        background: rgba(156, 163, 175, 0.5);
        border-radius: 2px;
    }

    #mobile-menu::-webkit-scrollbar-thumb:hover {
        background: rgba(156, 163, 175, 0.7);
    }
</style>