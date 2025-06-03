@extends('layouts.shop')

@section('title', $product->seo_title ?? $product->name . ' - Vũ Phúc Baking')
@section('description', $product->seo_description ?? Str::limit(strip_tags($product->description), 160))

@push('styles')
<style>
    .hero-pattern {
        background-image:
            radial-gradient(circle at 25% 25%, rgba(255,255,255,0.1) 2px, transparent 2px),
            radial-gradient(circle at 75% 75%, rgba(255,255,255,0.1) 2px, transparent 2px);
        background-size: 50px 50px;
    }

    .product-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    .image-gallery {
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.95);
    }

    .prose h1, .prose h2, .prose h3, .prose h4, .prose h5, .prose h6 {
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        color: #1f2937;
        margin-top: 2rem;
        margin-bottom: 1rem;
    }

    .prose p, .prose li {
        font-family: 'Open Sans', sans-serif;
        line-height: 1.75;
        color: #374151;
    }
</style>
@endpush

@section('content')
<div class="bg-white py-8 md:py-12">
    <div class="container mx-auto px-4">
        <!-- Breadcrumb -->
        <div class="mb-8">
            <nav class="flex items-center space-x-2 text-sm text-gray-500">
                <a href="{{ route('storeFront') }}" class="hover:text-red-700">Trang chủ</a>
                <span class="mx-2">/</span>
                <a href="{{ route('products.categories') }}" class="hover:text-red-700">Sản phẩm</a>
                @if($product->category)
                    <span class="mx-2">/</span>
                    <a href="{{ route('products.category', $product->category->slug) }}" class="hover:text-red-700">{{ $product->category->name }}</a>
                @endif
                <span class="mx-2">/</span>
                <span>{{ $product->name }}</span>
            </nav>
        <!-- Product Details -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 mb-12">
                <!-- Product Images -->
                <div class="space-y-6">
                    @php
                        $images = $product->productImages->where('status', 'active')->sortBy('order');
                        $mainImage = $images->where('is_main', true)->first() ?? $images->first();
                    @endphp

                    @if($mainImage)
                        <div class="image-gallery rounded-3xl shadow-2xl overflow-hidden group">
                            <div class="aspect-square w-full overflow-hidden">
                                <img id="main-image"
                                     src="{{ getProductImageUrlFromImage($mainImage, $product->name) }}"
                                     alt="{{ getProductImageAlt($mainImage, $product->name, $product->seo_title) }}"
                                     class="w-full h-full object-center object-cover transition-transform duration-700 group-hover:scale-105">
                            </div>
                        </div>

                        @if($images->count() > 1)
                            <div class="grid grid-cols-4 gap-4">
                                @foreach($images as $image)
                                    <button type="button"
                                            class="aspect-square w-full overflow-hidden rounded-2xl bg-gray-100 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1"
                                            onclick="changeMainImage('{{ getProductImageUrlFromImage($image, $product->name) }}', '{{ getProductImageAlt($image, $product->name, $product->seo_title) }}')">
                                        <img src="{{ getProductImageUrlFromImage($image, $product->name) }}"
                                             alt="{{ getProductImageAlt($image, $product->name, $product->seo_title) }}"
                                             class="w-full h-full object-center object-cover">
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    @else
                        <div class="aspect-square w-full overflow-hidden rounded-3xl bg-gradient-to-br from-red-50 to-red-100 flex items-center justify-center shadow-2xl">
                            <div class="text-center relative">
                                <!-- Background pattern -->
                                <div class="absolute inset-0 opacity-10">
                                    <div class="absolute top-4 left-4 w-3 h-3 bg-red-200 rounded-full"></div>
                                    <div class="absolute top-8 right-6 w-2 h-2 bg-red-200 rounded-full"></div>
                                    <div class="absolute bottom-6 left-8 w-2 h-2 bg-red-200 rounded-full"></div>
                                    <div class="absolute bottom-4 right-4 w-3 h-3 bg-red-200 rounded-full"></div>
                                </div>

                                <!-- Main icon -->
                                <div class="relative z-10">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-32 h-32 text-red-300 mx-auto mb-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2C13.1 2 14 2.9 14 4C14 5.1 13.1 6 12 6C10.9 6 10 5.1 10 4C10 2.9 10.9 2 12 2ZM21 9V7L15 1H9L3 7V9H21ZM12 19C10.9 19 10 18.1 10 17C10 15.9 10.9 15 12 15C13.1 15 14 15.9 14 17C14 18.1 13.1 19 12 19ZM12 13C9.8 13 8 14.8 8 17C8 19.2 9.8 21 12 21C14.2 21 16 19.2 16 17C16 14.8 14.2 13 12 13ZM5 11C3.9 11 3 11.9 3 13C3 14.1 3.9 15 5 15C6.1 15 7 14.1 7 13C7 11.9 6.1 11 5 11ZM19 11C17.9 11 17 11.9 17 13C17 14.1 17.9 15 19 15C20.1 15 21 14.1 21 13C21 11.9 20.1 11 19 11Z"/>
                                    </svg>
                                    <p class="text-xl text-red-400 font-medium font-montserrat">{{ $product->name }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Product Info -->
                <div class="space-y-8">
                    <!-- Product Header -->
                    <div class="bg-white rounded-3xl shadow-xl p-8">
                        <div class="mb-6">
                            @if($product->category)
                                <span class="inline-block px-4 py-2 text-sm font-semibold bg-red-100 text-red-800 rounded-full mb-4">
                                    {{ $product->category->name }}
                                </span>
                            @endif
                            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 font-montserrat leading-tight">
                                {{ $product->name }}
                            </h2>
                        </div>

                        <!-- Product Meta -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            @if($product->brand)
                                <div class="flex items-center p-4 bg-gray-50 rounded-2xl">
                                    <svg class="w-5 h-5 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-xs text-gray-500 font-open-sans">Thương hiệu</p>
                                        <p class="font-semibold text-gray-900">{{ $product->brand }}</p>
                                    </div>
                                </div>
                            @endif

                            @if($product->sku)
                                <div class="flex items-center p-4 bg-gray-50 rounded-2xl">
                                    <svg class="w-5 h-5 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                    </svg>
                                    <div>
                                        <p class="text-xs text-gray-500 font-open-sans">Mã sản phẩm</p>
                                        <p class="font-semibold text-gray-900">{{ $product->sku }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Price Section -->
                        <div class="bg-gradient-to-r from-red-50 to-red-100 rounded-2xl p-6 border border-red-200">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-bold text-gray-900 font-montserrat">Giá sản phẩm</h3>
                                @if($product->is_hot)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                        Nổi bật
                                    </span>
                                @endif
                            </div>

                            <div class="flex items-center space-x-4">
                                @if($product->hasDiscount())
                                    <span class="text-4xl font-bold text-red-700 font-montserrat">{{ formatPrice($product->getCurrentPrice()) }}</span>
                                    <span class="text-xl text-gray-400 line-through font-open-sans">{{ formatPrice($product->price) }}</span>
                                    <span class="bg-red-600 text-white text-sm font-bold px-3 py-1 rounded-full">
                                        -{{ $product->getDiscountPercentage() }}%
                                    </span>
                                @else
                                    <span class="text-4xl font-bold text-red-700 font-montserrat">{{ formatPrice($product->price) }}</span>
                                @endif
                            </div>

                            @if($product->unit)
                                <p class="text-sm text-gray-600 mt-3 font-open-sans">
                                    <span class="font-medium">Đơn vị:</span> {{ $product->unit }}
                                </p>
                            @endif
                        </div>
                    </div>

                <!-- Stock Status -->
                @if($product->stock !== null)
                    <div class="flex items-center space-x-2">
                        @if($product->stock > 0)
                            <span class="flex items-center text-green-600">
                                <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Còn hàng ({{ $product->stock }} sản phẩm)
                            </span>
                        @else
                            <span class="flex items-center text-red-600">
                                <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                Hết hàng
                            </span>
                        @endif
                    </div>
                @endif

                <!-- Hot Product Badge -->
                @if($product->is_hot)
                    <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        Sản phẩm nổi bật
                    </div>
                @endif

                <!-- Contact for Price -->
                <div class="space-y-4">
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Liên hệ để đặt hàng</h3>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-red-700 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                                </svg>
                                <span class="text-gray-700">{{ $globalSettings->phone ?? '0123 456 789' }}</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-red-700 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                </svg>
                                <span class="text-gray-700">{{ $globalSettings->email ?? 'info@vuphucbaking.com' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Description -->
        @if($product->description)
            <div class="border-t border-gray-200 pt-8 mb-12">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Mô tả sản phẩm</h2>
                <div class="prose prose-red max-w-none">
                    {!! $product->description !!}
                </div>
            </div>
        @endif

        <!-- Related Products -->
        @if(isset($relatedProducts) && $relatedProducts->count() > 0)
            <div class="border-t border-gray-200 pt-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Sản phẩm liên quan</h2>
                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                    @foreach($relatedProducts as $relatedProduct)
                        <a href="{{ route('products.show', $relatedProduct->slug) }}" class="group">
                            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden h-full flex flex-col transition-all duration-300 group-hover:shadow-md">
                                <div class="aspect-w-1 aspect-h-1 w-full overflow-hidden bg-gray-100">
                                    @php
                                        $image = $relatedProduct->productImages->first();
                                    @endphp
                                    @if($image)
                                        <img src="{{ getProductImageUrlFromImage($image, $relatedProduct->name) }}"
                                            alt="{{ getProductImageAlt($image, $relatedProduct->name, $relatedProduct->seo_title) }}"
                                            class="w-full h-full object-center object-cover transform transition-transform duration-300 group-hover:scale-105">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-red-50 to-red-100">
                                            <div class="text-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-red-300 mx-auto mb-2" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 2C13.1 2 14 2.9 14 4C14 5.1 13.1 6 12 6C10.9 6 10 5.1 10 4C10 2.9 10.9 2 12 2ZM21 9V7L15 1H9L3 7V9H21ZM12 19C10.9 19 10 18.1 10 17C10 15.9 10.9 15 12 15C13.1 15 14 15.9 14 17C14 18.1 13.1 19 12 19ZM12 13C9.8 13 8 14.8 8 17C8 19.2 9.8 21 12 21C14.2 21 16 19.2 16 17C16 14.8 14.2 13 12 13ZM5 11C3.9 11 3 11.9 3 13C3 14.1 3.9 15 5 15C6.1 15 7 14.1 7 13C7 11.9 6.1 11 5 11ZM19 11C17.9 11 17 11.9 17 13C17 14.1 17.9 15 19 15C20.1 15 21 14.1 21 13C21 11.9 20.1 11 19 11Z"/>
                                                </svg>
                                                <p class="text-xs text-red-400 font-medium">Vũ Phúc Baking</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-4 flex-grow flex flex-col">
                                    <h3 class="text-sm font-medium text-gray-900 group-hover:text-red-700 transition-colors line-clamp-2">{{ $relatedProduct->name }}</h3>

                                    <div class="mt-auto pt-3">
                                        @if($relatedProduct->hasDiscount())
                                            <p class="text-xs text-gray-500 line-through">{{ formatPrice($relatedProduct->price) }}</p>
                                            <p class="text-sm font-semibold text-red-700">{{ formatPrice($relatedProduct->getCurrentPrice()) }}</p>
                                        @else
                                            <p class="text-sm font-semibold text-red-700">{{ formatPrice($relatedProduct->price) }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function changeMainImage(src, alt) {
        const mainImage = document.getElementById('main-image');
        if (mainImage) {
            mainImage.src = src;
            mainImage.alt = alt;
        }
    }
</script>
@endpush
@endsection
