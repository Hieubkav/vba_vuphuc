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

        // Tạo danh mục khóa học - sẽ hiển thị tất cả danh mục active, >3 danh mục sẽ thành swiper
        $categories = [
            [
                'name' => 'Bánh cơ bản',
                'slug' => 'banh-co-ban',
                'description' => 'Các khóa học làm bánh cơ bản dành cho người mới bắt đầu',
                'seo_title' => 'Khóa học làm bánh cơ bản - VBA Vũ Phúc',
                'seo_description' => 'Học làm bánh từ cơ bản với các kỹ thuật nền tảng. Phù hợp cho người mới bắt đầu muốn học làm bánh chuyên nghiệp.',
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
                'image' => 'cat-courses/workshop-lam-banh.webp',
                'order' => 3,
                'status' => 'active'
            ],
            [
                'name' => 'Bánh Âu',
                'slug' => 'banh-au',
                'description' => 'Khóa học làm bánh Âu với kỹ thuật truyền thống và hiện đại',
                'seo_title' => 'Khóa học làm bánh Âu - VBA Vũ Phúc',
                'seo_description' => 'Học làm bánh Âu với các công thức truyền thống và kỹ thuật hiện đại từ các đầu bếp chuyên nghiệp.',
                'image' => 'cat-courses/banh-au.webp',
                'order' => 4,
                'status' => 'active'
            ],
            [
                'name' => 'Bánh Á',
                'slug' => 'banh-a',
                'description' => 'Khóa học làm bánh Á với hương vị đặc trưng châu Á',
                'seo_title' => 'Khóa học làm bánh Á - VBA Vũ Phúc',
                'seo_description' => 'Khám phá nghệ thuật làm bánh Á với các hương vị đặc trưng và kỹ thuật truyền thống.',
                'image' => 'cat-courses/banh-a.webp',
                'order' => 5,
                'status' => 'active'
            ],
            [
                'name' => 'Bánh sinh nhật',
                'slug' => 'banh-sinh-nhat',
                'description' => 'Khóa học trang trí và làm bánh sinh nhật chuyên nghiệp',
                'seo_title' => 'Khóa học làm bánh sinh nhật - VBA Vũ Phúc',
                'seo_description' => 'Học cách làm và trang trí bánh sinh nhật đẹp mắt, chuyên nghiệp với nhiều kỹ thuật độc đáo.',
                'image' => 'cat-courses/banh-sinh-nhat.webp',
                'order' => 6,
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
