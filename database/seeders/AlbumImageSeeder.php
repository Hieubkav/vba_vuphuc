<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AlbumImage;
use App\Models\Album;

class AlbumImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('📸 Tạo dữ liệu hình ảnh album...');

        $albums = Album::all();
        $imageCount = 0;

        foreach ($albums as $album) {
            $numImages = rand(5, 15); // Mỗi album có 5-15 ảnh
            
            for ($i = 1; $i <= $numImages; $i++) {
                AlbumImage::create([
                    'album_id' => $album->id,
                    'image_path' => "albums/{$album->slug}/image-{$i}.webp",
                    'alt_text' => "Hình ảnh {$i} - {$album->title}",
                    'caption' => "Mô tả chi tiết hình ảnh {$i} trong album {$album->title}",
                    'order' => $i,
                    'status' => 'active',
                    'is_featured' => $i === 1, // Ảnh đầu tiên là featured
                    'file_size' => rand(100000, 2000000), // 100KB - 2MB
                    'width' => rand(800, 1920),
                    'height' => rand(600, 1080),
                ]);
                $imageCount++;
            }
        }

        $this->command->info("✅ Đã tạo {$imageCount} hình ảnh cho " . $albums->count() . " album");
    }
}
