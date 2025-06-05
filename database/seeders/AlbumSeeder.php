<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Album;
use App\Models\AlbumImage;
use Illuminate\Support\Str;

class AlbumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $albums = [
            [
                'title' => 'Album Khóa học Bánh Kem Cơ Bản',
                'description' => 'Tổng hợp hình ảnh và tài liệu từ khóa học bánh kem cơ bản dành cho người mới bắt đầu.',
                'slug' => 'album-khoa-hoc-banh-kem-co-ban',
                'published_date' => now()->subDays(30),
                'featured' => true,
                'total_pages' => 25,
                'status' => 'active',
                'order' => 1,
            ],
            [
                'title' => 'Album Khóa học Bánh Ngọt Nâng Cao',
                'description' => 'Những khoảnh khắc đáng nhớ trong khóa học bánh ngọt nâng cao với các kỹ thuật chuyên nghiệp.',
                'slug' => 'album-khoa-hoc-banh-ngot-nang-cao',
                'published_date' => now()->subDays(20),
                'featured' => false,
                'total_pages' => 35,
                'status' => 'active',
                'order' => 2,
            ],
            [
                'title' => 'Album Hội thảo Xu hướng Bánh 2024',
                'description' => 'Hình ảnh và tài liệu từ hội thảo về xu hướng bánh mới nhất năm 2024.',
                'slug' => 'album-hoi-thao-xu-huong-banh-2024',
                'published_date' => now()->subDays(10),
                'featured' => true,
                'total_pages' => 18,
                'status' => 'active',
                'order' => 3,
            ],
            [
                'title' => 'Album Khóa học Bánh Mì Artisan',
                'description' => 'Quá trình học tập và thực hành trong khóa học bánh mì artisan chuyên nghiệp.',
                'slug' => 'album-khoa-hoc-banh-mi-artisan',
                'published_date' => now()->subDays(5),
                'featured' => false,
                'total_pages' => 42,
                'status' => 'active',
                'order' => 4,
            ],
            [
                'title' => 'Album Workshop Trang trí Bánh',
                'description' => 'Những tác phẩm nghệ thuật từ workshop trang trí bánh với các kỹ thuật độc đáo.',
                'slug' => 'album-workshop-trang-tri-banh',
                'published_date' => now()->subDays(2),
                'featured' => false,
                'total_pages' => 28,
                'status' => 'active',
                'order' => 5,
            ],
        ];

        foreach ($albums as $albumData) {
            // Tạo SEO fields tự động
            $albumData['seo_title'] = $albumData['title'] . ' - VBA Vũ Phúc';
            $albumData['seo_description'] = Str::limit($albumData['description'], 150);

            // Thêm file PDF mẫu
            $albumData['pdf_file'] = 'albums/pdfs/sample-album-' . $albumData['order'] . '.pdf';
            $albumData['thumbnail'] = 'albums/thumbnails/sample-thumbnail-' . $albumData['order'] . '.svg';

            $album = Album::updateOrCreate(
                ['slug' => $albumData['slug']],
                $albumData
            );

            // Xóa ảnh cũ nếu có
            $album->images()->delete();

            // Tạo ảnh mẫu cho mỗi album
            $imageCount = rand(3, 6);
            for ($i = 1; $i <= $imageCount; $i++) {
                // Sử dụng ảnh mẫu có sẵn hoặc tạo tên file động
                $imagePath = 'albums/images/sample-' . $album->id . '-' . $i . '.svg';

                // Nếu không có file cụ thể, sử dụng file mẫu chung
                if (!file_exists(storage_path('app/public/' . $imagePath))) {
                    $imagePath = 'albums/images/sample-1-' . (($i - 1) % 3 + 1) . '.svg';
                }

                AlbumImage::create([
                    'album_id' => $album->id,
                    'image_path' => $imagePath,
                    'alt_text' => $album->title . ' - Hình ' . $i,
                    'caption' => 'Hình ảnh ' . $i . ' từ ' . $album->title,
                    'order' => $i,
                    'status' => 'active',
                    'is_featured' => $i === 1, // Ảnh đầu tiên là featured
                ]);
            }
        }
    }
}
