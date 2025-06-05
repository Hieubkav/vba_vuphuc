<?php

namespace App\Console\Commands;

use App\Services\DashboardOptimizationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class WarmupDashboardCache extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'dashboard:warmup {--clear : Clear existing cache before warming up}';

    /**
     * The console command description.
     */
    protected $description = 'Warm up dashboard cache for better performance';

    /**
     * Dashboard optimization service
     */
    protected DashboardOptimizationService $dashboardService;

    /**
     * Create a new command instance.
     */
    public function __construct(DashboardOptimizationService $dashboardService)
    {
        parent::__construct();
        $this->dashboardService = $dashboardService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🚀 Bắt đầu warm up dashboard cache...');

        // Clear cache if requested
        if ($this->option('clear')) {
            $this->info('🧹 Xóa cache cũ...');
            $this->dashboardService->clearCache();
        }

        try {
            // Warm up dashboard stats
            $this->info('📊 Warm up thống kê dashboard...');
            $stats = $this->dashboardService->getDashboardStats();
            $this->line("   ✅ Courses: {$stats['courses']['total']} total, {$stats['courses']['active']} active");
            $this->line("   ✅ Students: {$stats['students']['total']} total, {$stats['students']['active']} active");
            $this->line("   ✅ Posts: {$stats['posts']['total']} total, {$stats['posts']['published']} published");
            $this->line("   ✅ Instructors: {$stats['instructors']['total']} total, {$stats['instructors']['active']} active");



            // Warm up navigation data
            $this->info('🧭 Warm up dữ liệu navigation...');
            $this->warmupNavigationData();

            // Warm up other common queries
            $this->info('⚡ Warm up các queries thường dùng...');
            $this->warmupCommonQueries();

            $this->newLine();
            $this->info('🎉 Hoàn thành warm up dashboard cache!');
            $this->info('⏱️  Dashboard sẽ load nhanh hơn trong 5 phút tới.');

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('❌ Lỗi khi warm up cache: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * Warm up navigation data
     */
    private function warmupNavigationData(): void
    {
        Cache::remember('navigation_data', 7200, function () {
            return [
                'mainCategories' => \App\Models\CatPost::where('status', 'active')
                    ->whereNull('parent_id')
                    ->with(['children' => function ($query) {
                        $query->where('status', 'active')->orderBy('order');
                    }])
                    ->orderBy('order')
                    ->get(),

                'menuItems' => \App\Models\MenuItem::where('status', 'active')
                    ->whereNull('parent_id')
                    ->with(['children' => function ($query) {
                        $query->where('status', 'active')->orderBy('order');
                    }])
                    ->orderBy('order')
                    ->get(),
            ];
        });

        $this->line('   ✅ Navigation data cached');
    }

    /**
     * Warm up common queries
     */
    private function warmupCommonQueries(): void
    {
        // Featured courses
        Cache::remember('featured_courses', 1800, function () {
            return \App\Models\Course::where('status', 'active')
                ->where('is_featured', true)
                ->with(['courseCategory', 'instructor'])
                ->orderBy('order')
                ->take(6)
                ->get();
        });
        $this->line('   ✅ Featured courses cached');

        // Recent posts
        Cache::remember('recent_posts', 1800, function () {
            return \App\Models\Post::where('status', 'active')
                ->with(['category'])
                ->orderBy('created_at', 'desc')
                ->take(6)
                ->get();
        });
        $this->line('   ✅ Recent posts cached');

        // Active sliders
        Cache::remember('active_sliders', 3600, function () {
            return \App\Models\Slider::where('status', 'active')
                ->orderBy('order')
                ->get();
        });
        $this->line('   ✅ Active sliders cached');

        // Course categories
        Cache::remember('course_categories', 3600, function () {
            return \App\Models\CatCourse::where('status', 'active')
                ->withCount('courses')
                ->orderBy('order')
                ->get();
        });
        $this->line('   ✅ Course categories cached');

        // Partners/Associations
        Cache::remember('active_partners', 3600, function () {
            return \App\Models\Association::where('status', 'active')
                ->orderBy('order')
                ->get();
        });
        $this->line('   ✅ Partners cached');
    }
}
