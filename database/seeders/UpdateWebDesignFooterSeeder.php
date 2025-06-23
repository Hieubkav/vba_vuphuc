<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UpdateWebDesignFooterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $webDesign = \App\Models\WebDesign::first();

        if ($webDesign) {
            $webDesign->update([
                'footer_enabled' => true,
                'footer_order' => 10,
                'footer_policy_1_title' => 'Chính sách & Điều khoản',
                'footer_policy_1_type' => 'custom',
                'footer_policy_1_url' => '#',
                'footer_policy_1_post' => null,
                'footer_policy_2_title' => 'Hệ thống đại lý',
                'footer_policy_2_type' => 'custom',
                'footer_policy_2_url' => '#',
                'footer_policy_2_post' => null,
                'footer_policy_3_title' => 'Bảo mật & Quyền riêng tư',
                'footer_policy_3_type' => 'custom',
                'footer_policy_3_url' => '#',
                'footer_policy_3_post' => null,
                'footer_copyright' => '© ' . date('Y') . ' Copyright by VBA Vũ Phúc - All Rights Reserved',
            ]);

            $this->command->info('✅ Đã cập nhật footer cho WebDesign');
        } else {
            \App\Models\WebDesign::create([
                'hero_banner_enabled' => true,
                'hero_banner_order' => 1,
                'courses_overview_enabled' => true,
                'courses_overview_order' => 2,
                'courses_overview_title' => 'Khóa học VBA Excel chuyên nghiệp',
                'courses_overview_description' => 'Nâng cao kỹ năng Excel với các khóa học VBA từ cơ bản đến nâng cao',
                'courses_overview_bg_color' => 'bg-white',
                'courses_overview_animation_class' => 'animate-fade-in-optimized',
                'footer_enabled' => true,
                'footer_order' => 10,
                'footer_policy_1_title' => 'Chính sách & Điều khoản',
                'footer_policy_1_url' => '#',
                'footer_policy_2_title' => 'Hệ thống đại lý',
                'footer_policy_2_url' => '#',
                'footer_policy_3_title' => 'Bảo mật & Quyền riêng tư',
                'footer_policy_3_url' => '#',
                'footer_copyright' => '© ' . date('Y') . ' Copyright by VBA Vũ Phúc - All Rights Reserved',
            ]);

            $this->command->info('✅ Đã tạo WebDesign mới với footer');
        }
    }
}
