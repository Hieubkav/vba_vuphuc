@extends('layouts.shop')

@section('title', 'Nhóm học tập - VBA Vũ Phúc')
@section('description', 'Tham gia các nhóm học tập VBA Excel để kết nối với cộng đồng, chia sẻ kinh nghiệm và nhận hỗ trợ từ giảng viên.')

@push('styles')
<style>
    .filter-card {
        @apply bg-white border border-gray-100 shadow-sm;
    }

    .mobile-sidebar {
        display: none;
    }

    .mobile-sidebar.active {
        display: block;
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
                <span class="text-white">Nhóm học tập</span>
            </div>
        </nav>
        <h1 class="text-xl md:text-2xl font-bold text-white font-montserrat">
            Nhóm học tập VBA Excel
        </h1>
    </div>
</section>

<!-- Main Content -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <livewire:course-group-list />
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
