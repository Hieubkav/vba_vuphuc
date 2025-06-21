<div class="min-h-screen bg-gray-50">
    <div class="container mx-auto px-4 py-6">
        <!-- Mobile Filter Button -->
        <div class="lg:hidden mb-6">
            <button @click="$dispatch('toggle-mobile-filter')"
                    class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 flex items-center justify-between text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
                <span class="flex items-center font-medium">
                    <i class="fas fa-filter mr-2 text-red-500"></i>
                    Bộ lọc & Tìm kiếm
                </span>
                <i class="fas fa-chevron-down text-gray-400"></i>
            </button>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Desktop Sidebar Filters -->
            <aside class="hidden lg:block w-80 flex-shrink-0">
                <div class="space-y-6" id="desktop-filter-content">
                    <!-- Search -->
                    <x-filter-card title="Tìm kiếm">
                        <div class="relative">
                            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Nhập tên sản phẩm..." class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-lg focus:ring-1 focus:ring-red-500 focus:border-red-500 font-open-sans text-sm">
                            <i class="fas fa-search absolute left-2.5 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                        </div>
                    </x-filter-card>

                    <!-- Category Filter -->
                    <x-filter-card title="Danh mục">
                        <div class="space-y-1.5">
                            <button wire:click="$set('category', '')" class="block w-full text-left px-3 py-2 rounded-lg font-open-sans text-sm transition-all duration-200 {{ !$category ? 'bg-red-600 text-white' : 'hover:bg-red-50 hover:text-red-600' }}">Tất cả danh mục</button>
                            @foreach($this->categories as $cat)
                                <button wire:click="$set('category', '{{ $cat->id }}')" class="block w-full text-left px-3 py-2 rounded-lg font-open-sans text-sm transition-all duration-200 {{ $category == $cat->id ? 'bg-red-600 text-white' : 'hover:bg-red-50 hover:text-red-600' }}">{{ $cat->name }} <span class="text-gray-400 text-xs">({{ $cat->products_count }})</span></button>
                            @endforeach
                        </div>
                    </x-filter-card>

                    <!-- Price Range -->
                    @if($this->priceRange && $this->priceRange->min_price && $this->priceRange->max_price)
                    <x-filter-card title="Khoảng giá">
                        <div class="grid grid-cols-2 gap-2">
                            <input type="number" wire:model.live.debounce.500ms="minPrice" placeholder="Từ {{ number_format($this->priceRange->min_price, 0, ',', '.') }}" class="px-3 py-2 border border-gray-200 rounded-lg focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm">
                            <input type="number" wire:model.live.debounce.500ms="maxPrice" placeholder="Đến {{ number_format($this->priceRange->max_price, 0, ',', '.') }}" class="px-3 py-2 border border-gray-200 rounded-lg focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm">
                        </div>
                    </x-filter-card>
                    @endif

                    <!-- Special Filters -->
                    <x-filter-card title="Đặc biệt">
                        <div class="space-y-2">
                            @php
                                $specialFilters = [
                                    ['model' => 'isHot', 'label' => 'Sản phẩm nổi bật'],
                                    ['model' => 'hasDiscount', 'label' => 'Đang giảm giá']
                                ];
                            @endphp
                            @foreach($specialFilters as $filter)
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" wire:model.live="{{ $filter['model'] }}" class="sr-only">
                                <div class="w-4 h-4 border-2 border-gray-300 rounded {{ ${$filter['model']} ? 'bg-red-500 border-red-500' : '' }} relative">
                                    @if(${$filter['model']})<i class="fas fa-check absolute inset-0 text-white text-xs flex items-center justify-center"></i>@endif
                                </div>
                                <span class="ml-3 text-sm text-gray-700 font-open-sans">{{ $filter['label'] }}</span>
                            </label>
                            @endforeach
                        </div>
                    </x-filter-card>

                    <!-- Clear Filters -->
                    @if($search || $category || $sort !== 'newest' || $minPrice || $maxPrice || $isHot || $hasDiscount)
                    <x-filter-card title="">
                        <button wire:click="clearFilters" class="block w-full text-center px-3 py-2.5 bg-gray-50 text-gray-600 rounded-lg hover:bg-gray-100 transition-colors font-medium font-open-sans text-sm">
                            <i class="fas fa-redo mr-2"></i>Xóa bộ lọc
                        </button>
                    </x-filter-card>
                    @endif
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1">
                <!-- Active Filters & Results Count -->
                <div class="mb-6">
                    <div class="flex flex-wrap items-center gap-3 mb-4">
                        @if($search)
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm bg-red-100 text-red-800">
                                <i class="fas fa-search mr-1.5"></i>
                                "{{ $search }}"
                                <button wire:click="$set('search', '')" class="ml-2 hover:text-red-600">
                                    <i class="fas fa-times"></i>
                                </button>
                            </span>
                        @endif
                        @if($category)
                            @php $selectedCategory = $this->categories->find($category); @endphp
                            @if($selectedCategory)
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm bg-blue-100 text-blue-800">
                                    <i class="fas fa-tag mr-1.5"></i>
                                    {{ $selectedCategory->name }}
                                    <button wire:click="$set('category', '')" class="ml-2 hover:text-blue-600">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </span>
                            @endif
                        @endif
                        @if($isHot)
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm bg-orange-100 text-orange-800">
                                <i class="fas fa-fire mr-1.5"></i>
                                Nổi bật
                                <button wire:click="$set('isHot', false)" class="ml-2 hover:text-orange-600">
                                    <i class="fas fa-times"></i>
                                </button>
                            </span>
                        @endif
                        @if($hasDiscount)
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm bg-green-100 text-green-800">
                                <i class="fas fa-percent mr-1.5"></i>
                                Giảm giá
                                <button wire:click="$set('hasDiscount', false)" class="ml-2 hover:text-green-600">
                                    <i class="fas fa-times"></i>
                                </button>
                            </span>
                        @endif
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-600 font-open-sans">
                            <span wire:loading.remove>
                                Hiển thị {{ count($this->products) }} sản phẩm
                            </span>
                            <span wire:loading class="flex items-center">
                                <i class="fas fa-spinner fa-spin mr-2"></i>
                                Đang tải...
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <label class="text-sm text-gray-500 font-open-sans">Sắp xếp:</label>
                            <select wire:model.live="sort"
                                    class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 focus:ring-1 focus:ring-red-500 focus:border-red-500 bg-white">
                                @foreach($sortOptions as $sortKey => $sortName)
                                    <option value="{{ $sortKey }}">{{ $sortName }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Products Grid -->
                <div wire:loading.remove>
                    @if($this->products->count() > 0)
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6 mb-16">
                            @foreach($this->products as $product)<x-product-card :product="$product" />@endforeach
                        </div>

                        <!-- Load More Button -->
                        @if($hasMoreProducts)
                            <div class="text-center">
                                <button wire:click="loadMore"
                                        class="inline-flex items-center px-8 py-3 bg-white border border-gray-200 rounded-xl text-gray-700 hover:bg-gray-50 transition-colors font-medium shadow-sm"
                                        wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="loadMore">
                                        <i class="fas fa-plus mr-2"></i>
                                        Xem thêm sản phẩm
                                    </span>
                                    <span wire:loading wire:target="loadMore" class="flex items-center">
                                        <i class="fas fa-spinner fa-spin mr-2"></i>
                                        Đang tải...
                                    </span>
                                </button>
                            </div>
                        @endif
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-16">
                            <div class="max-w-md mx-auto">
                                <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-search text-4xl text-gray-400"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-3 font-montserrat">Không tìm thấy sản phẩm</h3>
                                <p class="text-gray-600 mb-6 font-open-sans">Thử thay đổi bộ lọc hoặc từ khóa tìm kiếm để xem thêm kết quả.</p>
                                <button wire:click="clearFilters"
                                       class="inline-flex items-center px-6 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors font-medium font-open-sans">
                                    <i class="fas fa-redo mr-2"></i>
                                    Xem tất cả sản phẩm
                                </button>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Loading State -->
                <div wire:loading class="text-center py-16">
                    <div class="inline-flex items-center px-6 py-3 bg-white rounded-xl shadow-sm">
                        <i class="fas fa-spinner fa-spin text-red-500 mr-3"></i>
                        <span class="text-gray-700 font-medium">Đang tải sản phẩm...</span>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="{{ asset('css/products-filter.css') }}">
@endpush
