<div class="flex flex-col lg:flex-row gap-8">
    <!-- Sidebar Filters -->
    <aside class="lg:w-80 flex-shrink-0">
        <!-- Mobile Filter Toggle -->
        <div class="lg:hidden mb-6">
            <button onclick="toggleSidebar()"
                    class="w-full flex items-center justify-center px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                <i class="fas fa-filter mr-2"></i>
                Bộ lọc khóa học
            </button>
        </div>

        <!-- Desktop Filters -->
        <div class="hidden lg:block space-y-6" id="desktop-filter-content">
            <!-- Search -->
            <div class="filter-card rounded-xl p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center font-montserrat">
                    <i class="fas fa-search mr-2 text-red-500"></i>
                    Tìm kiếm
                </h3>
                <div class="relative">
                    <input type="text"
                           wire:model.live.debounce.300ms="search"
                           class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors font-open-sans"
                           placeholder="Tên khóa học, giảng viên...">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    @if($search)
                    <button wire:click="$set('search', '')"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-red-600">
                        <i class="fas fa-times"></i>
                    </button>
                    @endif
                </div>
            </div>

            <!-- Categories -->
            <div class="filter-card rounded-xl p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center font-montserrat">
                    <i class="fas fa-tags mr-2 text-red-500"></i>
                    Danh mục
                </h3>
                <div class="space-y-2">
                    <button wire:click="$set('category', '')"
                            class="w-full text-left px-3 py-2 rounded-lg transition-colors font-open-sans {{ !$category ? 'bg-red-100 text-red-700 font-medium' : 'hover:bg-gray-50' }}">
                        Tất cả danh mục
                    </button>
                    @foreach($categories as $cat)
                    <button wire:click="$set('category', '{{ $cat->slug }}')"
                            class="w-full text-left px-3 py-2 rounded-lg transition-colors font-open-sans flex items-center justify-between {{ $category === $cat->slug ? 'bg-red-100 text-red-700 font-medium' : 'hover:bg-gray-50' }}">
                        <span>{{ $cat->name }}</span>
                        <span class="text-xs bg-gray-200 text-gray-600 px-2 py-1 rounded-full">{{ $cat->courses_count ?? 0 }}</span>
                    </button>
                    @endforeach
                </div>
            </div>

            <!-- Levels -->
            <div class="filter-card rounded-xl p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center font-montserrat">
                    <i class="fas fa-layer-group mr-2 text-red-500"></i>
                    Cấp độ
                </h3>
                <div class="space-y-2">
                    <button wire:click="$set('level', '')"
                            class="w-full text-left px-3 py-2 rounded-lg transition-colors font-open-sans {{ !$level ? 'bg-red-100 text-red-700 font-medium' : 'hover:bg-gray-50' }}">
                        Tất cả cấp độ
                    </button>
                    <button wire:click="$set('level', 'beginner')"
                            class="w-full text-left px-3 py-2 rounded-lg transition-colors font-open-sans {{ $level === 'beginner' ? 'bg-red-100 text-red-700 font-medium' : 'hover:bg-gray-50' }}">
                        Cơ bản
                    </button>
                    <button wire:click="$set('level', 'intermediate')"
                            class="w-full text-left px-3 py-2 rounded-lg transition-colors font-open-sans {{ $level === 'intermediate' ? 'bg-red-100 text-red-700 font-medium' : 'hover:bg-gray-50' }}">
                        Trung cấp
                    </button>
                    <button wire:click="$set('level', 'advanced')"
                            class="w-full text-left px-3 py-2 rounded-lg transition-colors font-open-sans {{ $level === 'advanced' ? 'bg-red-100 text-red-700 font-medium' : 'hover:bg-gray-50' }}">
                        Nâng cao
                    </button>
                </div>
            </div>

            <!-- Sort -->
            <div class="filter-card rounded-xl p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center font-montserrat">
                    <i class="fas fa-sort mr-2 text-red-500"></i>
                    Sắp xếp
                </h3>
                <div class="space-y-2">
                    <button wire:click="$set('sort', 'order')"
                            class="w-full text-left px-3 py-2 rounded-lg transition-colors font-open-sans {{ $sort === 'order' ? 'bg-red-100 text-red-700 font-medium' : 'hover:bg-gray-50' }}">
                        Mặc định
                    </button>
                    <button wire:click="$set('sort', 'newest')"
                            class="w-full text-left px-3 py-2 rounded-lg transition-colors font-open-sans {{ $sort === 'newest' ? 'bg-red-100 text-red-700 font-medium' : 'hover:bg-gray-50' }}">
                        Mới nhất
                    </button>

                    <button wire:click="$set('sort', 'popular')"
                            class="w-full text-left px-3 py-2 rounded-lg transition-colors font-open-sans {{ $sort === 'popular' ? 'bg-red-100 text-red-700 font-medium' : 'hover:bg-gray-50' }}">
                        Phổ biến nhất
                    </button>
                </div>
            </div>

            <!-- Clear Filters -->
            @if($search || $category || $level || $sort !== 'order')
            <div class="filter-card rounded-xl p-6">
                <button wire:click="clearFilters"
                        class="w-full px-4 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium font-open-sans">
                    <i class="fas fa-eraser mr-2"></i>
                    Xóa bộ lọc
                </button>
            </div>
            @endif
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 min-w-0 space-y-6">

        <!-- Results Summary -->
        <div class="bg-white rounded-xl shadow-md border border-red-100 p-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 flex items-center font-montserrat">
                        <i class="fas fa-graduation-cap mr-3 text-red-500"></i>
                        {{ $courses->total() }} khóa học
                    </h2>
                    @if($search || $category || $level)
                    <p class="text-red-600 mt-1 font-medium font-open-sans">
                        <i class="fas fa-filter mr-1"></i>
                        Kết quả tìm kiếm với bộ lọc đã chọn
                    </p>
                    @endif
                </div>

                <!-- View Toggle -->
                <div class="flex items-center space-x-3 bg-red-50 px-4 py-2 rounded-lg">
                    <span class="text-sm font-medium text-red-700 font-open-sans">Hiển thị:</span>
                    <select wire:model.live="perPage"
                            class="px-3 py-2 border border-red-200 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm font-open-sans">
                        <option value="12">12</option>
                        <option value="24">24</option>
                        <option value="36">36</option>
                        <option value="48">48</option>
                    </select>
                    <span class="text-sm font-medium text-red-700 font-open-sans">khóa học</span>
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
                <span class="font-medium font-open-sans">Đang tải khóa học...</span>
            </div>
        </div>

        <!-- Courses Grid -->
        @if($courses->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" wire:loading.remove>
                @foreach($courses as $course)
                <div class="course-card">
                    @include('components.course-card', ['course' => $course])
                </div>
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
                    <h3 class="text-xl font-bold text-gray-900 mb-2 font-montserrat">
                        Không tìm thấy khóa học nào
                    </h3>
                    <p class="text-gray-600 mb-6 font-open-sans">
                        Thử thay đổi bộ lọc hoặc từ khóa tìm kiếm để xem thêm kết quả
                    </p>
                    <button wire:click="clearFilters"
                            class="inline-flex items-center px-6 py-3 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors shadow-md font-open-sans">
                        <i class="fas fa-refresh mr-2"></i>
                        Xóa bộ lọc
                    </button>
                </div>
            </div>
        @endif
    </main>

    <!-- Mobile Sidebar -->
    <div class="mobile-sidebar lg:hidden" onclick="toggleSidebar()">
        <div class="mobile-sidebar-content" onclick="event.stopPropagation()">
            <div class="p-6">
                <!-- Close button -->
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-900 font-montserrat">Bộ lọc</h3>
                    <button onclick="toggleSidebar()" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Mobile Filter Content (will be populated by JavaScript) -->
                <div id="mobile-filter-content"></div>
            </div>
        </div>
    </div>
</div>


