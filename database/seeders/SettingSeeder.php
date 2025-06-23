<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('⚙️ Tạo dữ liệu cấu hình website...');

        Setting::updateOrCreate(
            ['id' => 1],
            [
                'site_name' => 'VBA Vũ Phúc',
                'logo_link' => 'settings/logo-vba-vu-phuc.webp',
                'favicon_link' => 'settings/favicon.ico',
                'seo_title' => 'VBA Vũ Phúc - Khóa học làm bánh chuyên nghiệp',
                'seo_description' => 'VBA Vũ Phúc cung cấp các khóa học làm bánh chuyên nghiệp, từ cơ bản đến nâng cao. Đào tạo kỹ năng thực hành với giảng viên giàu kinh nghiệm.',
                'og_image_link' => 'settings/og-image-vba-vu-phuc.webp',
                'placeholder_image' => 'settings/placeholder.webp',
                'hotline' => '0123.456.789',
                'address' => '123 Đường ABC, Phường XYZ, Quận 1, TP.HCM',
                'email' => 'contact@vbavuphuc.com',
                'slogan' => 'Nâng tầm kỹ năng làm bánh - Thành công trong nghề nghiệp',
                'facebook_link' => 'https://facebook.com/vbavuphuc',
                'zalo_link' => 'https://zalo.me/vbavuphuc',
                'youtube_link' => 'https://youtube.com/@vbavuphuc',
                'tiktok_link' => 'https://tiktok.com/@vbavuphuc',
                'messenger_link' => 'https://m.me/vbavuphuc',
                'working_hours' => 'Thứ 2 - Thứ 6: 8:00 - 17:00, Thứ 7: 8:00 - 12:00',
                'status' => 'active',
                'order' => 1,
            ]
        );

        $this->command->info('✅ Đã tạo cấu hình website');
    }
}
