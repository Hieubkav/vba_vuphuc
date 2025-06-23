@extends('layouts.shop')

@section('title', 'Danh mục bài viết - VBA Vũ Phúc')
@section('description', 'Khám phá các danh mục bài viết về Excel, VBA và các kỹ năng văn phòng tại VBA Vũ Phúc')

@push('styles')
<style>
    .filter-card {
        background: white;
        border: 1px solid #f1f5f9;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        transition: all 0.2s ease;
    }

    .filter-card:hover {
        border-color: rgba(220, 38, 38, 0.2);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .post-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .post-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    .mobile-sidebar {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 50;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .mobile-sidebar.active {
        opacity: 1;
        visibility: visible;
    }

    .mobile-sidebar-content {
        position: absolute;
        top: 0;
        right: 0;
        width: 320px;
        max-width: 90vw;
        height: 100vh;
        background: white;
        transform: translateX(100%);
        transition: transform 0.3s ease;
        overflow-y: auto;
    }

    .mobile-sidebar.active .mobile-sidebar-content {
        transform: translateX(0);
    }

    @media (min-width: 1024px) {
        .mobile-sidebar {
            display: none !important;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-r from-red-600 to-red-700">
    <div class="container mx-auto px-4 py-4">
        <nav class="mb-1">
            <div class="flex items-center space-x-2 text-white/80 text-xs font-open-sans">
                <a href="{{ route('storeFront') }}" class="hover:text-white transition-colors">Trang chủ</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-white">Chuyên mục bài viết</span>
            </div>
        </nav>
        <h1 class="text-xl md:text-2xl font-bold text-white font-montserrat">
            Bài viết chất lượng cao
        </h1>
    </div>
</section>

<!-- Main Content -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <livewire:post-list />
    </div>
</section>
@endsection

@push('scripts')
<script>
function toggleSidebar() {
    const mobileSidebar = document.querySelector('.mobile-sidebar');
    mobileSidebar.classList.toggle('active');

    // Prevent body scroll when sidebar is open
    if (mobileSidebar.classList.contains('active')) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = '';
    }
}

// Copy filter content to mobile sidebar
document.addEventListener('DOMContentLoaded', function() {
    const desktopContent = document.getElementById('desktop-filter-content');
    const mobileContent = document.getElementById('mobile-filter-content');

    if (desktopContent && mobileContent) {
        mobileContent.innerHTML = desktopContent.innerHTML;
    }

    // Close sidebar when clicking outside
    document.addEventListener('click', function(e) {
        const mobileSidebar = document.querySelector('.mobile-sidebar');
        const sidebarContent = document.querySelector('.mobile-sidebar-content');

        if (mobileSidebar && mobileSidebar.classList.contains('active')) {
            if (!sidebarContent.contains(e.target) && !e.target.closest('[onclick*="toggleSidebar"]')) {
                toggleSidebar();
            }
        }
    });
});
</script>
@endpush
