<?php

namespace App\Actions;

use Illuminate\Http\UploadedFile;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Lorisleiva\Actions\Concerns\AsAction;

/**
 * Convert Image To WebP Action - KISS Principle
 *
 * Chỉ làm 1 việc: Chuyển ảnh sang WebP với 95% chất lượng
 * Giữ nguyên tỷ lệ khung hình khi resize
 * Không có cache, không có optimization phức tạp
 */
class ConvertImageToWebp
{
    use AsAction;

    private ImageManager $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Chuyển ảnh sang WebP với 95% chất lượng và giữ nguyên tỷ lệ
     *
     * @param UploadedFile $file File upload
     * @param string $directory Thư mục lưu (vd: 'courses', 'posts')
     * @param string|null $customName Tên file tùy chỉnh
     * @param int $width Chiều rộng tối đa (0 = không resize)
     * @param int $height Chiều cao tối đa (0 = không resize)
     * @return string|null Đường dẫn file đã lưu
     */
    public function handle(UploadedFile $file, string $directory, ?string $customName = null, int $width = 0, int $height = 0): ?string
    {
        try {
            // Kiểm tra file có tồn tại không
            if (!$file->isValid()) {
                Log::error('ConvertImageToWebp: Invalid file upload');
                return null;
            }

            // Kiểm tra file path có tồn tại không
            $filePath = $file->getPathname();
            if (!file_exists($filePath)) {
                Log::error('ConvertImageToWebp: File does not exist at path: ' . $filePath);
                return null;
            }

            // Tạo tên file
            $fileName = $this->generateFileName($customName) . '.webp';

            // Đường dẫn lưu
            $savePath = $directory . '/' . $fileName;

            // Đọc và convert sang WebP
            $image = $this->manager->read($filePath);

            // Resize nếu có kích thước - giữ nguyên tỷ lệ (Intervention Image v3)
            if ($width > 0 && $height > 0) {
                // Scale để fit trong khung mà không cắt ảnh
                $image->scale($width, $height);
            } elseif ($width > 0) {
                // Scale theo chiều rộng, giữ tỷ lệ
                $image->scaleDown($width);
            } elseif ($height > 0) {
                // Scale theo chiều cao, giữ tỷ lệ
                $image->scaleDown(height: $height);
            }

            $webpData = $image->toWebp(95); // 95% chất lượng

            // Lưu file
            Storage::disk('public')->put($savePath, $webpData);

            return $savePath;

        } catch (\Exception $e) {
            Log::error('ConvertImageToWebp error: ' . $e->getMessage(), [
                'file_name' => $file->getClientOriginalName() ?? 'unknown',
                'file_size' => $file->getSize() ?? 'unknown',
                'directory' => $directory
            ]);

            // Fallback: lưu file gốc nếu convert lỗi
            try {
                return $file->store($directory, 'public');
            } catch (\Exception $fallbackError) {
                Log::error('ConvertImageToWebp fallback error: ' . $fallbackError->getMessage());
                return null;
            }
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

    /**
     * Static method để gọi action dễ dàng hơn
     */
    public static function run(UploadedFile $file, string $directory, ?string $customName = null, int $width = 0, int $height = 0): ?string
    {
        return static::make()->handle($file, $directory, $customName, $width, $height);
    }
}
