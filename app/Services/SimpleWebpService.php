<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;

/**
 * Simple WebP Service - KISS Principle
 * 
 * Chỉ làm 1 việc: Chuyển ảnh sang WebP với 95% chất lượng
 * Không có cache, không có optimization phức tạp
 */
class SimpleWebpService
{
    private ImageManager $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Chuyển ảnh sang WebP với 95% chất lượng
     *
     * @param UploadedFile $file File upload
     * @param string $directory Thư mục lưu (vd: 'courses', 'posts')
     * @param string|null $customName Tên file tùy chỉnh
     * @param int $width Chiều rộng (0 = không resize)
     * @param int $height Chiều cao (0 = không resize)
     * @return string|null Đường dẫn file đã lưu
     */
    public function convertToWebP(UploadedFile $file, string $directory, ?string $customName = null, int $width = 0, int $height = 0): ?string
    {
        try {
            // Tạo tên file
            $fileName = $this->generateFileName($customName) . '.webp';

            // Đường dẫn lưu
            $filePath = $directory . '/' . $fileName;

            // Đọc và convert sang WebP
            $image = $this->manager->read($file->getPathname());

            // Resize nếu có kích thước - giữ nguyên tỷ lệ
            if ($width > 0 && $height > 0) {
                // Resize với constraint để giữ tỷ lệ và fit trong khung
                $image->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio(); // Giữ nguyên tỷ lệ
                    $constraint->upsize(); // Không phóng to ảnh nhỏ
                });
            } elseif ($width > 0) {
                $image->resize($width, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            } elseif ($height > 0) {
                $image->resize(null, $height, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }

            $webpData = $image->toWebp(95); // 95% chất lượng

            // Lưu file
            Storage::disk('public')->put($filePath, $webpData);

            return $filePath;

        } catch (\Exception $e) {
            // Fallback: lưu file gốc nếu convert lỗi
            return $file->store($directory, 'public');
        }
    }

    /**
     * Tạo tên file đơn giản
     */
    private function generateFileName(?string $customName = null): string
    {
        if ($customName) {
            return \Illuminate\Support\Str::slug($customName) . '-' . time();
        }
        
        return 'image-' . time() . '-' . rand(1000, 9999);
    }
}
