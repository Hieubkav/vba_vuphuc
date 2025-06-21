@extends('layouts.shop')

{{--
    Dữ liệu được share từ ViewServiceProvider với cache tập trung
    WebDesign settings điều khiển thứ tự và hiển thị các section
--}}

@section('content')
    @php
        use App\Services\StorefrontSectionService;
        $sections = StorefrontSectionService::getSections($webDesign);
        // Sử dụng isset() để tránh lỗi undefined variable
        $sectionData = [
            'albums' => $albums ?? collect(),
            'courseGroups' => $courseGroups ?? collect(),
            'courseCategories' => $courseCategories ?? collect(),
            'testimonials' => $testimonials ?? collect(),
            'faqs' => $faqs ?? collect(),
            'partners' => $partners ?? collect(),
            'latestPosts' => $latestPosts ?? collect(),
            'newsPosts' => $latestPosts ?? collect(), // Alias cho tương thích
        ];
    @endphp

    @foreach($sections as $sectionKey => $section)
        @if($section['enabled'])
            <x-storefront-section-renderer :section-key="$sectionKey" :section="$section" :data="$sectionData" />
        @endif
    @endforeach
@endsection

{{-- Styles và scripts được handle bởi simple-storefront.css và simple-storefront.js --}}
