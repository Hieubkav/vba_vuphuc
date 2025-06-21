<div>
<div class="flex flex-col lg:flex-row gap-8">
    <!-- Sidebar Filters -->
    <aside class="lg:w-80 flex-shrink-0">
        <!-- Mobile Filter Toggle -->
        <div class="lg:hidden mb-6">
            <button onclick="toggleSidebar()"
                    class="w-full flex items-center justify-center px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                <i class="fas fa-filter mr-2"></i>
                Bộ lọc nhóm học tập
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
                           placeholder="Tên nhóm, mô tả...">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    @if($search)
                    <button wire:click="$set('search', '')"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <i class="fas fa-times text-gray-400 hover:text-gray-600"></i>
                    </button>
                    @endif
                </div>
            </div>

            <!-- Group Types -->
            <div class="filter-card rounded-xl p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center font-montserrat">
                    <i class="fas fa-layer-group mr-2 text-red-500"></i>
                    Loại nhóm
                </h3>
                <div class="space-y-2">
                    <button wire:click="$set('groupType', '')"
                            class="w-full text-left px-3 py-2 rounded-lg transition-colors font-open-sans {{ !$groupType ? 'bg-red-100 text-red-700 font-medium' : 'hover:bg-gray-50' }}">
                        <div class="flex items-center justify-between">
                            <span>Tất cả loại nhóm</span>
                            <span class="text-xs text-gray-500">{{ $stats['total'] }}</span>
                        </div>
                    </button>
                    <button wire:click="$set('groupType', 'facebook')"
                            class="w-full text-left px-3 py-2 rounded-lg transition-colors font-open-sans {{ $groupType === 'facebook' ? 'bg-red-100 text-red-700 font-medium' : 'hover:bg-gray-50' }}">
                        <div class="flex items-center justify-between">
                            <span><i class="fab fa-facebook mr-2 text-blue-600"></i>Facebook</span>
                            <span class="text-xs text-gray-500">{{ $groupTypes['facebook'] ?? 0 }}</span>
                        </div>
                    </button>
                    <button wire:click="$set('groupType', 'zalo')"
                            class="w-full text-left px-3 py-2 rounded-lg transition-colors font-open-sans {{ $groupType === 'zalo' ? 'bg-red-100 text-red-700 font-medium' : 'hover:bg-gray-50' }}">
                        <div class="flex items-center justify-between">
                            <span><i class="fas fa-comments mr-2 text-blue-500"></i>Zalo</span>
                            <span class="text-xs text-gray-500">{{ $groupTypes['zalo'] ?? 0 }}</span>
                        </div>
                    </button>
                    <button wire:click="$set('groupType', 'telegram')"
                            class="w-full text-left px-3 py-2 rounded-lg transition-colors font-open-sans {{ $groupType === 'telegram' ? 'bg-red-100 text-red-700 font-medium' : 'hover:bg-gray-50' }}">
                        <div class="flex items-center justify-between">
                            <span><i class="fab fa-telegram mr-2 text-blue-400"></i>Telegram</span>
                            <span class="text-xs text-gray-500">{{ $groupTypes['telegram'] ?? 0 }}</span>
                        </div>
                    </button>
                </div>
            </div>

            <!-- Sort Options -->
            <div class="filter-card rounded-xl p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center font-montserrat">
                    <i class="fas fa-sort mr-2 text-red-500"></i>
                    Sắp xếp
                </h3>
                <div class="space-y-2">
                    <button wire:click="$set('sort', 'order')"
                            class="w-full text-left px-3 py-2 rounded-lg transition-colors font-open-sans {{ $sort === 'order' ? 'bg-red-100 text-red-700 font-medium' : 'hover:bg-gray-50' }}">
                        Thứ tự mặc định
                    </button>
                    <button wire:click="$set('sort', 'name')"
                            class="w-full text-left px-3 py-2 rounded-lg transition-colors font-open-sans {{ $sort === 'name' ? 'bg-red-100 text-red-700 font-medium' : 'hover:bg-gray-50' }}">
                        Tên A-Z
                    </button>
                    <button wire:click="$set('sort', 'newest')"
                            class="w-full text-left px-3 py-2 rounded-lg transition-colors font-open-sans {{ $sort === 'newest' ? 'bg-red-100 text-red-700 font-medium' : 'hover:bg-gray-50' }}">
                        Mới nhất
                    </button>
                    <button wire:click="$set('sort', 'members')"
                            class="w-full text-left px-3 py-2 rounded-lg transition-colors font-open-sans {{ $sort === 'members' ? 'bg-red-100 text-red-700 font-medium' : 'hover:bg-gray-50' }}">
                        Nhiều thành viên nhất
                    </button>
                </div>
            </div>

            <!-- Clear Filters -->
            @if($search || $groupType || $sort !== 'order')
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
        <!-- Stats & Controls -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <!-- Stats -->
                <div class="flex items-center space-x-6">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-red-600 font-montserrat">{{ $stats['total'] }}</div>
                        <div class="text-sm text-gray-600 font-open-sans">Nhóm học tập</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600 font-montserrat">{{ number_format($stats['total_members']) }}</div>
                        <div class="text-sm text-gray-600 font-open-sans">Thành viên</div>
                    </div>
                </div>

                <!-- View Toggle -->
                <div class="flex items-center space-x-3 bg-red-50 px-4 py-2 rounded-lg">
                    <span class="text-sm font-medium text-red-700 font-open-sans">Hiển thị:</span>
                    <select wire:model.live="perPage"
                            class="px-3 py-2 border border-red-200 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm font-open-sans">
                        <option value="12">12</option>
                        <option value="24">24</option>
                        <option value="36">36</option>
                    </select>
                    <span class="text-sm font-medium text-red-700 font-open-sans">nhóm</span>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div wire:loading class="text-center py-8">
            <div class="inline-flex items-center px-4 py-2 bg-red-50 rounded-lg">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-red-700 font-medium font-open-sans">Đang tải...</span>
            </div>
        </div>

        <!-- Course Groups Grid -->
        @if($courseGroups->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" wire:loading.remove>
                @foreach($courseGroups as $group)
                <div class="group-card">
                    @include('components.course-group-card', ['group' => $group])
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="flex justify-center mt-8" wire:loading.remove>
                {{ $courseGroups->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16" wire:loading.remove>
                <div class="w-16 h-16 mx-auto mb-4 bg-red-50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-xl text-red-600 opacity-60"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2 font-montserrat">Không tìm thấy nhóm học tập</h3>
                <p class="text-gray-600 text-sm mb-6 font-open-sans">Thử thay đổi bộ lọc hoặc từ khóa tìm kiếm</p>
                @if($search || $groupType || $sort !== 'order')
                <button wire:click="clearFilters"
                        class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors duration-300">
                    <i class="fas fa-eraser mr-2"></i>
                    Xóa bộ lọc
                </button>
                @endif
            </div>
        @endif
    </main>
</div>

<!-- Mobile Sidebar -->
<div class="mobile-sidebar fixed inset-0 z-50 lg:hidden">
    <div class="mobile-sidebar-overlay absolute inset-0 bg-black/50" onclick="toggleSidebar()"></div>
    <div class="mobile-sidebar-content absolute right-0 top-0 h-full w-80 bg-white shadow-xl transform translate-x-full transition-transform duration-300">
        <div class="flex items-center justify-between p-4 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-900 font-montserrat">Bộ lọc nhóm học tập</h3>
            <button onclick="toggleSidebar()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-4 space-y-6 overflow-y-auto h-full pb-20" id="mobile-filter-content">
            <!-- Content will be copied from desktop filters -->
        </div>
    </div>
</div>

<style>
.filter-card {
    @apply bg-white border border-gray-100 shadow-sm;
}

.mobile-sidebar.active .mobile-sidebar-content {
    transform: translateX(0);
}

@media (min-width: 1024px) {
    .mobile-sidebar {
        display: none !important;
    }
}
</style>
</div>
