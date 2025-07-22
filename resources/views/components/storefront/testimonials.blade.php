@if(isset($testimonials) && $testimonials->count() > 0)
<div class="container mx-auto px-4 relative">
    <!-- Background Decoration -->
    <div class="absolute -top-16 -left-16 w-48 h-48 bg-red-50 rounded-full opacity-60 -z-10 hidden lg:block"></div>
    <div class="absolute -bottom-16 -right-16 w-48 h-48 bg-red-50 rounded-full opacity-60 -z-10 hidden lg:block"></div>

    {{-- <!-- Testimonial Header -->
    <div class="text-center max-w-3xl mx-auto mb-12 relative z-10">
        <span class="inline-block py-1 px-3 text-xs font-semibold bg-red-100 text-red-800 rounded-full tracking-wider uppercase">Học viên nói gì</span>
        <h2 class="mt-3 text-2xl md:text-3xl lg:text-4xl font-bold text-gray-900 leading-tight">Niềm tin từ <span class="text-red-700">học viên</span></h2>
        <p class="mt-4 text-base md:text-lg text-gray-600">Những chia sẻ chân thực từ học viên đã tham gia khóa học tại VBA Vũ Phúc</p>
    </div> --}}
    
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
                            <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-200 mr-3 flex-shrink-0 testimonial-avatar">
                                @php
                                    $isInitialsAvatar = \App\Helpers\AvatarHelper::isInitialsAvatar($testimonial->avatar);
                                @endphp

                                @if($isInitialsAvatar)
                                    @php
                                        $avatarData = \App\Helpers\AvatarHelper::parseAvatarString($testimonial->avatar);
                                    @endphp

                                    @if($avatarData)
                                        <!-- Avatar chữ cái -->
                                        <div
                                            class="w-full h-full avatar-initials avatar-initials--shadow flex items-center justify-center text-sm font-semibold"
                                            style="background-color: {{ $avatarData['background_color'] }}; color: {{ $avatarData['text_color'] }}"
                                            title="Feedback từ {{ $testimonial->name }}"
                                        >
                                            {{ $avatarData['initials'] }}
                                        </div>
                                    @else
                                        <!-- Fallback nếu parse lỗi -->
                                        <div class="w-full h-full bg-gradient-to-br from-red-50 to-red-100 flex items-center justify-center">
                                            <i class="fas fa-user text-red-500 text-sm"></i>
                                        </div>
                                    @endif
                                @elseif($testimonial->avatar)
                                    <!-- Ảnh thật từ testimonial -->
                                    <img src="{{ asset('storage/' . $testimonial->avatar) }}"
                                         alt="{{ \App\Services\SeoImageService::createSeoAltText($testimonial->name, 'Ảnh đại diện') }}"
                                         title="{{ \App\Services\SeoImageService::createSeoTitle($testimonial->name, 'Ảnh đại diện') }}"
                                         class="w-full h-full object-cover"
                                         loading="lazy"
                                         onerror="handleImageError(this)">
                                @else
                                    <!-- Placeholder khi không có ảnh -->
                                    <div class="w-full h-full bg-gradient-to-br from-red-50 to-red-100 flex items-center justify-center">
                                        <i class="fas fa-user text-red-500 text-sm"></i>
                                    </div>
                                @endif
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

<!-- Fallback UI đẹp khi không có testimonials -->
@else
<div class="container mx-auto px-4 text-center py-16">
    <div class="max-w-lg mx-auto">
        <!-- Icon với gradient đỏ -->
        <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-red-500 to-red-600 rounded-full flex items-center justify-center shadow-lg">
            <i class="fas fa-star text-3xl text-white"></i>
        </div>

        <!-- Tiêu đề -->
        <h3 class="text-2xl font-bold text-gray-800 mb-3">Chưa có đánh giá nào</h3>

        <!-- Mô tả -->
        <p class="text-gray-600 text-base leading-relaxed mb-6">
            Hãy là người đầu tiên chia sẻ trải nghiệm học tập của bạn với chúng tôi!
        </p>

        <!-- Call to action -->
        <div class="inline-flex items-center px-6 py-3 bg-red-50 text-red-700 rounded-lg border border-red-200">
            <i class="fas fa-heart text-red-500 mr-2"></i>
            <span class="font-medium">Đánh giá sẽ xuất hiện tại đây</span>
        </div>
    </div>
</div>
@endif

@if(isset($testimonials) && $testimonials->count() > 0)
@push('styles')
<link rel="stylesheet" href="{{ asset('css/testimonials.css') }}">
<link rel="stylesheet" href="{{ asset('css/avatar-initials.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/testimonials.js') }}"></script>
@endpush
@endif