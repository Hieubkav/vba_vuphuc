<div class="space-y-6">
    <!-- Search and Filters -->
    <div class="bg-white rounded-xl shadow-lg border border-red-100 overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4">
            <h2 class="text-xl font-bold text-white flex items-center">
                <i class="fas fa-graduation-cap mr-3"></i>
                Tìm kiếm khóa học
            </h2>
        </div>

        <!-- Filters -->
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Search -->
                <div class="lg:col-span-1">
                    <label for="search" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-search mr-1 text-red-500"></i>
                        Tìm kiếm
                    </label>
                    <div class="relative">
                        <input type="text"
                               id="search"
                               wire:model.live.debounce.300ms="search"
                               class="w-full pl-10 pr-4 py-3 border border-red-200 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
                               placeholder="Tên khóa học, giảng viên...">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-red-400"></i>
                        </div>
                        @if($search)
                        <button wire:click="$set('search', '')"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i class="fas fa-times text-red-400 hover:text-red-600 transition-colors"></i>
                        </button>
                        @endif
                    </div>
                </div>

                <!-- Category Filter -->
                <div>
                    <label for="category" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-folder mr-1 text-red-500"></i>
                        Danh mục
                    </label>
                    <select wire:model.live="category"
                            id="category"
                            class="w-full px-4 py-3 border border-red-200 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors">
                        <option value="">Tất cả danh mục</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->slug }}">{{ $cat->name }} ({{ $cat->courses_count ?? 0 }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Level Filter -->
                <div>
                    <label for="level" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-layer-group mr-1 text-red-500"></i>
                        Cấp độ
                    </label>
                    <select wire:model.live="level"
                            id="level"
                            class="w-full px-4 py-3 border border-red-200 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors">
                        <option value="">Tất cả cấp độ</option>
                        <option value="beginner">Cơ bản</option>
                        <option value="intermediate">Trung cấp</option>
                        <option value="advanced">Nâng cao</option>
                    </select>
                </div>
            </div>

            <!-- Sort and Clear -->
            <div class="mt-6 pt-6 border-t border-red-100">
                <div class="flex flex-col sm:flex-row gap-4">
                    <!-- Sort -->
                    <div class="flex-1">
                        <label for="sort" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-sort mr-1 text-red-500"></i>
                            Sắp xếp theo
                        </label>
                        <select wire:model.live="sort"
                                id="sort"
                                class="w-full px-4 py-3 border border-red-200 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors">
                            <option value="order">Mặc định</option>
                            <option value="newest">Mới nhất</option>
                            <option value="price_asc">Giá thấp đến cao</option>
                            <option value="price_desc">Giá cao đến thấp</option>
                            <option value="popular">Phổ biến nhất</option>
                        </select>
                    </div>

                    <!-- Clear Filters -->
                    <div class="flex items-end">
                        <button wire:click="clearFilters"
                                class="px-6 py-3 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors font-medium">
                            <i class="fas fa-times mr-2"></i>
                            Xóa bộ lọc
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Results Summary -->
    <div class="bg-white rounded-xl shadow-md border border-red-100 p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-list-ul mr-3 text-red-500"></i>
                    {{ $courses->total() }} khóa học
                </h2>
                @if($search || $category || $level)
                <p class="text-red-600 mt-1 font-medium">
                    <i class="fas fa-filter mr-1"></i>
                    Kết quả tìm kiếm với bộ lọc đã chọn
                </p>
                @endif
            </div>

            <!-- View Toggle -->
            <div class="flex items-center space-x-3 bg-red-50 px-4 py-2 rounded-lg">
                <span class="text-sm font-medium text-red-700">Hiển thị:</span>
                <select wire:model.live="perPage"
                        class="px-3 py-2 border border-red-200 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm">
                    <option value="12">12</option>
                    <option value="24">24</option>
                    <option value="36">36</option>
                    <option value="48">48</option>
                </select>
                <span class="text-sm font-medium text-red-700">khóa học</span>
            </div>
        </div>
    </div>

    <!-- Loading State -->
    <div wire:loading.delay class="text-center py-8">
        <div class="inline-flex items-center px-6 py-3 bg-red-100 text-red-800 rounded-xl shadow-md">
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="font-medium">Đang tải khóa học...</span>
        </div>
    </div>

    <!-- Courses Grid -->
    @if($courses->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" wire:loading.remove>
            @foreach($courses as $course)
            @include('components.storefront.course-card', ['course' => $course])
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="flex justify-center mt-12">
            {{ $courses->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-16" wire:loading.remove>
            <div class="max-w-md mx-auto bg-white rounded-xl shadow-lg border border-red-100 p-8">
                <div class="w-24 h-24 mx-auto mb-6 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-search text-3xl text-red-400"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">
                    Không tìm thấy khóa học nào
                </h3>
                <p class="text-gray-600 mb-6">
                    Thử thay đổi bộ lọc hoặc từ khóa tìm kiếm để xem thêm kết quả
                </p>
                <button wire:click="clearFilters"
                        class="inline-flex items-center px-6 py-3 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors shadow-md">
                    <i class="fas fa-refresh mr-2"></i>
                    Xóa bộ lọc
                </button>
            </div>
        </div>
    @endif

    <!-- Quick Stats -->
    @if(isset($stats) && $stats['total'] > 0)
    <div class="bg-white rounded-xl shadow-lg border border-red-100 overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4">
            <h3 class="text-lg font-bold text-white flex items-center">
                <i class="fas fa-chart-bar mr-3"></i>
                Thống kê khóa học
            </h3>
        </div>

        <!-- Stats -->
        <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="text-center bg-red-50 rounded-lg p-4">
                    <div class="text-3xl font-bold text-red-600 mb-1">{{ $stats['total'] }}</div>
                    <div class="text-sm font-medium text-red-700">Tổng khóa học</div>
                </div>
                @if(isset($stats['levels']))
                @foreach($stats['levels'] as $level => $count)
                <div class="text-center bg-gray-50 rounded-lg p-4">
                    <div class="text-3xl font-bold text-gray-700 mb-1">{{ $count }}</div>
                    <div class="text-sm font-medium text-gray-600">
                        {{ $level === 'beginner' ? 'Cơ bản' : ($level === 'intermediate' ? 'Trung cấp' : 'Nâng cao') }}
                    </div>
                </div>
                @endforeach
                @endif
            </div>
        </div>
    </div>
    @endif
</div>
