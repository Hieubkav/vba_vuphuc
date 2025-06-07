@extends('layouts.shop')

{{--
    Dữ liệu được share từ ViewServiceProvider với cache tập trung
    WebDesign settings điều khiển thứ tự và hiển thị các section
--}}

@section('content')
    @php
        // Lấy cấu hình WebDesign hoặc sử dụng fallback
        $sections = $webDesign ? $webDesign->getOrderedSections() : [];

        // Fallback nếu không có WebDesign settings
        if (empty($sections)) {
            $sections = [
                'hero_banner' => [
                    'enabled' => true,
                    'order' => 1,
                    'component' => 'components.storefront.hero-banner',
                    'type' => 'include'
                ],
                'courses_overview' => [
                    'enabled' => true,
                    'order' => 2,
                    'title' => 'Khóa học VBA Excel chuyên nghiệp',
                    'description' => 'Nâng cao kỹ năng Excel với các khóa học VBA từ cơ bản đến nâng cao',
                    'bg_color' => 'bg-white',
                    'animation_class' => 'animate-fade-in-optimized',
                    'component' => 'courses-overview',
                    'type' => 'livewire'
                ],
                'testimonials' => [
                    'enabled' => true,
                    'order' => 6,
                    'title' => 'Đánh giá từ học viên',
                    'description' => 'Chia sẻ từ những học viên đã tham gia khóa học',
                    'bg_color' => 'bg-white',
                    'animation_class' => 'animate-fade-in-optimized',
                    'component' => 'components.storefront.testimonials',
                    'type' => 'include'
                ],
                // ... các section khác với fallback values
            ];
        }
    @endphp

    @foreach($sections as $sectionKey => $section)
        @if($section['enabled'])
            @if($sectionKey === 'hero_banner')
                <!-- Hero Banner -->
                @include('components.storefront.hero-banner')

            @elseif($sectionKey === 'courses_overview')
                <!-- Giới thiệu khóa học -->
                <x-storefront-section
                    title="{{ $section['title'] ?? 'Khóa học VBA Excel chuyên nghiệp' }}"
                    description="{{ $section['description'] ?? 'Nâng cao kỹ năng Excel với các khóa học VBA từ cơ bản đến nâng cao' }}"
                    bg-color="{{ $section['bg_color'] ?? 'bg-white' }}"
                    animation-class="{{ $section['animation_class'] ?? 'animate-fade-in-optimized' }}"
                    :has-data="true">
                    @livewire('courses-overview')
                </x-storefront-section>

            @elseif($sectionKey === 'album_timeline')
                <!-- Album timeline khóa học -->
                <x-storefront-section
                    title="{{ $section['title'] ?? 'Thư viện tài liệu' }}"
                    description="{{ $section['description'] ?? 'Tài liệu và hình ảnh từ các khóa học đã diễn ra' }}"
                    bg-color="{{ $section['bg_color'] ?? 'bg-gray-25' }}"
                    animation-class="{{ $section['animation_class'] ?? 'animate-fade-in-optimized' }}"
                    :has-data="isset($albums) && $albums->isNotEmpty()"
                    empty-icon="fas fa-images"
                    empty-message="Chưa có album nào được tải lên">
                    @include('components.storefront.album-timeline')
                </x-storefront-section>

            @elseif($sectionKey === 'course_groups')
                <!-- Nhóm khóa học -->
                <x-storefront-section
                    title="{{ $section['title'] ?? 'Nhóm học tập' }}"
                    description="{{ $section['description'] ?? 'Tham gia các nhóm Facebook/Zalo để học hỏi và trao đổi kinh nghiệm' }}"
                    bg-color="{{ $section['bg_color'] ?? 'bg-white' }}"
                    animation-class="{{ $section['animation_class'] ?? 'animate-fade-in-optimized' }}"
                    :has-data="isset($courseGroups) && $courseGroups->isNotEmpty()"
                    empty-icon="fas fa-users"
                    empty-message="Chưa có nhóm khóa học nào">
                    @include('components.storefront.course-groups')
                </x-storefront-section>

            @elseif($sectionKey === 'course_categories')
                <!-- Khóa học theo từng danh mục -->
                <x-storefront-section
                    title="{{ $section['title'] ?? 'Khóa học theo chuyên mục' }}"
                    description="{{ $section['description'] ?? 'Khám phá các khóa học được phân loại theo từng chuyên mục' }}"
                    bg-color="{{ $section['bg_color'] ?? 'bg-gray-25' }}"
                    animation-class="{{ $section['animation_class'] ?? 'animate-fade-in-optimized' }}"
                    :has-data="isset($courseCategories) && $courseCategories->isNotEmpty()"
                    empty-icon="fas fa-graduation-cap"
                    empty-message="Chưa có khóa học nào">
                    @include('components.storefront.course-categories-sections')
                </x-storefront-section>

            @elseif($sectionKey === 'testimonials')
                <!-- Đánh giá của khách hàng -->
                <x-storefront-section
                    title="{{ $section['title'] ?? 'Đánh giá từ học viên' }}"
                    description="{{ $section['description'] ?? 'Chia sẻ từ những học viên đã tham gia khóa học' }}"
                    bg-color="{{ $section['bg_color'] ?? 'bg-white' }}"
                    animation-class="{{ $section['animation_class'] ?? 'animate-fade-in-optimized' }}"
                    :has-data="isset($testimonials) && $testimonials->isNotEmpty()"
                    empty-icon="fas fa-star"
                    empty-message="Chưa có đánh giá nào">
                    @include('components.storefront.testimonials')
                </x-storefront-section>

            @elseif($sectionKey === 'faq')
                <!-- FAQ -->
                <x-storefront-section
                    title="{{ $section['title'] ?? 'Câu hỏi thường gặp' }}"
                    description="{{ $section['description'] ?? 'Giải đáp những thắc mắc phổ biến về khóa học' }}"
                    bg-color="{{ $section['bg_color'] ?? 'bg-gray-25' }}"
                    animation-class="{{ $section['animation_class'] ?? 'animate-fade-in-optimized' }}"
                    :has-data="isset($faqs) && $faqs->isNotEmpty()"
                    empty-icon="fas fa-question-circle"
                    empty-message="Chưa có câu hỏi nào">
                    @include('components.storefront.faq-section')
                </x-storefront-section>

            @elseif($sectionKey === 'partners')
                <!-- Đối tác -->
                <x-storefront-section
                    title="{{ $section['title'] ?? 'Đối tác tin cậy' }}"
                    description="{{ $section['description'] ?? 'Những đối tác đồng hành cùng chúng tôi' }}"
                    bg-color="{{ $section['bg_color'] ?? 'bg-white' }}"
                    animation-class="{{ $section['animation_class'] ?? 'animate-fade-in-optimized' }}"
                    :has-data="isset($partners) && $partners->isNotEmpty()"
                    empty-icon="fas fa-handshake"
                    empty-message="Chưa có đối tác nào">
                    @include('components.storefront.partners')
                </x-storefront-section>

            @elseif($sectionKey === 'blog_posts')
                <!-- Blog Posts -->
                <x-storefront-section
                    title="{{ $section['title'] ?? 'Bài viết mới nhất' }}"
                    description="{{ $section['description'] ?? 'Cập nhật kiến thức và thông tin hữu ích' }}"
                    bg-color="{{ $section['bg_color'] ?? 'bg-gray-25' }}"
                    animation-class="{{ $section['animation_class'] ?? 'animate-fade-in-optimized' }}"
                    :has-data="(isset($newsPosts) && $newsPosts->isNotEmpty()) || (isset($latestPosts) && $latestPosts->isNotEmpty())"
                    empty-icon="fas fa-newspaper"
                    empty-message="Chưa có bài viết nào">
                    @include('components.storefront.blog-posts')
                </x-storefront-section>

            @elseif($sectionKey === 'homepage_cta')
                <!-- Call to Action -->
                <section class="storefront-section bg-gradient-to-r from-red-700 via-red-600 to-red-700 text-white relative overflow-hidden">
                    @include('components.storefront.homepage-cta')
                </section>
            @endif
        @endif
    @endforeach
@endsection

{{-- Styles và scripts được handle bởi simple-storefront.css và simple-storefront.js --}}
