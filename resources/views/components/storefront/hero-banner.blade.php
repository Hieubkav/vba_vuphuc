@php
    use Illuminate\Support\Facades\Storage;
    $activeSliders = isset($sliders) && !empty($sliders) ? $sliders : \App\Models\Slider::where('status', 'active')->orderBy('order')->get();
@endphp

@if($activeSliders->count() > 0)
<section class="relative overflow-hidden">
    <div class="relative hero-carousel bg-gradient-to-br from-gray-100 to-gray-200 min-h-[300px] md:min-h-[400px]" x-data="{
        activeSlide: 0, slides: {{ $activeSliders->count() }}, interval: null, maxHeight: 0,
        init() { this.calculateMaxHeight(); if (this.slides > 1) this.interval = setInterval(() => this.nextSlide(), 8000); },
        nextSlide() { this.activeSlide = (this.activeSlide + 1) % this.slides; this.$nextTick(() => this.updateContainerHeights()); },
        prevSlide() { this.activeSlide = (this.activeSlide + this.slides - 1) % this.slides; this.$nextTick(() => this.updateContainerHeights()); },
        calculateMaxHeight() {
            const vh = window.innerHeight, navbar = document.querySelector('header'), subnav = document.querySelector('.bg-red-700');
            const nh = navbar?.offsetHeight || 0, sh = subnav?.offsetHeight || 0, minH = window.innerWidth < 768 ? 300 : 400;
            this.maxHeight = Math.max(vh - nh - sh - 20, minH); this.applyMaxHeight();
        },
        applyMaxHeight() { ['md\\:hidden', 'hidden.md\\:block'].forEach(sel => { const el = this.$el.querySelector('.' + sel); if(el) el.style.maxHeight = this.maxHeight + 'px'; }); },
        updateContainerHeights() {
            ['md\\:hidden', 'hidden.md\\:block'].forEach(sel => {
                const container = this.$el.querySelector('.' + sel), img = container?.querySelector('.hero-slide.opacity-100 img');
                if(img?.complete) container.style.height = Math.min(img.offsetHeight, this.maxHeight) + 'px';
            });
        }
    }" x-init="init(); $nextTick(() => updateContainerHeights());" @resize.window="calculateMaxHeight()" @mouseenter="if(interval) clearInterval(interval);" @mouseleave="if(slides > 1) interval = setInterval(() => nextSlide(), 8000);">

        <!-- Mobile Slides -->
        <div class="md:hidden overflow-hidden relative min-h-[300px]">
            @forelse($activeSliders as $index => $slider)
                <div class="absolute inset-0 transition-opacity duration-1000 ease-in-out hero-slide" x-bind:class="{ 'opacity-100': activeSlide === {{ $index }}, 'opacity-0': activeSlide !== {{ $index }} }">
                    <div class="absolute inset-0 smart-overlay-mobile z-10"></div>
                    @if($slider->link)<a href="{{ $slider->link }}" class="absolute top-4 right-4 z-30 p-2 bg-white/20 backdrop-blur-sm rounded-full text-white hover:bg-white/30 transition-colors duration-300 shadow-lg" aria-label="Xem chi tiết"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg></a>@endif
                    @if($slider->image_link)
                        <div class="w-full h-full flex items-center justify-center">
                            <img src="{{ Storage::url($slider->image_link) }}"
                                 alt="{{ $slider->alt_text ?: $slider->title . ' - VBA Vũ Phúc' }}"
                                 class="w-full h-full object-cover min-h-[300px]"
                                 loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                                 @load="if(activeSlide === {{ $index }}) { $dispatch('slide-loaded'); $nextTick(() => updateContainerHeights()); }"
                                 onerror="console.log('Image failed to load:', this.src); this.style.display='none'; this.parentElement.innerHTML='<div class=\'w-full h-full bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center min-h-[300px]\'><span class=\'text-white text-lg font-medium\'>{{ addslashes($slider->title ?? 'VBA Vũ Phúc') }}</span></div>';"
                                 onload="console.log('Image loaded successfully:', this.src);">
                        </div>
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center min-h-[300px]"><span class="text-white text-lg font-medium">{{ $slider->title ?? 'VBA Vũ Phúc' }}</span></div>
                    @endif

                    @if($slider->title || $slider->description)
                        <div class="absolute inset-0 z-20 flex flex-col justify-end p-6 sm:p-8">
                            <div class="transition-opacity duration-500" x-bind:class="{ 'opacity-100': activeSlide === {{ $index }}, 'opacity-0': activeSlide !== {{ $index }} }">
                                <!-- Minimal text container - very subtle background -->
                                <div class="relative">
                                    <!-- Very light background -->
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-black/20 to-transparent rounded-lg"></div>
                                    <div class="relative p-3 sm:p-4">
                                        @if($slider->title)<h2 class="text-white text-lg sm:text-xl font-bold mb-1 text-shadow-strong">{{ $slider->title }}</h2>@endif
                                        @if($slider->description)<p class="text-white text-xs sm:text-sm mb-2 max-w-xs text-shadow-medium leading-snug">{{ $slider->description }}</p>@endif
                                        @if($slider->link)<a href="{{ $slider->link }}" class="inline-flex items-center bg-red-600/90 hover:bg-red-700 text-white px-2 py-1 text-xs rounded transition-all duration-300 shadow-lg border border-red-500/30 hover:shadow-xl hover:scale-105"><span class="font-medium">Xem chi tiết</span><svg class="h-3 w-3 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></a>@endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @empty
                <div class="relative bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center min-h-[300px]">
                    <div class="absolute inset-0 bg-gradient-to-b from-black/20 via-transparent to-black/30 z-10"></div>
                    <div class="absolute inset-0 z-20 flex flex-col justify-center items-center text-center p-6 sm:p-8">
                        <h2 class="text-white text-xl sm:text-2xl font-bold mb-2 drop-shadow-lg">VBA Vũ Phúc</h2>
                        <p class="text-white text-sm sm:text-base mb-3 max-w-md drop-shadow-md">Khóa học chuyên nghiệp & Dịch vụ đào tạo chất lượng cao</p>
                        <a href="{{ route('courses.index') ?? '#' }}" class="inline-flex items-center bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-colors shadow-lg"><span>Khám phá ngay</span><svg class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Desktop Slides -->
        <div class="hidden md:block overflow-hidden relative bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900 dark:to-red-800 transition-all duration-700" id="desktop-slider-container">
            @forelse($activeSliders as $index => $slider)
                <div class="absolute inset-0 transition-opacity duration-1000 ease-in-out hero-slide" x-bind:class="{ 'opacity-100': activeSlide === {{ $index }}, 'opacity-0': activeSlide !== {{ $index }} }">
                    <div class="absolute inset-0 smart-overlay-desktop z-10"></div>
                    @if($slider->link)<a href="{{ $slider->link }}" class="absolute top-6 right-6 z-30 p-3 bg-white/20 backdrop-blur-sm rounded-full text-white hover:bg-white/30 transition-colors duration-300 shadow-lg" aria-label="Xem chi tiết"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg></a>@endif
                    @if($slider->image_link)
                        <div class="w-full h-full flex items-center justify-center responsive-padding">
                            <img src="{{ Storage::url($slider->image_link) }}"
                                 alt="{{ $slider->alt_text ?: $slider->title . ' - VBA Vũ Phúc' }}"
                                 class="w-full h-auto object-contain max-w-full"
                                 loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                                 @load="if(activeSlide === {{ $index }}) { $dispatch('slide-loaded'); $nextTick(() => updateContainerHeights()); }"
                                 onerror="console.log('Desktop image failed to load:', this.src);"
                                 onload="console.log('Desktop image loaded successfully:', this.src);">
                        </div>
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center"><span class="text-white text-2xl font-medium">{{ $slider->title ?? 'VBA Vũ Phúc' }}</span></div>
                    @endif

                    @if($slider->title || $slider->description)
                        <div class="absolute inset-0 z-20 flex items-center">
                            <div class="container mx-auto px-4 lg:px-6">
                                <div class="max-w-2xl transition-opacity duration-500" x-bind:class="{ 'opacity-100': activeSlide === {{ $index }}, 'opacity-0': activeSlide !== {{ $index }} }">
                                    <!-- Minimal text container - very subtle background -->
                                    <div class="relative">
                                        <!-- Very light background -->
                                        <div class="absolute inset-0 bg-gradient-to-r from-black/35 via-black/20 to-transparent rounded-lg"></div>
                                        <div class="relative p-4 lg:p-6">
                                            @if($slider->title)<h1 class="text-white text-2xl lg:text-3xl xl:text-4xl font-bold mb-3 text-shadow-strong leading-tight">{{ $slider->title }}</h1>@endif
                                            @if($slider->description)<p class="text-white text-sm lg:text-base xl:text-lg mb-4 max-w-lg text-shadow-medium leading-relaxed">{{ $slider->description }}</p>@endif
                                            @if($slider->link)<a href="{{ $slider->link }}" class="inline-flex items-center bg-red-600/90 hover:bg-red-700 text-white px-4 py-2 lg:px-5 lg:py-2.5 rounded-lg transition-all duration-300 shadow-xl border border-red-500/30 text-sm lg:text-base font-medium hover:shadow-2xl hover:scale-105"><span>Xem chi tiết</span><svg class="h-4 w-4 ml-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></a>@endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @empty
                <div class="relative bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center min-h-[400px]">
                    <div class="absolute inset-0 bg-gradient-to-r from-black/30 via-black/10 to-transparent z-10"></div>
                    <div class="absolute inset-0 z-20 flex items-center">
                        <div class="container mx-auto px-4 lg:px-6">
                            <div class="max-w-2xl">
                                <h1 class="text-white text-3xl lg:text-5xl xl:text-6xl font-bold mb-4 drop-shadow-2xl leading-tight">VBA Vũ Phúc</h1>
                                <p class="text-white text-base lg:text-lg xl:text-xl mb-6 max-w-2xl drop-shadow-lg leading-relaxed">Trung tâm đào tạo chuyên nghiệp - Cung cấp khóa học và dịch vụ đào tạo chất lượng cao</p>
                                <a href="{{ route('courses.index') ?? '#' }}" class="inline-flex items-center bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white px-6 py-3 lg:px-8 lg:py-4 rounded-lg transition-colors shadow-xl text-lg font-medium"><span>Khám phá ngay</span><svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        @if($activeSliders->count() > 1)
            <div class="absolute inset-x-0 top-1/2 transform -translate-y-1/2 flex items-center justify-between px-4 md:px-6 z-30">
                @php $btnClass = "p-2 sm:p-3 rounded-full bg-white/20 backdrop-blur-sm text-white hover:bg-white/30 focus:outline-none transition-colors duration-300 shadow-lg"; $btnAction = "if(interval) { clearInterval(interval); interval = setInterval(() => nextSlide(), 8000); }"; @endphp
                <button @click="prevSlide(); {{ $btnAction }}" class="{{ $btnClass }}" aria-label="Slide trước"><svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg></button>
                <button @click="nextSlide(); {{ $btnAction }}" class="{{ $btnClass }}" aria-label="Slide tiếp theo"><svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></button>
            </div>
            <div class="absolute bottom-4 sm:bottom-6 left-0 right-0 z-30">
                <div class="flex items-center justify-center gap-2 sm:gap-3">
                    @foreach($activeSliders as $index => $slider)
                        <button @click="activeSlide = {{ $index }}; {{ $btnAction }}" class="w-3 h-3 sm:w-4 sm:h-4 rounded-full transition-all duration-300 focus:outline-none relative overflow-hidden shadow-lg" x-bind:class="{ 'bg-white w-8 sm:w-10': activeSlide === {{ $index }}, 'bg-white/50': activeSlide !== {{ $index }} }" aria-label="Đi đến slide {{ $index + 1 }}"><span class="absolute left-0 top-0 h-full bg-white transition-all duration-300" x-bind:class="{ 'w-full': activeSlide === {{ $index }}, 'w-0': activeSlide !== {{ $index }} }"></span></button>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>

<style>
.hero-slide { overflow: hidden; }
.hero-slide img { max-width: 100%; object-position: center; }

/* Enhanced text shadows for better readability - no blur needed */
.text-shadow-strong {
    text-shadow:
        0 2px 4px rgba(0, 0, 0, 0.9),
        0 4px 8px rgba(0, 0, 0, 0.7),
        0 6px 12px rgba(0, 0, 0, 0.5);
}

.text-shadow-medium {
    text-shadow:
        0 1px 3px rgba(0, 0, 0, 0.8),
        0 2px 6px rgba(0, 0, 0, 0.6),
        0 3px 9px rgba(0, 0, 0, 0.4);
}

/* Ultra minimal overlay gradients - barely visible */
.smart-overlay-mobile {
    background: linear-gradient(
        to top,
        rgba(0, 0, 0, 0.25) 0%,
        rgba(0, 0, 0, 0.1) 50%,
        transparent 80%
    );
}

.smart-overlay-desktop {
    background: linear-gradient(
        to right,
        rgba(0, 0, 0, 0.2) 0%,
        rgba(0, 0, 0, 0.08) 50%,
        transparent 70%
    );
}

/* Mobile styles */
@media (max-width: 767px) {
    .hero-slide img {
        object-fit: cover;
        width: 100%;
        height: 100%;
        min-height: 300px;
    }
    .md\:hidden .hero-slide { min-height: 300px; }
}

/* Desktop styles */
@media (min-width: 768px) {
    .hero-slide img {
        object-fit: contain;
        max-height: 80vh;
        height: auto;
    }
    .responsive-padding { padding: 0; }
}

@media (min-width: 1001px) and (max-width: 1200px) { .responsive-padding { padding: 0 2rem; } }
@media (min-width: 1201px) and (max-width: 1400px) { .responsive-padding { padding: 0 4rem; } }
@media (min-width: 1401px) and (max-width: 1600px) { .responsive-padding { padding: 0 6rem; } }
@media (min-width: 1601px) { .responsive-padding { padding: 0 8rem; } }

.hero-slide img:not([src]) { background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%); min-height: 300px; }
@media (min-width: 768px) { .hero-slide img:not([src]) { min-height: 400px; } }

/* Clean text styling - no blur effects */
.text-compact {
    font-size: 0.875rem; /* 14px */
    line-height: 1.25;
}

@media (min-width: 640px) {
    .text-compact {
        font-size: 1rem; /* 16px */
        line-height: 1.5;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function initHeroBannerHeight() {
        const heroCarousel = document.querySelector('.hero-carousel');
        if (!heroCarousel) return;

        function calculateMaxHeight() {
            const vh = window.innerHeight, navbar = document.querySelector('header'), subnav = document.querySelector('.bg-red-700');
            const nh = navbar?.offsetHeight || 0, sh = subnav?.offsetHeight || 0, minH = window.innerWidth < 768 ? 300 : 400;
            return Math.max(vh - nh - sh - 20, minH);
        }

        function applyMaxHeight() {
            const maxHeight = calculateMaxHeight();
            ['md\\:hidden', 'hidden.md\\:block'].forEach(sel => {
                const el = heroCarousel.querySelector('.' + sel);
                if(el) el.style.maxHeight = maxHeight + 'px';
            });
        }

        applyMaxHeight();
        window.addEventListener('resize', applyMaxHeight);
        heroCarousel.querySelectorAll('img').forEach(img => img.complete ? applyMaxHeight() : img.addEventListener('load', applyMaxHeight));
    }

    function initDynamicBackground() {
        const container = document.getElementById('desktop-slider-container');
        if (!container) return;

        function extractDominantColor(img) {
            const canvas = document.createElement('canvas'), ctx = canvas.getContext('2d');
            canvas.width = canvas.height = 50;
            try {
                ctx.drawImage(img, 0, 0, 50, 50);
                const data = ctx.getImageData(0, 0, 50, 50).data;
                let r = 0, g = 0, b = 0, count = 0;
                for (let i = 0; i < data.length; i += 16) {
                    if (data[i + 3] > 128) { r += data[i]; g += data[i + 1]; b += data[i + 2]; count++; }
                }
                if (count > 0) {
                    r = Math.round(r / count); g = Math.round(g / count); b = Math.round(b / count);
                    const isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                    container.style.background = isDark
                        ? `linear-gradient(135deg, rgb(${Math.max(0, r-80)}, ${Math.max(0, g-80)}, ${Math.max(0, b-80)}) 0%, rgb(${Math.max(0, r-40)}, ${Math.max(0, g-40)}, ${Math.max(0, b-40)}) 100%)`
                        : `linear-gradient(135deg, rgb(${Math.min(255, r+80)}, ${Math.min(255, g+80)}, ${Math.min(255, b+80)}) 0%, rgb(${Math.min(255, r+40)}, ${Math.min(255, g+40)}, ${Math.min(255, b+40)}) 100%)`;
                }
            } catch (e) {
                container.style.background = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'linear-gradient(135deg, #450a0a 0%, #7f1d1d 100%)' : 'linear-gradient(135deg, #fef2f2 0%, #fecaca 100%)';
            }
        }

        function updateBackground() {
            const activeImg = container.querySelector('.hero-slide.opacity-100 img');
            if (activeImg?.complete) extractDominantColor(activeImg);
            else if (activeImg) activeImg.onload = () => extractDominantColor(activeImg);
        }

        new MutationObserver(updateBackground).observe(container, { attributes: true, subtree: true, attributeFilter: ['class'] });
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', updateBackground);
        setTimeout(updateBackground, 300);
    }

    initHeroBannerHeight();
    setTimeout(initHeroBannerHeight, 100);
    setTimeout(() => initDynamicBackground(), 100);
});
</script>
@endif
