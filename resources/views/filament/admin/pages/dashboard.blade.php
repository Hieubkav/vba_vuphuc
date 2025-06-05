<x-filament-panels::page class="fi-dashboard-page">
    <!-- Dashboard Header với gradient đẹp mắt -->
    <div class="dashboard-header">
        <div class="flex items-center justify-between flex-wrap gap-6">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-4 mb-3">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <span class="text-2xl">🎓</span>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white dark:text-gray-100">VBA Vũ Phúc</h1>
                        <p class="text-blue-100 dark:text-blue-200 text-sm">Hệ thống quản lý khóa học chuyên nghiệp</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 text-sm text-blue-100">
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                        </svg>
                        Chào mừng, {{ auth()->user()->name }}
                    </span>
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                        </svg>
                        {{ now()->format('d/m/Y') }}
                    </span>
                </div>
            </div>
            <div class="text-right flex-shrink-0">
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                    <div class="text-sm text-blue-100 mb-1">Thời gian hiện tại</div>
                    <div class="text-2xl font-bold text-white" id="current-time">{{ now()->format('H:i:s') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid mb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Khóa học -->
            <div class="stat-card bg-gradient-to-br from-blue-500 to-blue-600">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Tổng khóa học</p>
                        <p class="text-3xl font-bold text-white">{{ \App\Models\Course::count() }}</p>
                        <p class="text-blue-200 text-xs mt-1">
                            {{ \App\Models\Course::where('status', 'active')->count() }} đang hoạt động
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Học viên -->
            <div class="stat-card bg-gradient-to-br from-green-500 to-green-600">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Tổng học viên</p>
                        <p class="text-3xl font-bold text-white">{{ \App\Models\Student::count() }}</p>
                        <p class="text-green-200 text-xs mt-1">
                            {{ \App\Models\Student::where('status', 'active')->count() }} đang học
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Bài viết -->
            <div class="stat-card bg-gradient-to-br from-purple-500 to-purple-600">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">Tổng bài viết</p>
                        <p class="text-3xl font-bold text-white">{{ \App\Models\Post::count() }}</p>
                        <p class="text-purple-200 text-xs mt-1">
                            {{ \App\Models\Post::where('status', 'active')->count() }} đã xuất bản
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Giảng viên -->
            <div class="stat-card bg-gradient-to-br from-orange-500 to-orange-600">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 text-sm font-medium">Tổng giảng viên</p>
                        <p class="text-3xl font-bold text-white">{{ \App\Models\Instructor::count() }}</p>
                        <p class="text-orange-200 text-xs mt-1">
                            {{ \App\Models\Instructor::where('status', 'active')->count() }} đang hoạt động
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>



    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Cập nhật thời gian hiện tại với hiệu ứng mượt mà
                function updateCurrentTime() {
                    const now = new Date();
                    const timeString = now.toLocaleTimeString('vi-VN', {
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit'
                    });
                    const timeElement = document.getElementById('current-time');
                    if (timeElement) {
                        // Thêm hiệu ứng fade khi cập nhật
                        timeElement.style.opacity = '0.7';
                        setTimeout(() => {
                            timeElement.textContent = timeString;
                            timeElement.style.opacity = '1';
                        }, 100);
                    }
                }

                // Thêm hiệu ứng hover cho stat cards
                const statCards = document.querySelectorAll('.stat-card');
                statCards.forEach(card => {
                    card.addEventListener('mouseenter', function() {
                        this.style.transform = 'translateY(-4px) scale(1.02)';
                    });

                    card.addEventListener('mouseleave', function() {
                        this.style.transform = 'translateY(0) scale(1)';
                    });
                });

                // Thêm hiệu ứng loading cho quick actions
                const quickActions = document.querySelectorAll('.quick-action-btn');
                quickActions.forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        // Thêm hiệu ứng ripple
                        const ripple = document.createElement('div');
                        ripple.style.cssText = `
                            position: absolute;
                            border-radius: 50%;
                            background: rgba(255,255,255,0.6);
                            transform: scale(0);
                            animation: ripple 0.6s linear;
                            pointer-events: none;
                        `;

                        const rect = this.getBoundingClientRect();
                        const size = Math.max(rect.width, rect.height);
                        ripple.style.width = ripple.style.height = size + 'px';
                        ripple.style.left = (e.clientX - rect.left - size / 2) + 'px';
                        ripple.style.top = (e.clientY - rect.top - size / 2) + 'px';

                        this.style.position = 'relative';
                        this.appendChild(ripple);

                        setTimeout(() => {
                            ripple.remove();
                        }, 600);
                    });
                });

                // Cập nhật thời gian ngay khi load và mỗi giây
                updateCurrentTime();
                setInterval(updateCurrentTime, 1000);

                // Thêm animation cho các elements khi load
                const animatedElements = document.querySelectorAll('.stat-card, .quick-action-btn');
                animatedElements.forEach((el, index) => {
                    el.style.opacity = '0';
                    el.style.transform = 'translateY(20px)';

                    setTimeout(() => {
                        el.style.transition = 'all 0.5s ease';
                        el.style.opacity = '1';
                        el.style.transform = 'translateY(0)';
                    }, index * 100);
                });
            });

            // Thêm CSS cho ripple effect
            const style = document.createElement('style');
            style.textContent = `
                @keyframes ripple {
                    to {
                        transform: scale(4);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);
        </script>
    @endpush

    @push('styles')
        <style>
            /* Dashboard Styles - Dark/Light Mode Compatible */
            .fi-dashboard-page {
                @apply bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800;
                min-height: 100vh;
                transition: background 0.3s ease;
            }

            /* Dashboard Header - Theme Compatible */
            .dashboard-header {
                @apply bg-gradient-to-br from-blue-600 via-blue-500 to-indigo-600 dark:from-blue-700 dark:via-blue-600 dark:to-indigo-700;
                @apply text-white dark:text-gray-100;
                @apply shadow-lg shadow-blue-500/25 dark:shadow-blue-600/25;
                @apply rounded-xl p-6 mb-8;
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            }

            .dashboard-header::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: linear-gradient(45deg, rgba(255,255,255,0.1) 0%, transparent 50%);
                pointer-events: none;
            }

            .dashboard-header:hover {
                @apply shadow-xl shadow-blue-500/30 dark:shadow-blue-600/30;
                transform: translateY(-1px);
            }

            /* Stats Cards */
            .stats-grid {
                @apply mb-8;
            }

            .stat-card {
                @apply rounded-xl p-6 shadow-lg border border-white/20;
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            }

            .stat-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, transparent 50%);
                pointer-events: none;
            }

            .stat-card:hover {
                transform: translateY(-2px);
                @apply shadow-xl;
            }

            /* Quick Action Buttons */
            .quick-action-btn {
                @apply flex flex-col items-center p-4 rounded-xl border border-gray-200 dark:border-gray-700;
                @apply bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700;
                @apply transition-all duration-200 ease-in-out;
                text-decoration: none;
            }

            .quick-action-btn:hover {
                @apply shadow-md border-gray-300 dark:border-gray-600;
                transform: translateY(-1px);
            }

            /* Responsive Improvements */
            @media (max-width: 768px) {
                .dashboard-header {
                    @apply p-4;
                }

                .dashboard-header h1 {
                    @apply text-xl;
                }

                .welcome-content .grid {
                    @apply grid-cols-1;
                }
            }

            /* Smooth transitions */
            .fi-dashboard-page * {
                transition: color 0.3s ease, background-color 0.3s ease, border-color 0.3s ease;
            }
        </style>
    @endpush
</x-filament-panels::page>
