<header class="bg-white dark:bg-gray-900 shadow-lg sticky top-0 z-50 border-b border-red-100 dark:border-gray-700 backdrop-blur-md bg-white/95 dark:bg-gray-900/95">
    <!-- Main Navigation -->
    <div class="container mx-auto px-4 py-4">
        <div class="flex justify-between items-center">
            <!-- Logo -->
            <a href="{{ route('storeFront') }}" class="flex-shrink-0 flex items-center group">
                <div class="h-14 md:h-18 flex items-center">
                    @php $s=$globalSettings??$settings??null;$hasLogo=$s&&!empty($s->logo_link);$name=$s->site_name??'VBA Vũ Phúc'; @endphp
                    @if($hasLogo)
                        <img src="{{ asset('storage/' . $s->logo_link) }}" alt="{{ $name }}" class="h-auto max-h-full object-contain transition-transform duration-300 group-hover:scale-105" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';" id="main-logo">
                        <div class="hidden items-center justify-center h-full px-4 py-2 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-lg shadow-md transition-transform duration-300 group-hover:scale-105" style="display: none;"><div class="text-center"><div class="text-lg md:text-xl font-bold tracking-tight whitespace-nowrap">VBA VŨ PHÚC</div><div class="text-xs opacity-90 -mt-1 whitespace-nowrap">Khóa học Excel & VBA</div></div></div>
                    @else
                        <div class="flex items-center justify-center h-full px-4 py-2 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-lg shadow-md transition-transform duration-300 group-hover:scale-105"><div class="text-center"><div class="text-lg md:text-xl font-bold tracking-tight whitespace-nowrap">VBA VŨ PHÚC</div><div class="text-xs opacity-90 -mt-1 whitespace-nowrap">Khóa học Excel & VBA</div></div></div>
                    @endif
                </div>
            </a>

            <!-- Thanh tìm kiếm - Desktop -->
            <div class="hidden lg:block flex-1 max-w-2xl mx-8">
                @livewire('public.search-bar', ['isMobile' => false])
            </div>

            <!-- Icons - Desktop -->
            <div class="hidden lg:flex items-center space-x-3">
                @livewire('public.user-account')
            </div>

            <!-- Menu mobile (hamburger) -->
            <div class="lg:hidden flex items-center gap-3">
                <!-- Search icon for mobile/tablet -->
                <button type="button" class="p-2 text-gray-600 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 transition-colors" aria-label="Tìm kiếm" id="mobile-search-button">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>



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

            <!-- Mobile Contact Info -->
            <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700 px-4">
                <h4 class="text-sm font-medium text-red-700 dark:text-red-400 mb-3">Thông tin liên hệ:</h4>
                <div class="space-y-2">
                    @if(isset($settings) && !empty($settings) && $settings->email)
                    <a href="mailto:{{ $settings->email }}" class="flex items-center text-gray-700 dark:text-gray-300">
                        <svg class="h-4 w-4 mr-2 text-red-700 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                        {{ $settings->email }}
                    </a>
                    @endif
                    @if(isset($settings) && !empty($settings) && $settings->address)
                    <div class="flex items-start text-gray-700 dark:text-gray-300">
                        <svg class="h-4 w-4 mr-2 mt-0.5 text-red-700 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        <span class="text-sm">{{ $settings->address }}</span>
                    </div>
                    @endif
                </div>
                <div class="flex items-center space-x-4 mt-4">
                    @php $socials=[['facebook_link','icon_facebook.webp','Facebook'],['youtube_link','youtube_icon.webp','Youtube'],['zalo_link','icon_zalo.webp','Zalo'],['messenger_link','icon_messenger.webp','Messenger']]; @endphp
                    @foreach($socials as [$key,$icon,$label])
                        @if(isset($settings) && $settings->{$key})
                        <a href="{{ $settings->{$key} }}" target="_blank" class="bg-red-50 dark:bg-gray-700 p-2 rounded-full text-red-700 dark:text-red-400 hover:bg-red-100 dark:hover:bg-gray-600 transition-colors" aria-label="{{ $label }}"><img src="{{ asset('images/' . $icon) }}" alt="{{ $label }}" class="h-4 w-4"></a>
                        @endif
                    @endforeach
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

    <!-- Mobile Search Modal -->
    <div id="mobile-search-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden lg:hidden">
        <div class="flex items-start justify-center min-h-screen pt-16 px-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-md transform transition-all duration-300">
                <div class="p-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tìm kiếm</h3>
                        <button type="button" id="close-search-modal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Search component for modal -->
                    @livewire('public.search-bar', ['isMobile' => true])
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Navbar Scripts - Moved to external file -->
<script src="{{ asset('js/navbar.js') }}"></script>

<!-- Navbar Styles - Moved to external file -->
<link rel="stylesheet" href="{{ asset('css/navbar.css') }}">