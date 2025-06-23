@props(['sectionKey', 'section', 'data' => []])

@php
    use App\Services\StorefrontSectionService;
    $hasData = StorefrontSectionService::hasData($sectionKey, $data);
@endphp

@if($sectionKey === 'hero_banner')
    @include('components.storefront.hero-banner')
@elseif($sectionKey === 'courses_overview')
    {{-- courses-overview component tự quản lý title/description từ WebDesign --}}
    @include('components.storefront.courses-overview')
@elseif($sectionKey === 'homepage_cta')
    {{-- CTA đã được di chuyển vào layout chung, không hiển thị ở đây nữa --}}
    @php
        // Skip homepage_cta vì đã được hiển thị trong layout chung
    @endphp
@else
    <x-storefront-section
        title="{{ $section['title'] ?? '' }}"
        description="{{ $section['description'] ?? '' }}"
        bg-color="{{ $section['bg_color'] ?? 'bg-white' }}"
        animation-class="{{ $section['animation_class'] ?? 'animate-fade-in-optimized' }}"
        :has-data="$hasData"
        empty-icon="{{ $section['empty_icon'] ?? 'fas fa-info-circle' }}"
        empty-message="{{ $section['empty_message'] ?? 'Chưa có dữ liệu' }}">
        @if($section['type'] === 'livewire')
            @livewire($section['component'])
        @else
            @include($section['component'])
        @endif
    </x-storefront-section>
@endif
