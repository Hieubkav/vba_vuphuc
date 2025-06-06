<x-filament-panels::page class="fi-dashboard-page">
    <!-- Clean Dashboard Header -->
    <div class="dashboard-header-clean">
        <div class="flex items-center justify-between flex-wrap gap-6">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-4 mb-3">
                    <div class="w-12 h-12 bg-red-600 rounded-xl flex items-center justify-center shadow-lg">
                        <span class="text-2xl">🎓</span>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">VBA Vũ Phúc</h1>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">Hệ thống quản lý khóa học chuyên nghiệp</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
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
                <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-lg border border-gray-200 dark:border-gray-700">
                    <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Thời gian hiện tại</div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-gray-100" id="current-time">{{ now()->format('H:i:s') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Clean Stats Cards -->
    <div class="stats-grid mb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Khóa học -->
            <div class="clean-stat-card group">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-2">Tổng khóa học</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-1">{{ \App\Models\Course::count() }}</p>
                        <p class="text-blue-600 dark:text-blue-400 text-xs font-medium">
                            {{ \App\Models\Course::where('status', 'active')->count() }} đang hoạt động
                        </p>
                    </div>
                    <div class="w-14 h-14 bg-blue-100 dark:bg-blue-900/30 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Học viên -->
            <div class="clean-stat-card group">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-2">Tổng học viên</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-1">{{ \App\Models\Student::count() }}</p>
                        <p class="text-green-600 dark:text-green-400 text-xs font-medium">
                            {{ \App\Models\Student::where('status', 'active')->count() }} đang học
                        </p>
                    </div>
                    <div class="w-14 h-14 bg-green-100 dark:bg-green-900/30 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Bài viết -->
            <div class="clean-stat-card group">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-2">Tổng bài viết</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-1">{{ \App\Models\Post::count() }}</p>
                        <p class="text-purple-600 dark:text-purple-400 text-xs font-medium">
                            {{ \App\Models\Post::where('status', 'active')->count() }} đã xuất bản
                        </p>
                    </div>
                    <div class="w-14 h-14 bg-purple-100 dark:bg-purple-900/30 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-purple-600 dark:text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Giảng viên -->
            <div class="clean-stat-card group">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-2">Tổng giảng viên</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-1">{{ \App\Models\Instructor::count() }}</p>
                        <p class="text-orange-600 dark:text-orange-400 text-xs font-medium">
                            {{ \App\Models\Instructor::where('status', 'active')->count() }} đang hoạt động
                        </p>
                    </div>
                    <div class="w-14 h-14 bg-orange-100 dark:bg-orange-900/30 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-orange-600 dark:text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Realtime Stats Section -->
    <div class="realtime-stats-section mb-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                </svg>
                Thống kê Realtime
            </h2>
            <div class="text-right">
                <div class="text-xs text-gray-500 dark:text-gray-400">
                    Tổng {{ \App\Models\Visitor::count() }} records • Cập nhật {{ now()->format('H:i:s') }}
                </div>
                <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                    Auto-refresh mỗi 30 giây
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            @php
                try {
                    $visitorService = new \App\Services\VisitorStatsService();
                    $realtimeStats = $visitorService->getRealtimeStats();
                } catch (\Exception $e) {
                    $realtimeStats = [
                        'unique_visitors_today' => 0,
                        'total_page_views_today' => 0,
                        'unique_visitors_total' => 0,
                        'total_page_views_total' => 0,
                        'top_courses' => []
                    ];
                }
            @endphp

            <!-- Người truy cập hôm nay -->
            <div class="realtime-stat-card group">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-2">Người truy cập hôm nay</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-1">{{ number_format($realtimeStats['unique_visitors_today']) }}</p>
                        <p class="text-emerald-600 dark:text-emerald-400 text-xs font-medium">Unique visitors</p>
                    </div>
                    <div class="w-14 h-14 bg-emerald-100 dark:bg-emerald-900/30 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-emerald-600 dark:text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Lượt truy cập hôm nay -->
            <div class="realtime-stat-card group">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-2">Lượt truy cập hôm nay</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-1">{{ number_format($realtimeStats['total_page_views_today']) }}</p>
                        <p class="text-cyan-600 dark:text-cyan-400 text-xs font-medium">Page views</p>
                    </div>
                    <div class="w-14 h-14 bg-cyan-100 dark:bg-cyan-900/30 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-cyan-600 dark:text-cyan-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Tổng người truy cập -->
            <div class="realtime-stat-card group">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-2">Tổng người truy cập</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-1">{{ number_format($realtimeStats['unique_visitors_total']) }}</p>
                        <p class="text-rose-600 dark:text-rose-400 text-xs font-medium">Tất cả thời gian</p>
                    </div>
                    <div class="w-14 h-14 bg-rose-100 dark:bg-rose-900/30 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-rose-600 dark:text-rose-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Tổng lượt truy cập -->
            <div class="realtime-stat-card group">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-2">Tổng lượt truy cập</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-1">{{ number_format($realtimeStats['total_page_views_total']) }}</p>
                        <p class="text-indigo-600 dark:text-indigo-400 text-xs font-medium">Tất cả thời gian</p>
                    </div>
                    <div class="w-14 h-14 bg-indigo-100 dark:bg-indigo-900/30 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-indigo-600 dark:text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Courses Section -->
    <div class="top-courses-section mb-8">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 2L3 7v11a1 1 0 001 1h3a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h3a1 1 0 001-1V7l-7-5z" clip-rule="evenodd"/>
            </svg>
            Top 3 khóa học được truy cập nhiều nhất
        </h2>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
            @if(!empty($realtimeStats['top_courses']) && count($realtimeStats['top_courses']) > 0)
                <div class="space-y-4">
                    @foreach($realtimeStats['top_courses'] as $index => $item)
                        @php
                            $course = $item['course'] ?? null;
                            $views = $item['views'] ?? 0;
                            $rankColors = ['text-yellow-600', 'text-gray-500', 'text-orange-600'];
                            $rankBgs = ['bg-yellow-100', 'bg-gray-100', 'bg-orange-100'];
                            $rankIcons = ['🏆', '🥈', '🥉'];
                        @endphp

                        <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                            <!-- Rank -->
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 {{ $rankBgs[$index] ?? 'bg-gray-100' }} rounded-full flex items-center justify-center">
                                    <span class="text-2xl">{{ $rankIcons[$index] ?? '🎖️' }}</span>
                                </div>
                            </div>

                            <!-- Course Info -->
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                    {{ $course->title ?? 'Khóa học không xác định' }}
                                </h4>
                                @if($course && $course->slug)
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        <a href="{{ route('courses.show', $course->slug) }}"
                                           target="_blank"
                                           class="hover:text-blue-600 dark:hover:text-blue-400">
                                            Xem khóa học →
                                        </a>
                                    </p>
                                @endif
                            </div>

                            <!-- Views Count -->
                            <div class="flex-shrink-0 text-right">
                                <div class="text-lg font-bold text-blue-600 dark:text-blue-400">
                                    {{ number_format($views) }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    lượt xem
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-600">
                    <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                        <span>Cập nhật realtime</span>
                        <span>{{ now()->format('H:i d/m/Y') }}</span>
                    </div>
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Chưa có dữ liệu truy cập khóa học
                    </p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                        Dữ liệu sẽ xuất hiện khi có người truy cập các trang khóa học
                    </p>
                </div>
            @endif
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

                // Clean hover effects for stat cards
                const statCards = document.querySelectorAll('.clean-stat-card, .realtime-stat-card');
                statCards.forEach((card, index) => {
                    // Add staggered animation on load
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';

                    setTimeout(() => {
                        card.style.transition = 'all 0.4s ease';
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, index * 100);
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

                // Auto-refresh realtime stats mỗi 30 giây
                function refreshRealtimeStats() {
                    // Tạo endpoint API riêng cho việc refresh stats
                    fetch('/api/realtime-stats')
                        .then(response => response.json())
                        .then(data => {
                            if (data.success && data.stats) {
                                // Cập nhật các số liệu realtime
                                updateStatCard('unique_visitors_today', data.stats.unique_visitors_today);
                                updateStatCard('total_page_views_today', data.stats.total_page_views_today);
                                updateStatCard('unique_visitors_total', data.stats.unique_visitors_total);
                                updateStatCard('total_page_views_total', data.stats.total_page_views_total);

                                console.log('✅ Realtime stats updated:', data.stats);
                            }
                        })
                        .catch(error => {
                            console.log('⚠️ Failed to update realtime stats:', error);
                        });
                }

                function updateStatCard(type, value) {
                    const cards = document.querySelectorAll('.realtime-stat-card');
                    cards.forEach(card => {
                        const title = card.querySelector('p').textContent.toLowerCase();
                        let shouldUpdate = false;

                        if (type === 'unique_visitors_today' && title.includes('người truy cập hôm nay')) {
                            shouldUpdate = true;
                        } else if (type === 'total_page_views_today' && title.includes('lượt truy cập hôm nay')) {
                            shouldUpdate = true;
                        } else if (type === 'unique_visitors_total' && title.includes('tổng người truy cập')) {
                            shouldUpdate = true;
                        } else if (type === 'total_page_views_total' && title.includes('tổng lượt truy cập')) {
                            shouldUpdate = true;
                        }

                        if (shouldUpdate) {
                            const numberElement = card.querySelector('.text-3xl');
                            if (numberElement) {
                                // Simple update animation
                                numberElement.style.transition = 'all 0.3s ease';
                                numberElement.style.opacity = '0.7';

                                setTimeout(() => {
                                    numberElement.textContent = new Intl.NumberFormat('vi-VN').format(value);
                                    numberElement.style.opacity = '1';
                                }, 150);
                            }
                        }
                    });
                }

                // Refresh stats ngay khi load và mỗi 30 giây
                setTimeout(refreshRealtimeStats, 2000); // Delay 2s để trang load xong
                setInterval(refreshRealtimeStats, 30000); // Mỗi 30 giây

                // Clean page load animations
                const headerElement = document.querySelector('.dashboard-header-clean');
                if (headerElement) {
                    headerElement.style.opacity = '0';
                    headerElement.style.transform = 'translateY(-10px)';

                    setTimeout(() => {
                        headerElement.style.transition = 'all 0.5s ease';
                        headerElement.style.opacity = '1';
                        headerElement.style.transform = 'translateY(0)';
                    }, 100);
                }

                // Animate sections with stagger
                const sections = document.querySelectorAll('.realtime-stats-section, .top-courses-section');
                sections.forEach((section, index) => {
                    section.style.opacity = '0';
                    section.style.transform = 'translateY(30px)';

                    setTimeout(() => {
                        section.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
                        section.style.opacity = '1';
                        section.style.transform = 'translateY(0)';
                    }, (index + 1) * 200 + 800); // After stat cards
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
            /* Clean Dashboard Styles */
            .fi-dashboard-page {
                @apply bg-gray-50 dark:bg-gray-900;
                min-height: 100vh;
                transition: background-color 0.3s ease;
            }

            /* Clean Dashboard Header */
            .dashboard-header-clean {
                @apply bg-white dark:bg-gray-800;
                @apply shadow-lg border border-gray-200 dark:border-gray-700;
                @apply rounded-2xl p-6 mb-8;
                transition: all 0.3s ease;
            }

            .dashboard-header-clean:hover {
                @apply shadow-xl;
                transform: translateY(-1px);
            }

            /* Clean Stats Cards */
            .stats-grid {
                @apply mb-8;
            }

            .clean-stat-card, .realtime-stat-card {
                @apply bg-white dark:bg-gray-800;
                @apply border border-gray-200 dark:border-gray-700;
                @apply rounded-2xl p-6 shadow-lg;
                transition: all 0.3s ease;
            }

            .clean-stat-card:hover, .realtime-stat-card:hover {
                @apply shadow-xl border-gray-300 dark:border-gray-600;
                transform: translateY(-2px);
            }

            /* Realtime Stats Section */
            .realtime-stats-section {
                @apply mb-8;
            }

            /* Top Courses Section */
            .top-courses-section .bg-white {
                @apply rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700;
            }

            .dark .top-courses-section .bg-white {
                @apply bg-gray-800;
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

            /* Simple Animations */
            @keyframes slideInUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Responsive Design */
            @media (max-width: 768px) {
                .dashboard-header-clean {
                    @apply p-4 rounded-xl;
                }

                .dashboard-header-clean h1 {
                    @apply text-2xl;
                }

                .clean-stat-card, .realtime-stat-card {
                    @apply p-4 rounded-xl;
                }
            }
                }
            }

            /* Smooth transitions */
            .fi-dashboard-page * {
                transition: color 0.3s ease, background-color 0.3s ease, border-color 0.3s ease;
            }
        </style>
    @endpush
</x-filament-panels::page>
