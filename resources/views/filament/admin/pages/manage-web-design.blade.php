<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Header Section --}}
        <div class="bg-gradient-to-r from-red-50 to-pink-50 dark:from-red-900/20 dark:to-pink-900/20 border border-red-200 dark:border-red-700 rounded-xl p-6 shadow-sm">
            <div class="flex items-center space-x-4">
                {{-- <div class="w-12 h-12 bg-red-100 dark:bg-red-800 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-red-600 dark:text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a4 4 0 004-4V5z"></path>
                    </svg>
                </div> --}}
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Quản lý giao diện trang chủ</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Kéo thả để sắp xếp thứ tự, bật/tắt hiển thị và tùy chỉnh nội dung các phần</p>
                </div>
            </div>


        </div>

        {{-- Form Section --}}
        <x-filament-panels::form wire:submit="save">
            {{ $this->form }}

            {{-- Save Section --}}
            <div class="mt-8 text-center">
                <div class="inline-flex flex-col items-center p-8    rounded-2xl shadow-lg">
                    {{-- Icon và Title --}}
                    {{-- <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-pink-600 rounded-2xl flex items-center justify-center mb-4 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div> --}}

                    {{-- <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-2">Lưu cài đặt giao diện</h3>
                     --}}

                    {{-- Save Button --}}
                    <x-filament::button
                        type="submit"
                        size="xl"
                        class="bg-gradient-to-r from-red-600 via-pink-600 to-rose-600 hover:from-red-700 hover:via-pink-700 hover:to-rose-700 text-white font-semibold px-8 py-4 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200"
                    >
                        {{-- <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg> --}}
                        <span class="text-lg">Lưu cài đặt giao diện</span>
                    </x-filament::button>

                    {{-- Helper Text --}}
                    {{-- <div class="mt-4 flex items-center space-x-2 text-xs text-gray-500 dark:text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Cache sẽ được tự động clear sau khi lưu</span>
                    </div> --}}
                </div>
            </div>
        </x-filament-panels::form>
    </div>
</x-filament-panels::page>
