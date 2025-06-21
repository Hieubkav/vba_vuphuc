@extends('layouts.shop')

@section('title', 'Danh sách khóa học - VBA Vũ Phúc')
@section('description', 'Khám phá các khóa học chất lượng cao tại VBA Vũ Phúc. Học từ các chuyên gia hàng đầu với phương pháp giảng dạy hiện đại.')

@push('styles')
<style>
    .hero-pattern {
        background-image:
            radial-gradient(circle at 25% 25%, rgba(255,255,255,0.1) 2px, transparent 2px),
            radial-gradient(circle at 75% 75%, rgba(255,255,255,0.1) 2px, transparent 2px);
        background-size: 50px 50px;
    }

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

    .course-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .course-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    .filter-btn {
        transition: all 0.2s ease;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
    }

    .filter-btn:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
        color: #dc2626;
    }

    .filter-btn.active {
        background: #dc2626;
        color: white;
        border-color: #dc2626;
        box-shadow: 0 1px 3px 0 rgba(220, 38, 38, 0.3);
    }

    .mobile-sidebar {
        display: none;
    }

    .mobile-sidebar.active {
        display: block;
        position: fixed;
        inset: 0;
        z-index: 50;
        background: rgba(0, 0, 0, 0.5);
    }

    .mobile-sidebar-content {
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        width: 320px;
        background: white;
        transform: translateX(-100%);
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
                <span class="text-white">Khóa học</span>
            </div>
        </nav>
        <h1 class="text-xl md:text-2xl font-bold text-white font-montserrat">
            Khóa học chất lượng cao
        </h1>
    </div>
</section>

<!-- Main Content -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <livewire:course-list />
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
