<?php

namespace App\Services;

use Illuminate\Support\Str;

class SeoImageService
{
    /**
     * Tạo link ảnh SEO-friendly từ tên và loại
     */
    public static function createSeoFriendlyImageName($name, $type = 'avatar', $extension = 'webp')
    {
        // Loại bỏ dấu và tạo slug
        $slug = Str::slug($name, '-');
        
        // Thêm timestamp để tránh trùng lặp
        $timestamp = now()->format('YmdHis');
        
        // Tạo tên file SEO-friendly
        return $type . '-' . $slug . '-' . $timestamp . '.' . $extension;
    }

    /**
     * Tạo đường dẫn thư mục theo loại
     */
    public static function getDirectoryPath($type)
    {
        $directories = [
            'avatar' => 'testimonials/avatars',
            'course' => 'courses/thumbnails',
            'post' => 'posts/thumbnails',
            'slider' => 'sliders',
            'partner' => 'partners/logos',
            'album' => 'albums/images',
        ];

        return $directories[$type] ?? 'uploads';
    }

    /**
     * Tạo alt text SEO-friendly
     */
    public static function createSeoAltText($name, $type = 'ảnh')
    {
        return $type . ' ' . $name . ' - VBA Vũ Phúc';
    }

    /**
     * Tạo title attribute SEO-friendly
     */
    public static function createSeoTitle($name, $type = 'Ảnh')
    {
        return $type . ' của ' . $name . ' tại VBA Vũ Phúc';
    }
}
