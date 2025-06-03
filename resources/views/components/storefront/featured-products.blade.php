@php
    // Sử dụng dữ liệu thực từ ViewServiceProvider
    // Kiểm tra và đảm bảo dữ liệu tồn tại
    $products = isset($featuredProducts) && !empty($featuredProducts) ? $featuredProducts : collect();
    $productsCount = $products->count();
@endphp

@if($productsCount > 0)
<div class="container mx-auto px-4">
    <!-- Section Header -->
    <div class="text-center mb-10 md:mb-12">
            <span class="inline-block py-1 px-3 text-xs font-semibold bg-red-100 text-red-800 rounded-full tracking-wider mb-4">SẢN PHẨM</span>
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 leading-tight font-montserrat mb-6">
                Sản phẩm <span class="text-red-700">nổi bật</span>
            </h2>
            <p class="text-lg text-gray-600 font-open-sans max-w-3xl mx-auto leading-relaxed">
                Khám phá những sản phẩm chất lượng cao được khách hàng tin dùng và đánh giá cao nhất tại Vũ Phúc Baking
            </p>
        </div>

        <!-- MOBILE VERSION - Hiển thị chỉ trên thiết bị di động (dưới md) -->
        <div class="md:hidden">
            @if($productsCount <= 4)
            <!-- Grid layout cho 1-4 sản phẩm trên mobile -->
            <div class="grid grid-cols-2 gap-4">
                @foreach($products as $product)
                    <div class="bg-white rounded-3xl shadow-xl overflow-hidden transition-all duration-300 hover:shadow-2xl hover:-translate-y-2 group">
                        <a href="{{ route('products.show', $product->slug) }}" class="block relative h-40 overflow-hidden">
                            @if(getProductImageUrl($product))
                                <img src="{{ getProductImageUrl($product) }}" alt="{{ $product->seo_title ?? $product->name }}" class="object-cover w-full h-full transition-transform duration-700 group-hover:scale-105">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-red-50 to-red-100 relative overflow-hidden">
                                    <!-- Background pattern -->
                                    <div class="absolute inset-0 opacity-10">
                                        <div class="absolute top-2 left-2 w-2 h-2 bg-red-200 rounded-full"></div>
                                        <div class="absolute top-4 right-3 w-1 h-1 bg-red-200 rounded-full"></div>
                                        <div class="absolute bottom-3 left-4 w-1 h-1 bg-red-200 rounded-full"></div>
                                        <div class="absolute bottom-2 right-2 w-2 h-2 bg-red-200 rounded-full"></div>
                                    </div>

                                    <!-- Main icon -->
                                    <div class="relative z-10 text-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-red-300 mx-auto mb-1" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2C13.1 2 14 2.9 14 4C14 5.1 13.1 6 12 6C10.9 6 10 5.1 10 4C10 2.9 10.9 2 12 2ZM21 9V7L15 1H9L3 7V9H21ZM12 19C10.9 19 10 18.1 10 17C10 15.9 10.9 15 12 15C13.1 15 14 15.9 14 17C14 18.1 13.1 19 12 19ZM12 13C9.8 13 8 14.8 8 17C8 19.2 9.8 21 12 21C14.2 21 16 19.2 16 17C16 14.8 14.2 13 12 13ZM5 11C3.9 11 3 11.9 3 13C3 14.1 3.9 15 5 15C6.1 15 7 14.1 7 13C7 11.9 6.1 11 5 11ZM19 11C17.9 11 17 11.9 17 13C17 14.1 17.9 15 19 15C20.1 15 21 14.1 21 13C21 11.9 20.1 11 19 11Z"/>
                                        </svg>
                                        <p class="text-xs text-red-400 font-medium">{{ Str::limit($product->name, 12) }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Product badges -->
                            <div class="absolute top-3 left-3 flex flex-col gap-1">
                                @if($product->hasDiscount())
                                    <span class="bg-red-600 text-white px-2 py-1 rounded-full text-xs font-bold">
                                        -{{ $product->getDiscountPercentage() }}%
                                    </span>
                                @endif
                                @if($product->is_hot)
                                    <span class="bg-yellow-500 text-white px-2 py-1 rounded-full text-xs font-bold">
                                        HOT
                                    </span>
                                @endif
                            </div>
                        </a>

                        <div class="p-4">
                            @if($product->category)
                                <span class="text-xs text-red-600 bg-red-50 px-2 py-1 rounded-full font-medium">{{ $product->category->name }}</span>
                            @endif
                            <h3 class="font-bold text-sm text-gray-900 mb-3 line-clamp-2 group-hover:text-red-600 transition-colors font-montserrat mt-2">
                                <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
                            </h3>

                            <!-- Price and Action -->
                            <div class="flex justify-between items-center">
                                <div>
                                    @if($product->price && $product->price > 0)
                                        @if($product->hasDiscount())
                                            <div class="flex flex-col">
                                                <span class="text-red-700 font-bold text-sm font-montserrat">{{ formatPrice($product->getCurrentPrice()) }}</span>
                                                <span class="text-gray-400 line-through text-xs font-open-sans">{{ formatPrice($product->price) }}</span>
                                            </div>
                                        @else
                                            <span class="text-red-700 font-bold text-sm font-montserrat">{{ formatPrice($product->price) }}</span>
                                        @endif
                                    @endif
                                </div>

                                <a href="{{ route('products.show', $product->slug) }}" class="inline-flex items-center text-xs text-red-700 font-medium hover:text-red-800 transition-colors">
                                    <span class="font-open-sans">Chi tiết</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-1 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @else
            <!-- Carousel cho 5+ sản phẩm trên mobile -->
            <div class="products-mobile-container overflow-hidden relative">
                <div class="swiper-container products-swiper">
                    <div class="swiper-wrapper">
                        @foreach($products as $product)
                            <div class="swiper-slide">
                                <div class="bg-white rounded-lg shadow-sm overflow-hidden mx-1">
                                    <a href="{{ route('products.show', $product->slug) }}" class="block relative h-40 overflow-hidden">
                                        @if(getProductImageUrl($product))
                                            <img src="{{ getProductImageUrl($product) }}" alt="{{ $product->seo_title ?? $product->name }}" class="object-cover w-full h-full">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-red-50 to-red-100">
                                                <div class="text-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-14 h-14 text-red-300 mx-auto mb-2" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M12 2C13.1 2 14 2.9 14 4C14 5.1 13.1 6 12 6C10.9 6 10 5.1 10 4C10 2.9 10.9 2 12 2ZM21 9V7L15 1H9L3 7V9H21ZM12 19C10.9 19 10 18.1 10 17C10 15.9 10.9 15 12 15C13.1 15 14 15.9 14 17C14 18.1 13.1 19 12 19ZM12 13C9.8 13 8 14.8 8 17C8 19.2 9.8 21 12 21C14.2 21 16 19.2 16 17C16 14.8 14.2 13 12 13ZM5 11C3.9 11 3 11.9 3 13C3 14.1 3.9 15 5 15C6.1 15 7 14.1 7 13C7 11.9 6.1 11 5 11ZM19 11C17.9 11 17 11.9 17 13C17 14.1 17.9 15 19 15C20.1 15 21 14.1 21 13C21 11.9 20.1 11 19 11Z"/>
                                                    </svg>
                                                    <p class="text-xs text-red-400 font-medium">Vũ Phúc Baking</p>
                                                </div>
                                            </div>
                                        @endif

                                        @if($product->hasDiscount())
                                            <div class="absolute top-1 right-1 bg-red-600 text-white px-1.5 py-0.5 rounded text-xs font-medium">
                                                -{{ $product->getDiscountPercentage() }}%
                                            </div>
                                        @endif
                                    </a>
                                    <div class="p-3">
                                        <h3 class="font-medium text-sm text-gray-900 mb-1 line-clamp-2 h-10">
                                            <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
                                        </h3>
                                        <div class="flex justify-between items-center mt-2">
                                            <div>
                                                @if($product->price && $product->price > 0)
                                                    @if($product->hasDiscount())
                                                        <span class="text-red-600 font-medium text-sm">{{ number_format($product->getCurrentPrice()) }}đ</span>
                                                        <span class="text-gray-400 line-through text-xs ml-1">{{ number_format($product->price) }}đ</span>
                                                    @else
                                                        <span class="text-red-600 font-medium text-sm">{{ number_format($product->price) }}đ</span>
                                                    @endif
                                                @endif
                                            </div>
                                            <a href="{{ route('products.show', $product->slug) }}" class="inline-block text-xs text-white bg-red-600 hover:bg-red-700 px-2 py-1 rounded transition-colors">
                                                Xem chi tiết
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Điều hướng carousel mobile -->
                <div class="flex items-center justify-center mt-4 space-x-2">
                    <button class="products-prev-mobile w-8 h-8 flex items-center justify-center rounded-full bg-white shadow text-gray-500 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <div class="swiper-pagination products-pagination"></div>
                    <button class="products-next-mobile w-8 h-8 flex items-center justify-center rounded-full bg-white shadow text-gray-500 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>
            @endif

            <!-- Nút xem thêm cho mobile -->
            <div class="text-center mt-8">
                <a href="{{ route('products.categories') }}" class="group inline-flex items-center justify-center px-8 py-4 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-2xl transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl">
                    <span class="font-montserrat">Xem tất cả sản phẩm</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>
        </div>

        <!-- DESKTOP VERSION - Hiển thị chỉ từ md trở lên -->
        <div class="hidden md:block">
            @if($productsCount <= 8)
            <!-- Grid layout cho 1-8 sản phẩm trên desktop -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @foreach($products as $product)
                    <div class="bg-white rounded-3xl shadow-xl overflow-hidden transition-all hover:-translate-y-2 hover:shadow-2xl duration-300 group">
                        <a href="{{ route('products.show', $product->slug) }}" class="block relative h-64 overflow-hidden">
                            @if(getProductImageUrl($product))
                                <img src="{{ getProductImageUrl($product) }}" alt="{{ $product->seo_title ?? $product->name }}" class="object-cover w-full h-full transition-transform hover:scale-105 duration-700">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-red-50 to-red-100 relative overflow-hidden">
                                    <!-- Background pattern -->
                                    <div class="absolute inset-0 opacity-10">
                                        <div class="absolute top-4 left-4 w-3 h-3 bg-red-200 rounded-full"></div>
                                        <div class="absolute top-8 right-6 w-2 h-2 bg-red-200 rounded-full"></div>
                                        <div class="absolute bottom-6 left-8 w-2 h-2 bg-red-200 rounded-full"></div>
                                        <div class="absolute bottom-4 right-4 w-3 h-3 bg-red-200 rounded-full"></div>
                                    </div>

                                    <!-- Main icon -->
                                    <div class="relative z-10 text-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-20 h-20 text-red-300 mx-auto mb-3" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2C13.1 2 14 2.9 14 4C14 5.1 13.1 6 12 6C10.9 6 10 5.1 10 4C10 2.9 10.9 2 12 2ZM21 9V7L15 1H9L3 7V9H21ZM12 19C10.9 19 10 18.1 10 17C10 15.9 10.9 15 12 15C13.1 15 14 15.9 14 17C14 18.1 13.1 19 12 19ZM12 13C9.8 13 8 14.8 8 17C8 19.2 9.8 21 12 21C14.2 21 16 19.2 16 17C16 14.8 14.2 13 12 13ZM5 11C3.9 11 3 11.9 3 13C3 14.1 3.9 15 5 15C6.1 15 7 14.1 7 13C7 11.9 6.1 11 5 11ZM19 11C17.9 11 17 11.9 17 13C17 14.1 17.9 15 19 15C20.1 15 21 14.1 21 13C21 11.9 20.1 11 19 11Z"/>
                                        </svg>
                                        <p class="text-sm text-red-400 font-medium">{{ Str::limit($product->name, 20) }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Product badges -->
                            <div class="absolute top-4 left-4 flex flex-col gap-2">
                                @if($product->hasDiscount())
                                    <span class="bg-red-600 text-white px-3 py-1 rounded-full text-xs font-bold">
                                        -{{ $product->getDiscountPercentage() }}%
                                    </span>
                                @endif
                                @if($product->is_hot)
                                    <span class="bg-yellow-500 text-white px-3 py-1 rounded-full text-xs font-bold">
                                        HOT
                                    </span>
                                @endif
                            </div>
                        </a>

                        <div class="p-6">
                            @if($product->category)
                                <span class="text-sm text-red-600 bg-red-50 px-3 py-1 rounded-full font-medium">{{ $product->category->name }}</span>
                            @endif
                            <h3 class="font-bold text-lg text-gray-900 mb-4 line-clamp-2 group-hover:text-red-600 transition-colors font-montserrat mt-3">
                                <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
                            </h3>

                            <!-- Price and Action -->
                            <div class="flex justify-between items-center">
                                <div>
                                    @if($product->price && $product->price > 0)
                                        @if($product->hasDiscount())
                                            <div class="flex flex-col">
                                                <span class="text-red-700 font-bold text-xl font-montserrat">{{ formatPrice($product->getCurrentPrice()) }}</span>
                                                <span class="text-gray-400 line-through text-sm font-open-sans">{{ formatPrice($product->price) }}</span>
                                            </div>
                                        @else
                                            <span class="text-red-700 font-bold text-xl font-montserrat">{{ formatPrice($product->price) }}</span>
                                        @endif
                                    @endif
                                </div>

                                <a href="{{ route('products.show', $product->slug) }}" class="inline-flex items-center text-sm text-red-700 font-medium hover:text-red-800 transition-colors">
                                    <span class="font-open-sans">Chi tiết</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @else
            <!-- Carousel cho 9+ sản phẩm trên desktop -->
            <div class="swiper-container desktop-products-swiper overflow-hidden">
                <div class="swiper-wrapper">
                    @foreach($products as $product)
                        <div class="swiper-slide">
                            <div class="bg-white rounded-lg shadow-sm overflow-hidden transition-all hover:-translate-y-1 hover:shadow-md duration-300 h-full mx-1.5">
                                <a href="{{ route('products.show', $product->slug) }}" class="block relative h-56 lg:h-64 overflow-hidden">
                                    @if(getProductImageUrl($product))
                                        <img src="{{ getProductImageUrl($product) }}" alt="{{ $product->seo_title ?? $product->name }}" class="object-cover w-full h-full transition-transform hover:scale-105 duration-500">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-red-50 to-red-100">
                                            <div class="text-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-20 h-20 text-red-300 mx-auto mb-3" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 2C13.1 2 14 2.9 14 4C14 5.1 13.1 6 12 6C10.9 6 10 5.1 10 4C10 2.9 10.9 2 12 2ZM21 9V7L15 1H9L3 7V9H21ZM12 19C10.9 19 10 18.1 10 17C10 15.9 10.9 15 12 15C13.1 15 14 15.9 14 17C14 18.1 13.1 19 12 19ZM12 13C9.8 13 8 14.8 8 17C8 19.2 9.8 21 12 21C14.2 21 16 19.2 16 17C16 14.8 14.2 13 12 13ZM5 11C3.9 11 3 11.9 3 13C3 14.1 3.9 15 5 15C6.1 15 7 14.1 7 13C7 11.9 6.1 11 5 11ZM19 11C17.9 11 17 11.9 17 13C17 14.1 17.9 15 19 15C20.1 15 21 14.1 21 13C21 11.9 20.1 11 19 11Z"/>
                                                </svg>
                                                <p class="text-sm text-red-400 font-medium">Vũ Phúc Baking</p>
                                            </div>
                                        </div>
                                    @endif

                                    @if($product->hasDiscount())
                                        <div class="absolute top-3 right-3 bg-red-600 text-white px-2 py-1 rounded-md text-xs font-medium">
                                            -{{ $product->getDiscountPercentage() }}%
                                        </div>
                                    @endif
                                </a>
                                <div class="p-5">
                                    <div class="mb-2">
                                        @if($product->category)
                                            <span class="text-sm text-gray-500">{{ $product->category->name }}</span>
                                        @endif
                                    </div>
                                    <h3 class="font-medium text-lg text-gray-900 mb-3 line-clamp-2 h-14">
                                        <a href="{{ route('products.show', $product->slug) }}" class="hover:text-red-700">{{ $product->name }}</a>
                                    </h3>
                                    <div class="flex justify-between items-center mt-4">
                                        <div>
                                            @if($product->price && $product->price > 0)
                                                @if($product->hasDiscount())
                                                    <span class="text-red-600 font-bold text-lg">{{ number_format($product->getCurrentPrice()) }}đ</span>
                                                    <span class="text-gray-400 line-through text-base ml-2">{{ number_format($product->price) }}đ</span>
                                                @else
                                                    <span class="text-red-600 font-bold text-lg">{{ number_format($product->price) }}đ</span>
                                                @endif
                                            @endif
                                        </div>
                                        <a href="{{ route('products.show', $product->slug) }}" class="inline-block text-sm text-white bg-red-600 hover:bg-red-700 px-3 py-1 rounded-md transition-colors" title="Xem chi tiết">
                                            Xem chi tiết
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Điều hướng carousel desktop -->
                <div class="flex items-center justify-center mt-8 space-x-4">
                    <button class="products-prev-desktop w-12 h-12 flex items-center justify-center rounded-full bg-white shadow text-gray-700 hover:text-red-700 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <div class="swiper-pagination desktop-products-pagination"></div>
                    <button class="products-next-desktop w-12 h-12 flex items-center justify-center rounded-full bg-white shadow text-gray-700 hover:text-red-700 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>
            @endif

            <!-- Nút xem thêm desktop -->
            <div class="text-center mt-16">
                <a href="{{ route('products.categories') }}" class="group inline-flex items-center px-10 py-4 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-2xl transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl">
                    <span class="font-montserrat">Xem tất cả sản phẩm</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-3 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Khởi tạo Swiper cho mobile (5+ sản phẩm)
        if (document.querySelector('.products-swiper')) {
            const productsSwiper = new Swiper('.products-swiper', {
                slidesPerView: 1.5,
                spaceBetween: 10,
                centeredSlides: false,
                grabCursor: true,
                pagination: {
                    el: '.products-pagination',
                    clickable: true,
                    dynamicBullets: true,
                },
                navigation: {
                    nextEl: '.products-next-mobile',
                    prevEl: '.products-prev-mobile',
                },
                breakpoints: {
                    360: {
                        slidesPerView: 1.6,
                    },
                    480: {
                        slidesPerView: 2.2,
                    },
                    640: {
                        slidesPerView: 3,
                    }
                }
            });
        }

        // Khởi tạo Swiper cho desktop (9+ sản phẩm)
        if (document.querySelector('.desktop-products-swiper')) {
            const desktopProductsSwiper = new Swiper('.desktop-products-swiper', {
                slidesPerView: 3,
                spaceBetween: 24,
                grabCursor: true,
                pagination: {
                    el: '.desktop-products-pagination',
                    clickable: true,
                    dynamicBullets: true,
                },
                navigation: {
                    nextEl: '.products-next-desktop',
                    prevEl: '.products-prev-desktop',
                },
                breakpoints: {
                    768: {
                        slidesPerView: 2,
                        spaceBetween: 20
                    },
                    992: {
                        slidesPerView: 3,
                        spaceBetween: 24
                    },
                    1200: {
                        slidesPerView: 4,
                        spaceBetween: 24
                    }
                }
            });
        }
    });
</script>
@endpush
@endif