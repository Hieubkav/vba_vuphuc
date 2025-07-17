<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Header Stats -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-900/20 p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Tổng số file</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $totalFiles }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-900/20 p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-green-500 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2h4a1 1 0 110 2h-1v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6H3a1 1 0 110-2h4z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Tổng dung lượng</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $this->getFormattedTotalSize() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-900/20 p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">File không sử dụng</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ count($unusedFiles) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-900/20 p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-purple-500 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Hiển thị</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ count($files) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-900/20 p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-center">
                    @if(count($unusedFiles) > 0)
                        <button
                            wire:click="deleteUnusedFiles"
                            wire:confirm="Bạn có chắc chắn muốn xóa {{ count($unusedFiles) }} file không sử dụng?"
                            class="bg-red-500 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 shadow-sm"
                        >
                            Xóa file không dùng
                        </button>
                    @else
                        <span class="text-green-600 dark:text-green-400 font-medium">Tất cả file đều được sử dụng</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-900/20 p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex flex-col md:flex-row gap-4 items-start md:items-center justify-between">
                <!-- Filter Tabs -->
                <div class="flex flex-wrap gap-2">
                    <button
                        wire:click="setFilter('all')"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 {{ $currentFilter === 'all' ? 'bg-blue-500 hover:bg-blue-600 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600 hover:text-gray-900 dark:hover:text-gray-100' }}"
                    >
                        Tất cả ({{ $totalFiles }})
                    </button>
                    <button
                        wire:click="setFilter('images')"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 {{ $currentFilter === 'images' ? 'bg-blue-500 hover:bg-blue-600 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600 hover:text-gray-900 dark:hover:text-gray-100' }}"
                    >
                        Hình ảnh
                    </button>
                    <button
                        wire:click="setFilter('documents')"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 {{ $currentFilter === 'documents' ? 'bg-blue-500 hover:bg-blue-600 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600 hover:text-gray-900 dark:hover:text-gray-100' }}"
                    >
                        Tài liệu
                    </button>
                    <button
                        wire:click="setFilter('videos')"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 {{ $currentFilter === 'videos' ? 'bg-blue-500 hover:bg-blue-600 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600 hover:text-gray-900 dark:hover:text-gray-100' }}"
                    >
                        Video
                    </button>
                    <button
                        wire:click="setFilter('audio')"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 {{ $currentFilter === 'audio' ? 'bg-blue-500 hover:bg-blue-600 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600 hover:text-gray-900 dark:hover:text-gray-100' }}"
                    >
                        Audio
                    </button>
                    <button
                        wire:click="setFilter('unused')"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 {{ $currentFilter === 'unused' ? 'bg-red-500 hover:bg-red-600 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600 hover:text-gray-900 dark:hover:text-gray-100' }}"
                    >
                        Không sử dụng ({{ count($unusedFiles) }})
                    </button>
                </div>

                <!-- Search and Actions -->
                <div class="flex gap-2 w-full md:w-auto">
                    <input
                        type="text"
                        wire:model.live="searchTerm"
                        wire:keyup="updateSearch"
                        placeholder="Tìm kiếm file..."
                        class="flex-1 md:w-64 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                    <button
                        wire:click="loadFiles"
                        class="bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 shadow-sm whitespace-nowrap"
                    >
                        Quét lại
                    </button>
                </div>
            </div>
        </div>

        <!-- Files Grid -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-900/20 overflow-hidden border border-gray-200 dark:border-gray-700">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 p-6">
                @forelse($files as $file)
                    <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:shadow-lg dark:hover:shadow-gray-900/40 transition-all duration-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-750">
                        <!-- File Preview -->
                        <div class="aspect-square mb-3 bg-gray-50 dark:bg-gray-700 rounded-lg overflow-hidden flex items-center justify-center border border-gray-200 dark:border-gray-600">
                            @if($file['file_type'] === 'image')
                                <img
                                    src="{{ $file['url'] }}"
                                    alt="{{ $file['name'] }}"
                                    class="w-full h-full object-cover"
                                    loading="lazy"
                                >
                            @else
                                <div class="flex flex-col items-center justify-center text-gray-700 dark:text-gray-200">
                                    <x-filament::icon
                                        :icon="$file['icon']"
                                        class="w-12 h-12 mb-2 text-gray-700 dark:text-gray-200"
                                    />
                                    <span class="text-xs uppercase font-bold text-gray-800 dark:text-gray-100">{{ $file['extension'] }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- File Info -->
                        <div class="space-y-3">
                            <h3 class="font-semibold text-sm text-gray-900 dark:text-white truncate" title="{{ $file['name'] }}">
                                {{ $file['name'] }}
                            </h3>

                            <div class="flex justify-between gap-2 text-xs font-medium">
                                <span class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-2 py-1 rounded-md border border-gray-200 dark:border-gray-600">
                                    {{ $file['formatted_size'] }}
                                </span>
                                <span class="uppercase bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200 px-2 py-1 rounded-md border border-blue-200 dark:border-blue-700">
                                    {{ $file['extension'] }}
                                </span>
                            </div>

                            <div class="text-xs text-gray-700 dark:text-gray-300 font-medium bg-gray-50 dark:bg-gray-700 px-2 py-1 rounded-md">
                                📅 {{ $file['created_at'] }}
                            </div>

                            <div class="text-xs text-gray-600 dark:text-gray-400 truncate bg-gray-50 dark:bg-gray-700 px-2 py-1 rounded-md border border-gray-200 dark:border-gray-600" title="{{ $file['mime_type'] }}">
                                {{ $file['mime_type'] }}
                            </div>

                            <!-- Usage Status -->
                            <div class="flex items-center justify-center mt-2">
                                @if($file['is_used'])
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-green-100 dark:bg-green-900/50 text-green-900 dark:text-green-100 border border-green-300 dark:border-green-600 shadow-sm">
                                        ✓ Đang sử dụng
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-red-100 dark:bg-red-900/50 text-red-900 dark:text-red-100 border border-red-300 dark:border-red-600 shadow-sm">
                                        ⚠ Không sử dụng
                                    </span>
                                @endif
                            </div>

                            <!-- Actions -->
                            <div class="flex space-x-2 pt-3">
                                <a
                                    href="{{ $file['url'] }}"
                                    target="_blank"
                                    class="flex-1 bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white text-xs py-2.5 px-3 rounded-lg text-center transition-all duration-200 font-semibold shadow-md hover:shadow-lg border border-blue-600 dark:border-blue-500"
                                >
                                    👁 Xem
                                </a>
                                <button
                                    wire:click="deleteFile('{{ $file['path'] }}')"
                                    wire:confirm="Bạn có chắc chắn muốn xóa file này?"
                                    class="flex-1 bg-red-500 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700 text-white text-xs py-2.5 px-3 rounded-lg transition-all duration-200 font-semibold shadow-md hover:shadow-lg border border-red-600 dark:border-red-500"
                                >
                                    🗑 Xóa
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-16 bg-gray-50 dark:bg-gray-700 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600">
                        <svg class="mx-auto h-16 w-16 text-gray-400 dark:text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">📂 Không có file</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300 max-w-md mx-auto">
                            @if($currentFilter !== 'all')
                                🔍 Không tìm thấy file nào với bộ lọc "<strong class="text-gray-800 dark:text-gray-200">{{ ucfirst($currentFilter) }}</strong>".
                                <br>Thử thay đổi bộ lọc hoặc từ khóa tìm kiếm.
                            @else
                                📁 Chưa có file nào trong storage.
                                <br>Upload file mới để bắt đầu quản lý.
                            @endif
                        </p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Info -->
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-blue-500 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-base font-semibold text-blue-900 dark:text-blue-100 mb-3">💡 Hướng dẫn sử dụng File Manager</h3>
                    <div class="text-sm text-blue-800 dark:text-blue-200 space-y-2">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div class="space-y-2">
                                <p class="font-medium">📁 Loại file hỗ trợ:</p>
                                <ul class="space-y-1 text-blue-700 dark:text-blue-300">
                                    <li>🖼 Hình ảnh: JPG, PNG, WebP, GIF</li>
                                    <li>📄 Tài liệu: PDF, DOC, XLS, PPT</li>
                                    <li>🎥 Video: MP4, AVI, MOV</li>
                                    <li>🎵 Audio: MP3, WAV, AAC</li>
                                </ul>
                            </div>
                            <div class="space-y-2">
                                <p class="font-medium">⚡ Tính năng:</p>
                                <ul class="space-y-1 text-blue-700 dark:text-blue-300">
                                    <li>🔍 Tìm kiếm và lọc thông minh</li>
                                    <li>🗑 Xóa file không sử dụng an toàn</li>
                                    <li>📊 Thống kê dung lượng chi tiết</li>
                                    <li>🔄 Quét lại tự động</li>
                                </ul>
                            </div>
                        </div>
                        <div class="mt-4 p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg border border-blue-200 dark:border-blue-700">
                            <p class="font-medium text-blue-900 dark:text-blue-100">⚠️ Lưu ý quan trọng:</p>
                            <p class="text-blue-800 dark:text-blue-200 mt-1">
                                File có trạng thái "Không sử dụng" có thể xóa an toàn. Tuy nhiên, nên backup dữ liệu trước khi xóa hàng loạt.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
