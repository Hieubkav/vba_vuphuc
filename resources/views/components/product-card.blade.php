@props(['product'])
@php $image=collect($product->product_images??[])->where('status','active')->sortBy('order')->first();$hasDiscount=isset($product->compare_price)&&$product->compare_price>$product->price; @endphp
<article class="group">
    <a href="{{ route('products.show', $product->slug) }}" class="block">
        <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="aspect-square overflow-hidden relative">
                @if($image && isset($image['image_link']))
                    <img src="{{ asset('storage/' . $image['image_link']) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                @else
                    <div class="w-full h-full bg-gradient-to-br from-red-50 to-red-100 flex items-center justify-center"><i class="fas fa-birthday-cake text-4xl text-red-300"></i></div>
                @endif
                @if($product->is_hot)<span class="absolute top-2 left-2 bg-gradient-to-r from-orange-400 to-orange-500 text-white text-xs px-2 py-1 rounded-full font-bold shadow-lg">HOT</span>@endif
                @if($hasDiscount)<div class="absolute top-2 right-2 bg-gradient-to-r from-red-500 to-red-600 text-white px-2 py-1 rounded-full text-xs font-bold shadow-lg">-{{ round((($product->compare_price - $product->price) / $product->compare_price) * 100) }}%</div>@endif
            </div>
            <div class="p-4">
                @if(isset($product->category) && $product->category)<span class="text-xs text-red-500 font-medium uppercase tracking-wide mb-1 block">{{ is_object($product->category) ? $product->category->name : $product->category['name'] }}</span>@endif
                <h3 class="text-sm md:text-base font-semibold text-gray-900 group-hover:text-red-700 transition-colors line-clamp-2 mb-3 font-montserrat">{{ $product->name }}</h3>
                <div class="flex items-center justify-between">
                    <div>
                        @if($hasDiscount)
                            <div class="flex flex-col"><span class="text-red-600 font-bold text-sm md:text-base">{{ number_format($product->price, 0, ',', '.') }}đ</span><span class="text-gray-400 line-through text-xs">{{ number_format($product->compare_price, 0, ',', '.') }}đ</span></div>
                        @else
                            <span class="text-red-600 font-bold text-sm md:text-base">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                        @endif
                    </div>
                    <span class="inline-flex items-center justify-center rounded-full bg-gradient-to-r from-red-50 to-red-100 px-3 py-1.5 text-xs font-medium text-red-700 group-hover:from-red-100 group-hover:to-red-200 transition-all">Chi tiết <i class="fas fa-arrow-right ml-1"></i></span>
                </div>
            </div>
        </div>
    </a>
</article>
