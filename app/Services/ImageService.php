<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ImageService
{
    /**
     * Lưu hình ảnh tải lên với định dạng WebP và tối ưu hóa
     *
     * @param UploadedFile|string $image Ảnh tải lên hoặc đường dẫn ảnh
     * @param string $directory Thư mục lưu trong storage
     * @param int $width Chiều rộng (0 để giữ nguyên)
     * @param int $height Chiều cao (0 để giữ nguyên)
     * @param int $quality Chất lượng ảnh (1-100)
     * @param string|null $customName Tên file tùy chỉnh cho SEO
     * @return string|null Đường dẫn đến ảnh đã lưu
     */
    public function saveImage($image, string $directory, int $width = 0, int $height = 0, int $quality = 80, ?string $customName = null): ?string
    {
        // Nếu không có ảnh, trả về null
        if (!$image) {
            return null;
        }

        // Khởi tạo ImageManager với driver GD
        $manager = new ImageManager(new Driver());

        // Nếu là UploadedFile (file mới upload)
        if ($image instanceof UploadedFile) {
            try {
                // Kiểm tra file có hợp lệ không
                if (!$image->isValid()) {
                    Log::error('ImageService: Invalid file upload');
                    return null;
                }

                // Kiểm tra file path có tồn tại không
                $realPath = $image->getRealPath();
                if (!$realPath || !file_exists($realPath)) {
                    Log::error('ImageService: File does not exist at path: ' . ($realPath ?? 'null'));
                    return null;
                }

                // Tạo tên file
                $filename = $this->generateFilename($customName) . '.webp';

                // Đọc và xử lý ảnh
                $img = $manager->read($realPath);

                // Resize nếu cần
                if ($width > 0 || $height > 0) {
                    $img->resize($width, $height, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                }

                // Chuyển đổi sang WebP và tối ưu hóa
                $encodedImage = $img->toWebp($quality);

                // Lưu vào storage disk 'public'
                $filePath = $directory . '/' . $filename;
                Storage::disk('public')->put($filePath, $encodedImage);

                // Trả về đường dẫn tương đối
                return $filePath;
            } catch (\Exception $e) {
                Log::error('ImageService error: ' . $e->getMessage(), [
                    'file_name' => $image->getClientOriginalName() ?? 'unknown',
                    'file_size' => $image->getSize() ?? 'unknown',
                    'directory' => $directory
                ]);

                // Fallback: lưu file gốc
                try {
                    return $image->store($directory, 'public');
                } catch (\Exception $fallbackError) {
                    Log::error('ImageService fallback error: ' . $fallbackError->getMessage());
                    return null;
                }
            }
        }

        // Nếu là string (đường dẫn ảnh có sẵn)
        if (is_string($image)) {
            try {
                // Kiểm tra file có tồn tại không
                $fullPath = storage_path('app/public/' . $image);
                if (!file_exists($fullPath)) {
                    Log::error('ImageService: File does not exist at path: ' . $fullPath);
                    return null;
                }

                // Tạo tên file mới
                $filename = $this->generateFilename($customName) . '.webp';

                // Đọc và xử lý ảnh
                $img = $manager->read($fullPath);

                // Resize nếu cần
                if ($width > 0 || $height > 0) {
                    $img->resize($width, $height, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                }

                // Chuyển đổi sang WebP
                $encodedImage = $img->toWebp($quality);

                // Lưu vào storage
                $filePath = $directory . '/' . $filename;
                Storage::disk('public')->put($filePath, $encodedImage);

                return $filePath;
            } catch (\Exception $e) {
                Log::error('ImageService error processing existing image: ' . $e->getMessage(), [
                    'image_path' => $image,
                    'directory' => $directory
                ]);
                return null;
            }
        }

        return null;
    }

    /**
     * Tạo tên file duy nhất
     */
    private function generateFilename(?string $customName = null): string
    {
        if ($customName) {
            return Str::slug($customName) . '-' . time();
        }
        
        return 'image-' . time() . '-' . Str::random(8);
    }

    /**
     * Resize ảnh thành tỷ lệ 16:9
     */
    public function resizeToSixteenNine(string $imagePath, ?string $customName = null): ?string
    {
        try {
            $fullPath = storage_path('app/public/' . $imagePath);

            if (!file_exists($fullPath)) {
                Log::error('ImageService: File not found for 16:9 resize: ' . $fullPath);
                return null;
            }

            $manager = new ImageManager(new Driver());
            $img = $manager->read($fullPath);

            // Tính toán kích thước 16:9
            $originalWidth = $img->width();
            $originalHeight = $img->height();

            // Tỷ lệ 16:9
            $targetRatio = 16/9;
            $currentRatio = $originalWidth / $originalHeight;

            if ($currentRatio > $targetRatio) {
                // Ảnh quá rộng, cắt theo chiều rộng
                $newWidth = $originalHeight * $targetRatio;
                $newHeight = $originalHeight;
                $x = ($originalWidth - $newWidth) / 2;
                $y = 0;
            } else {
                // Ảnh quá cao, cắt theo chiều cao
                $newWidth = $originalWidth;
                $newHeight = $originalWidth / $targetRatio;
                $x = 0;
                $y = ($originalHeight - $newHeight) / 2;
            }

            // Crop ảnh
            $img->crop($newWidth, $newHeight, $x, $y);

            // Tạo tên file mới
            $pathInfo = pathinfo($imagePath);
            $directory = $pathInfo['dirname'];
            $filename = $this->generateFilename($customName) . '.webp';

            // Chuyển đổi sang WebP
            $encodedImage = $img->toWebp(90);

            // Lưu vào storage
            $filePath = $directory . '/' . $filename;
            Storage::disk('public')->put($filePath, $encodedImage);

            return $filePath;
        } catch (\Exception $e) {
            Log::error('ImageService 16:9 resize error: ' . $e->getMessage(), [
                'image_path' => $imagePath
            ]);
            return null;
        }
    }

    /**
     * Xóa file ảnh từ storage
     *
     * @param string $imagePath Đường dẫn ảnh cần xóa
     * @return bool True nếu xóa thành công hoặc file không tồn tại
     */
    public function deleteImage(string $imagePath): bool
    {
        try {
            // Kiểm tra nếu đường dẫn rỗng
            if (empty($imagePath)) {
                return true;
            }

            // Xóa từ storage disk 'public'
            if (Storage::disk('public')->exists($imagePath)) {
                $deleted = Storage::disk('public')->delete($imagePath);

                if ($deleted) {
                    Log::info('ImageService: Successfully deleted image', ['path' => $imagePath]);
                } else {
                    Log::warning('ImageService: Failed to delete image', ['path' => $imagePath]);
                }

                return $deleted;
            }

            // File không tồn tại, coi như đã xóa thành công
            Log::info('ImageService: Image does not exist, skipping deletion', ['path' => $imagePath]);
            return true;

        } catch (\Exception $e) {
            Log::error('ImageService delete error: ' . $e->getMessage(), [
                'image_path' => $imagePath
            ]);
            return false;
        }
    }

    /**
     * Lưu ảnh với tỷ lệ khung hình được giữ nguyên (không crop)
     * Tối ưu cho hero banner - giữ nguyên aspect ratio gốc
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param int $maxWidth
     * @param int $maxHeight
     * @param int $quality
     * @param string|null $customName
     * @return string|null
     */
    public function saveImageWithAspectRatio(
        UploadedFile $file,
        string $directory,
        int $maxWidth = 1920,
        int $maxHeight = 1080,
        int $quality = 85,
        ?string $customName = null
    ): ?string {
        try {
            // Khởi tạo ImageManager
            $manager = new ImageManager(new Driver());

            // Đọc ảnh gốc
            $image = $manager->read($file->getRealPath());

            // Lấy kích thước gốc
            $originalWidth = $image->width();
            $originalHeight = $image->height();

            // Tính toán kích thước mới giữ nguyên tỷ lệ
            $ratio = min($maxWidth / $originalWidth, $maxHeight / $originalHeight);

            // Chỉ resize nếu ảnh lớn hơn kích thước tối đa
            if ($ratio < 1) {
                $newWidth = (int) round($originalWidth * $ratio);
                $newHeight = (int) round($originalHeight * $ratio);
                $image = $image->resize($newWidth, $newHeight);
            }

            // Tạo tên file
            $fileName = $customName ? Str::slug($customName) : Str::random(40);
            $fileName .= '.webp';

            // Đảm bảo thư mục tồn tại
            $fullDirectory = 'public/' . trim($directory, '/');
            if (!Storage::exists($fullDirectory)) {
                Storage::makeDirectory($fullDirectory);
            }

            // Đường dẫn đầy đủ
            $filePath = $fullDirectory . '/' . $fileName;

            // Lưu ảnh với định dạng WebP
            $webpData = $image->toWebp($quality);
            Storage::put($filePath, $webpData);

            // Trả về đường dẫn relative (không có 'public/')
            return trim($directory, '/') . '/' . $fileName;

        } catch (\Exception $e) {
            Log::error('ImageService saveImageWithAspectRatio error: ' . $e->getMessage(), [
                'file' => $file->getClientOriginalName(),
                'directory' => $directory,
                'maxWidth' => $maxWidth,
                'maxHeight' => $maxHeight
            ]);
            return null;
        }
    }
}
