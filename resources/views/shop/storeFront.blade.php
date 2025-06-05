@extends('layouts.shop')

{{--
    Dữ liệu được share từ ViewServiceProvider với cache tập trung
    SimpleStorefrontService chỉ là helper để check dữ liệu và fallback
--}}

@section('content')
    <!-- Hero Banner -->
    @include('components.storefront.hero-banner')

    <!-- Giới thiệu khóa học -->
    <x-storefront-section
        title="Khóa học VBA Excel chuyên nghiệp"
        description="Nâng cao kỹ năng Excel với các khóa học VBA từ cơ bản đến nâng cao"
        bg-color="bg-white"
        :has-data="true">
        @livewire('courses-overview')
    </x-storefront-section>

    <!-- Album timeline khóa học -->
    <x-storefront-section
        title="Thư viện tài liệu"
        description="Tài liệu và hình ảnh từ các khóa học đã diễn ra"
        bg-color="bg-gray-25"
        :has-data="isset($albums) && $albums->isNotEmpty()"
        empty-icon="fas fa-images"
        empty-message="Chưa có album nào được tải lên">
        @include('components.storefront.album-timeline')
    </x-storefront-section>

    <!-- Nhóm khóa học -->
    <x-storefront-section
        title="Nhóm học tập"
        description="Tham gia các nhóm Facebook/Zalo để học hỏi và trao đổi kinh nghiệm"
        bg-color="bg-white"
        :has-data="isset($courseGroups) && $courseGroups->isNotEmpty()"
        empty-icon="fas fa-users"
        empty-message="Chưa có nhóm khóa học nào">
        @include('components.storefront.course-groups')
    </x-storefront-section>

    <!-- Khóa học theo từng danh mục -->
    <x-storefront-section
        title="Khóa học theo chuyên mục"
        description="Khám phá các khóa học được phân loại theo từng chuyên mục"
        bg-color="bg-gray-25"
        :has-data="isset($courseCategories) && $courseCategories->isNotEmpty()"
        empty-icon="fas fa-graduation-cap"
        empty-message="Chưa có khóa học nào">
        @include('components.storefront.course-categories-sections')
    </x-storefront-section>

    <!-- Đánh giá của khách hàng -->
    <x-storefront-section
        title="Đánh giá từ học viên"
        description="Chia sẻ từ những học viên đã tham gia khóa học"
        bg-color="bg-white"
        :has-data="isset($testimonials) && $testimonials->isNotEmpty()"
        empty-icon="fas fa-star"
        empty-message="Chưa có đánh giá nào">
        @include('components.storefront.testimonials')
    </x-storefront-section>

    <!-- FAQ -->
    <x-storefront-section
        title="Câu hỏi thường gặp"
        description="Giải đáp những thắc mắc phổ biến về khóa học"
        bg-color="bg-gray-25"
        :has-data="isset($faqs) && $faqs->isNotEmpty()"
        empty-icon="fas fa-question-circle"
        empty-message="Chưa có câu hỏi nào">
        @include('components.storefront.faq-section')
    </x-storefront-section>

    <!-- Đối tác -->
    <x-storefront-section
        title="Đối tác tin cậy"
        description="Những đối tác đồng hành cùng chúng tôi"
        bg-color="bg-white"
        :has-data="isset($partners) && $partners->isNotEmpty()"
        empty-icon="fas fa-handshake"
        empty-message="Chưa có đối tác nào">
        @include('components.storefront.partners')
    </x-storefront-section>

    <!-- Blog Posts -->
    <x-storefront-section
        title="Bài viết mới nhất"
        description="Cập nhật kiến thức và thông tin hữu ích"
        bg-color="bg-gray-25"
        :has-data="isset($latestPosts) && $latestPosts->isNotEmpty()"
        empty-icon="fas fa-newspaper"
        empty-message="Chưa có bài viết nào">
        @include('components.storefront.blog-posts')
    </x-storefront-section>

    <!-- Call to Action -->
    <section class="storefront-section bg-gradient-to-r from-red-700 via-red-600 to-red-700 text-white relative overflow-hidden">
        @include('components.storefront.homepage-cta')
    </section>
@endsection

{{-- Styles và scripts được handle bởi simple-storefront.css và simple-storefront.js --}}
