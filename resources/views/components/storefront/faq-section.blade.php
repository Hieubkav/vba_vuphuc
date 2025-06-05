@if(isset($faqs) && $faqs->count() > 0)
<div class="container mx-auto px-4 relative">
    <!-- Background Elements - Minimalist -->
    <div class="hidden lg:block absolute -top-8 -right-12 w-20 h-20 bg-red-25 rounded-full opacity-40"></div>
    <div class="hidden lg:block absolute -bottom-8 -left-12 w-16 h-16 bg-red-25 rounded-full opacity-40"></div>

    <!-- FAQ Section Header -->
    <div class="text-center max-w-3xl mx-auto mb-12 relative z-10">
        <span class="inline-block py-1 px-3 text-xs font-semibold bg-red-100 text-red-800 rounded-full tracking-wider uppercase">Hỏi đáp</span>
        <h2 class="mt-3 text-2xl md:text-3xl lg:text-4xl font-bold text-gray-900 leading-tight">Câu hỏi <span class="text-red-700">thường gặp</span></h2>
        <p class="mt-4 text-base md:text-lg text-gray-600">Các thông tin quan trọng giúp bạn hiểu rõ hơn về khóa học và dịch vụ của VBA Vũ Phúc</p>
    </div>
    
    <!-- FAQ Accordion -->
    <div class="max-w-3xl mx-auto" x-data="{ activeAccordion: null }">
        @foreach($faqs as $faq)
        <!-- FAQ Item {{ $loop->iteration }} -->
        <div class="mb-2 border border-gray-100 rounded-lg overflow-hidden hover:border-red-100 transition-colors duration-200">
            <button
                @click="activeAccordion = activeAccordion === {{ $faq->id }} ? null : {{ $faq->id }}"
                class="flex items-center justify-between w-full p-4 text-left bg-white hover:bg-gray-25 transition-colors duration-200"
                :class="{ 'bg-gray-25': activeAccordion === {{ $faq->id }} }"
            >
                <span class="text-base md:text-lg font-semibold text-gray-900 pr-4">{{ $faq->question }}</span>
                <i class="fas fa-chevron-down text-red-600 transform transition-transform duration-200 flex-shrink-0"
                   :class="{ 'rotate-180': activeAccordion === {{ $faq->id }} }"></i>
            </button>
            <div x-show="activeAccordion === {{ $faq->id }}" x-collapse x-cloak class="px-4 py-3 bg-white border-t border-gray-100">
                <div class="text-gray-600 text-sm md:text-base leading-relaxed prose prose-sm max-w-none">
                    {!! $faq->answer !!}
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Extra Support Section -->
    @if($faqs->count() > 0)
    <div class="mt-10 max-w-3xl mx-auto flex flex-col md:flex-row items-center justify-between bg-gradient-to-r from-red-25 to-red-50 rounded-lg p-6 gap-6">
        <div class="text-center md:text-left">
            <h3 class="text-xl font-bold text-gray-900 mb-2">Không tìm thấy câu trả lời?</h3>
            <p class="text-gray-600 text-sm">Liên hệ với đội ngũ hỗ trợ của chúng tôi để được giải đáp nhanh chóng.</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3">
            <a href="tel:{{ $globalSettings->phone ?? '#' }}" class="inline-flex items-center justify-center px-5 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors text-sm">
                <i class="fas fa-phone mr-2"></i>
                Gọi ngay
            </a>
            <a href="{{ $globalSettings->zalo_link ?? '#' }}" class="inline-flex items-center justify-center px-5 py-2 border-2 border-red-700 text-red-700 font-medium rounded-lg hover:bg-red-700 hover:text-white transition-all text-sm">
                <i class="fas fa-comments mr-2"></i>
                Chat Zalo
            </a>
        </div>
    </div>
    @endif
</div>

<!-- Fallback UI khi không có FAQs -->
@else
<div class="container mx-auto px-4 text-center py-12">
    <div class="max-w-md mx-auto">
        <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
            <i class="fas fa-question-circle text-2xl text-gray-400"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-700 mb-2">Chưa có câu hỏi nào</h3>
        <p class="text-gray-500 text-sm">Các câu hỏi thường gặp sẽ được hiển thị tại đây.</p>
    </div>
</div>
@endif