<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Header với thông tin --}}
        <div class="bg-gradient-to-r from-red-50 to-orange-50 dark:from-red-900/20 dark:to-orange-900/20 border border-red-200 dark:border-red-700 rounded-lg p-6">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-red-100 dark:bg-red-800 rounded-lg flex items-center justify-center">
                        <x-heroicon-o-megaphone class="w-6 h-6 text-red-600 dark:text-red-400" />
                    </div>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-red-900 dark:text-red-100 mb-2">
                        🎯 CTA Toàn cục
                    </h3>
                    <p class="text-red-700 dark:text-red-300 text-sm leading-relaxed">
                        Quản lý Call-to-Action hiển thị trên tất cả các trang của website. 
                        CTA này sẽ xuất hiện trước footer để khuyến khích người dùng thực hiện hành động.
                    </p>
                </div>
            </div>
        </div>

        {{-- Preview CTA --}}
        @php
            $ctaData = app(\App\Services\GlobalCtaService::class)->getCtaData();
        @endphp
        
        @if($ctaData['enabled'])
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                    👁️ Xem trước CTA
                </h4>
            </div>
            <div class="p-6">
                <div class="bg-gradient-to-r from-red-700 via-red-600 to-red-700 text-white relative overflow-hidden rounded-lg p-8">
                    <div class="absolute inset-0 opacity-10">
                        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 25% 25%, rgba(255,255,255,0.1) 2px, transparent 2px), radial-gradient(circle at 75% 75%, rgba(255,255,255,0.1) 2px, transparent 2px); background-size: 50px 50px; background-position: 0 0, 25px 25px;"></div>
                    </div>
                    <div class="relative z-10">
                        <div class="flex flex-col md:flex-row items-center justify-between gap-8">
                            <div class="text-center md:text-left max-w-2xl">
                                <span class="bg-white bg-opacity-20 text-white px-3 py-1 rounded-full mb-4 inline-block text-sm">Học tập chuyên nghiệp</span>
                                <h2 class="text-2xl font-bold text-white mb-4">
                                    {{ $ctaData['title'] }}
                                </h2>
                                <p class="text-white/90">{{ $ctaData['description'] }}</p>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-4">
                                @if($ctaData['primary_button_text'])
                                <div class="px-6 py-3 bg-white text-red-700 font-semibold rounded-lg text-center min-w-[160px]">
                                    {{ $ctaData['primary_button_text'] }}
                                </div>
                                @endif
                                @if($ctaData['secondary_button_text'])
                                <div class="px-6 py-3 bg-transparent border-2 border-white text-white font-semibold rounded-lg text-center min-w-[160px]">
                                    {{ $ctaData['secondary_button_text'] }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-lg p-6">
            <div class="flex items-center gap-3">
                <x-heroicon-o-eye-slash class="w-5 h-5 text-yellow-600 dark:text-yellow-400" />
                <p class="text-yellow-800 dark:text-yellow-200 font-medium">
                    CTA hiện đang bị tắt và không hiển thị trên website
                </p>
            </div>
        </div>
        @endif

        {{-- Form cấu hình --}}
        <form wire:submit="save">
            {{ $this->form }}
            
            <div class="flex justify-end mt-6">
                {{ $this->getFormActions() }}
            </div>
        </form>
    </div>
</x-filament-panels::page>
