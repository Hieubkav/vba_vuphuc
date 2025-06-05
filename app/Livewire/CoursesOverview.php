<?php

namespace App\Livewire;

use App\Services\CoursesOverviewService;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class CoursesOverview extends Component
{
    public $isLoaded = false;
    public $courseCategoriesData;
    public $criticalImages = [];
    public $structuredData = [];
    public $realStats = [];

    protected $coursesService;

    public function boot()
    {
        $this->coursesService = app(CoursesOverviewService::class);
    }

    public function mount()
    {
        // Lazy load data để tối ưu hiệu suất
        $this->loadCoursesData();
    }

    public function loadCoursesData()
    {
        try {
            // Sử dụng service để lấy dữ liệu tối ưu
            $this->courseCategoriesData = $this->coursesService->getCoursesOverviewData();

            // Preload critical images
            $this->criticalImages = $this->coursesService->getCriticalImages();

            // Get structured data for SEO
            $this->structuredData = $this->coursesService->getStructuredData();

            // Get real statistics from database
            $this->realStats = $this->coursesService->getRealStats();

            $this->isLoaded = true;
        } catch (\Exception $e) {
            Log::error('CoursesOverview Livewire Error: ' . $e->getMessage());
            $this->courseCategoriesData = collect();
            // Fallback stats nếu có lỗi - Updated với dữ liệu thực
            $this->realStats = [
                'students' => 28,
                'courses' => 6,
                'satisfaction_rate' => 32,
                'experience_years' => 3
            ];
            $this->isLoaded = true;
        }
    }

    public function refreshData()
    {
        $this->isLoaded = false;
        $this->coursesService->clearCache();
        $this->loadCoursesData();

        // Emit event để refresh UI
        $this->dispatch('courses-refreshed');
    }

    public function render()
    {
        return view('livewire.courses-overview');
    }
}
