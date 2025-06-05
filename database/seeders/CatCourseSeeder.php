<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CatCourse;

class CatCourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('📁 Tạo dữ liệu danh mục khóa học...');

        // Tạo 3 danh mục khóa học làm bánh chính
        $categories = [
            [
                'name' => 'Bánh cơ bản',
                'slug' => 'banh-co-ban',
                'description' => 'Các khóa học làm bánh cơ bản dành cho người mới bắt đầu',
                'seo_title' => 'Khóa học làm bánh cơ bản - VBA Vũ Phúc',
                'seo_description' => 'Học làm bánh từ cơ bản với các kỹ thuật nền tảng. Phù hợp cho người mới bắt đầu muốn học làm bánh chuyên nghiệp.',
                'color' => '#059669', // Green
                'icon' => 'cake',
                'image' => 'cat-courses/banh-co-ban.webp',
                'order' => 1,
                'status' => 'active'
            ],
            [
                'name' => 'Bánh nâng cao',
                'slug' => 'banh-nang-cao',
                'description' => 'Các khóa học làm bánh nâng cao và kỹ thuật chuyên sâu',
                'seo_title' => 'Khóa học làm bánh nâng cao chuyên nghiệp - VBA Vũ Phúc',
                'seo_description' => 'Nâng cao kỹ năng làm bánh với các kỹ thuật chuyên sâu, trang trí bánh và kinh doanh bánh hiệu quả.',
                'color' => '#dc2626', // Red
                'icon' => 'star',
                'image' => 'cat-courses/banh-nang-cao.webp',
                'order' => 2,
                'status' => 'active'
            ],
            [
                'name' => 'Workshop',
                'slug' => 'workshop',
                'description' => 'Các workshop thực hành làm bánh chuyên đề và kỹ thuật đặc biệt',
                'seo_title' => 'Workshop làm bánh chuyên đề - VBA Vũ Phúc',
                'seo_description' => 'Tham gia các workshop thực hành làm bánh chuyên đề, học kỹ thuật mới và xu hướng làm bánh hiện đại.',
                'color' => '#7c3aed', // Purple
                'icon' => 'users',
                'image' => 'cat-courses/workshop-lam-banh.webp',
                'order' => 3,
                'status' => 'active'
            ]
        ];

        foreach ($categories as $categoryData) {
            CatCourse::updateOrCreate([
                'slug' => $categoryData['slug']
            ], $categoryData);
        }

        $this->command->info("✅ Đã tạo " . count($categories) . " danh mục khóa học");
    }
}
