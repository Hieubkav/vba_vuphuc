<?php

namespace App\Services;

use App\Models\WebDesign;
use Illuminate\Support\Facades\Cache;

/**
 * Global CTA Service
 * Quản lý dữ liệu CTA chung cho toàn bộ ứng dụng
 */
class GlobalCtaService
{
    /**
     * Lấy dữ liệu CTA với cache
     */
    public function getCtaData(): array
    {
        return Cache::remember('global_cta_data', 3600, function () {
            $webDesign = WebDesign::first();
            
            if (!$webDesign) {
                return $this->getDefaultCtaData();
            }

            return [
                'enabled' => $webDesign->homepage_cta_enabled ?? true,
                'title' => $webDesign->homepage_cta_title ?? 'Bắt đầu hành trình với VBA Vũ Phúc',
                'description' => $webDesign->homepage_cta_description ?? 'Khám phá các khóa học VBA chất lượng cao và chuyên sâu. Học tập hiệu quả, hỗ trợ tận tâm từ giảng viên.',
                'primary_button_text' => $webDesign->homepage_cta_primary_button_text ?? 'Xem khóa học',
                'primary_button_url' => $webDesign->homepage_cta_primary_button_url ?? '/courses',
                'secondary_button_text' => $webDesign->homepage_cta_secondary_button_text ?? 'Đăng ký học',
                'secondary_button_url' => $webDesign->homepage_cta_secondary_button_url ?? '/students/register',
                'bg_color' => 'bg-gradient-to-r from-red-700 via-red-600 to-red-700',
            ];
        });
    }

    /**
     * Dữ liệu CTA mặc định
     */
    private function getDefaultCtaData(): array
    {
        return [
            'enabled' => true,
            'title' => 'Bắt đầu hành trình với VBA Vũ Phúc',
            'description' => 'Khám phá các khóa học VBA chất lượng cao và chuyên sâu. Học tập hiệu quả, hỗ trợ tận tâm từ giảng viên.',
            'primary_button_text' => 'Xem khóa học',
            'primary_button_url' => '/courses',
            'secondary_button_text' => 'Đăng ký học',
            'secondary_button_url' => '/students/register',
            'bg_color' => 'bg-gradient-to-r from-red-700 via-red-600 to-red-700',
        ];
    }

    /**
     * Xóa cache CTA
     */
    public function clearCache(): void
    {
        Cache::forget('global_cta_data');
    }

    /**
     * Kiểm tra CTA có được bật không
     */
    public function isEnabled(): bool
    {
        $data = $this->getCtaData();
        return $data['enabled'] ?? true;
    }
}
