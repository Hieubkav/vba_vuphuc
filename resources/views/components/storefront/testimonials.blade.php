@if(isset($testimonials) && $testimonials->count() > 0)
<div class="container mx-auto px-4 relative">
    <!-- Background Decoration -->
    <div class="absolute -top-16 -left-16 w-48 h-48 bg-red-50 rounded-full opacity-60 -z-10 hidden lg:block"></div>
    <div class="absolute -bottom-16 -right-16 w-48 h-48 bg-red-50 rounded-full opacity-60 -z-10 hidden lg:block"></div>

    <!-- Testimonial Header -->
    <div class="text-center max-w-3xl mx-auto mb-12 relative z-10">
        <span class="inline-block py-1 px-3 text-xs font-semibold bg-red-100 text-red-800 rounded-full tracking-wider uppercase">Học viên nói gì</span>
        <h2 class="mt-3 text-2xl md:text-3xl lg:text-4xl font-bold text-gray-900 leading-tight">Niềm tin từ <span class="text-red-700">học viên</span></h2>
        <p class="mt-4 text-base md:text-lg text-gray-600">Những chia sẻ chân thực từ học viên đã tham gia khóa học tại VBA Vũ Phúc</p>
    </div>
    
    <!-- Testimonial Cards Slider -->
    <div class="testimonials-slider" x-data="testimonialsSlider({{ $testimonials->count() }})">
        <!-- Testimonials Wrapper -->
        <div class="overflow-hidden relative">
            <div class="flex transition-transform duration-500 ease-out" :style="{ 'transform': 'translateX(-' + (100 * activeSlide) / slidesPerView + '%)' }">
                @foreach($testimonials as $testimonial)
                <!-- Testimonial Card {{ $loop->iteration }} -->
                <div class="testimonial-card">
                    <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-300 p-6 h-full flex flex-col relative border border-gray-100 hover:border-red-100">
                        <!-- Quote Icon -->
                        <div class="absolute top-4 right-4">
                            <i class="fas fa-quote-right text-2xl text-red-50"></i>
                        </div>

                        <!-- Rating Stars -->
                        <div class="flex mb-3">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star text-sm {{ $i <= $testimonial->rating ? 'text-yellow-500' : 'text-gray-300' }}"></i>
                            @endfor
                        </div>

                        <!-- Testimonial Content -->
                        <p class="text-gray-600 italic text-base mb-4 flex-grow leading-relaxed">"{{ $testimonial->content }}"</p>

                        <!-- Customer Info -->
                        <div class="flex items-center mt-auto pt-3 border-t border-gray-100">
                            <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-200 mr-3 flex-shrink-0">
                                <img data-src="{{ $testimonial->avatar_url }}" alt="{{ $testimonial->name }}" class="w-full h-full object-cover testimonial-image lazy-loading" loading="lazy" style="opacity: 0; transition: opacity 0.3s ease, filter 0.3s ease, transform 0.3s ease;" onerror="this.style.display='none'; this.parentElement.classList.add('bg-red-100'); this.parentElement.innerHTML='<i class=\'fas fa-user text-red-400\'></i>'">
                            </div>
                            <div class="min-w-0 flex-1">
                                <h4 class="font-semibold text-gray-900 text-sm truncate">{{ $testimonial->name }}</h4>
                                <p class="text-xs text-gray-500 truncate">
                                    @if($testimonial->position && $testimonial->company)
                                        {{ $testimonial->position }}, {{ $testimonial->company }}
                                    @elseif($testimonial->position)
                                        {{ $testimonial->position }}
                                    @elseif($testimonial->company)
                                        {{ $testimonial->company }}
                                    @endif
                                    @if($testimonial->location)
                                        @if($testimonial->position || $testimonial->company), @endif{{ $testimonial->location }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Testimonial Navigation -->
        @if($testimonials->count() > 1)
        <div class="flex items-center justify-center mt-8 gap-2">
            <!-- Navigation Dots -->
            <template x-for="(slide, index) in totalSlides" :key="index">
                <button @click="goToSlide(index)" class="w-2 h-2 rounded-full transition-all duration-300"
                        :class="index === activeSlide ? 'bg-red-700 w-6' : 'bg-gray-300 hover:bg-gray-400'">
                </button>
            </template>
        </div>
        @endif
    </div>
</div>

<!-- Fallback UI khi không có testimonials -->
@else
<div class="container mx-auto px-4 text-center py-12">
    <div class="max-w-md mx-auto">
        <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
            <i class="fas fa-comments text-2xl text-gray-400"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-700 mb-2">Chưa có lời khen nào</h3>
        <p class="text-gray-500 text-sm">Các lời khen từ học viên sẽ được hiển thị tại đây.</p>
    </div>
</div>
@endif

@if(isset($testimonials) && $testimonials->count() > 0)
@push('styles')
<style>
    /* Testimonial Slider Styles - Minimalist Design */
    .testimonials-slider {
        margin: 0 -6px;
    }

    .testimonial-card {
        padding: 6px;
        width: 100%;
        flex-shrink: 0;
    }

    @media (min-width: 640px) {
        .testimonial-card {
            width: 50%;
        }
    }

    @media (min-width: 1024px) {
        .testimonial-card {
            width: 33.333%;
        }
    }

    @media (min-width: 1280px) {
        .testimonial-card {
            width: 25%;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    function testimonialsSlider(totalTestimonials) {
        return {
            activeSlide: 0,
            totalSlides: 1,
            slidesPerView: 1,
            totalTestimonials: totalTestimonials,

            init() {
                this.updateSlidesPerView();
                window.addEventListener('resize', () => this.updateSlidesPerView());
            },

            updateSlidesPerView() {
                if (window.innerWidth >= 1280) {
                    this.slidesPerView = 4;
                } else if (window.innerWidth >= 1024) {
                    this.slidesPerView = 3;
                } else if (window.innerWidth >= 640) {
                    this.slidesPerView = 2;
                } else {
                    this.slidesPerView = 1;
                }

                // Calculate total slides based on testimonials count and slides per view
                this.totalSlides = Math.max(1, Math.ceil(this.totalTestimonials / this.slidesPerView));

                // Make sure we don't go out of bounds
                if (this.activeSlide >= this.totalSlides) {
                    this.activeSlide = this.totalSlides - 1;
                }
            },

            next() {
                if (this.activeSlide < this.totalSlides - 1) {
                    this.activeSlide++;
                } else {
                    this.activeSlide = 0;
                }
            },

            prev() {
                if (this.activeSlide > 0) {
                    this.activeSlide--;
                } else {
                    this.activeSlide = this.totalSlides - 1;
                }
            },

            goToSlide(slideIndex) {
                this.activeSlide = slideIndex;
            }
        };
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Auto-rotate testimonials only if there are multiple slides
        const sliderElement = document.querySelector('[x-data*="testimonialsSlider"]');
        if (sliderElement) {
            const testimonialInterval = setInterval(() => {
                try {
                    const slider = sliderElement.__x.$data;
                    if (slider.totalSlides > 1) {
                        slider.next();
                    }
                } catch (e) {
                    // Clear interval if slider is not available
                    clearInterval(testimonialInterval);
                }
            }, 6000);
        }
    });
</script>
@endpush
@endif