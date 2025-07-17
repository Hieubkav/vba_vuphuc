@php
    // Lấy dữ liệu CTA từ WebDesign hoặc sử dụng giá trị mặc định
    $ctaTitle = $webDesign->homepage_cta_title ?? 'Bắt đầu hành trình với VBA Vũ Phúc';
    $ctaDescription = $webDesign->homepage_cta_description ?? 'Khám phá các khóa học VBA chất lượng cao và chuyên sâu. Học tập hiệu quả, hỗ trợ tận tâm từ giảng viên.';
    $primaryButtonText = $webDesign->homepage_cta_primary_button_text ?? 'Xem khóa học';
    $primaryButtonUrl = $webDesign->homepage_cta_primary_button_url ?? '/courses';
    $secondaryButtonText = $webDesign->homepage_cta_secondary_button_text ?? 'Đăng ký học';
    $secondaryButtonUrl = $webDesign->homepage_cta_secondary_button_url ?? '/students/register';
    $isVisible = $webDesign->homepage_cta_enabled ?? true;

    // Xử lý URL - nếu bắt đầu bằng / thì dùng route, nếu không thì dùng trực tiếp
    $primaryUrl = str_starts_with($primaryButtonUrl, '/') ? url($primaryButtonUrl) : $primaryButtonUrl;
    $secondaryUrl = str_starts_with($secondaryButtonUrl, '/') ? url($secondaryButtonUrl) : $secondaryButtonUrl;
@endphp

@if($isVisible)
<div class="absolute inset-0 opacity-10">
    <div class="absolute inset-0 bg-pattern"></div>
</div>
<div class="container mx-auto px-4 relative z-10">
    <div class="flex flex-col md:flex-row items-center justify-between gap-8 md:gap-16">
        <div class="text-center md:text-left max-w-2xl">
            <span class="badge-text bg-white bg-opacity-20 text-white px-3 py-1 rounded-full mb-4 inline-block">Học tập chuyên nghiệp</span>
            <h2 class="hero-title text-white mb-4">
                {!! nl2br(e($ctaTitle)) !!}
            </h2>
            <p class="subtitle text-white/90">{{ $ctaDescription }}</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-4">
            @if($primaryButtonText && $primaryButtonUrl)
            <a href="{{ $primaryUrl }}" class="px-8 py-4 bg-white text-red-700 font-semibold rounded-lg hover:bg-gray-100 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1 text-center min-w-[180px]">
                {{ $primaryButtonText }}
            </a>
            @endif

            @if($secondaryButtonText && $secondaryButtonUrl)
            <a href="{{ $secondaryUrl }}" class="px-8 py-4 bg-transparent border-2 border-white text-white font-semibold rounded-lg hover:bg-white hover:text-red-700 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1 text-center min-w-[180px]">
                {{ $secondaryButtonText }}
            </a>
            @endif
        </div>
    </div>
</div>
@endif