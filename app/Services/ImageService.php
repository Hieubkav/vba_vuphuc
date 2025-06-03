<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
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
            // Tạo tên file mới với định dạng webp
            $filename = $this->generateFilename($customName) . '.webp';

            // Đọc hình ảnh từ file tạm
            $img = $manager->read($image->getRealPath());

            // Resize nếu có kích thước chỉ định
            if ($width > 0 && $height > 0) {
                $img->resize($width, $height);
            } elseif ($width > 0) {
                $img->resize(width: $width);
            } elseif ($height > 0) {
                $img->resize(height: $height);
            }

            // Chuyển đổi sang WebP và tối ưu hóa
            $encodedImage = $img->toWebp($quality);

            // Lưu vào storage với đường dẫn public/{directory}/{filename}
            $path = 'public/' . $directory . '/' . $filename;
            Storage::put($path, $encodedImage);

            // Trả về đường dẫn tương đối trong storage/app/public
            return $directory . '/' . $filename;
        }

        // Nếu là đường dẫn file tuyệt đối (để resize ảnh đã tồn tại)
        elseif (is_string($image) && file_exists($image)) {
            // Tạo tên file mới
            $filename = $this->generateFilename($customName) . '.webp';

            // Đọc ảnh từ đường dẫn
            $img = $manager->read($image);

            // Resize nếu có kích thước được chỉ định
            if ($width > 0 && $height > 0) {
                $img->resize($width, $height);
            } elseif ($width > 0) {
                $img->resize(width: $width);
            } elseif ($height > 0) {
                $img->resize(height: $height);
            }

            // Chuyển đổi sang WebP và tối ưu hóa
            $encodedImage = $img->toWebp($quality);

            // Lưu vào storage với đường dẫn public/{directory}/{filename}
            $path = 'public/' . $directory . '/' . $filename;
            Storage::put($path, $encodedImage);

            // Trả về đường dẫn tương đối trong storage/app/public
            return $directory . '/' . $filename;
        }

        // Nếu là đường dẫn chuỗi (đã lưu trước đó trong storage)
        elseif (is_string($image) && Storage::exists('public/' . $image)) {
            return $image; // Giữ nguyên nếu đã tồn tại
        }

        return null;
    }

    /**
     * Xóa hình ảnh từ storage
     *
     * @param string|null $imagePath Đường dẫn hình ảnh cần xóa
     * @return bool
     */
    public function deleteImage(?string $imagePath): bool
    {
        if (!$imagePath) {
            return false;
        }

        $fullPath = 'public/' . $imagePath;
        if (Storage::exists($fullPath)) {
            return Storage::delete($fullPath);
        }

        return false;
    }

    /**
     * Lưu hình ảnh với giữ nguyên tỷ lệ (aspect ratio) - dành cho logo
     *
     * @param UploadedFile|string $image Ảnh tải lên hoặc đường dẫn ảnh
     * @param string $directory Thư mục lưu trong storage
     * @param int $maxWidth Chiều rộng tối đa
     * @param int $maxHeight Chiều cao tối đa
     * @param int $quality Chất lượng ảnh (1-100)
     * @param string|null $customName Tên file tùy chỉnh cho SEO
     * @return string|null Đường dẫn đến ảnh đã lưu
     */
    public function saveImageWithAspectRatio($image, string $directory, int $maxWidth = 400, int $maxHeight = 200, int $quality = 80, ?string $customName = null): ?string
    {
        // Nếu không có ảnh, trả về null
        if (!$image) {
            return null;
        }

        // Khởi tạo ImageManager với driver GD
        $manager = new ImageManager(new Driver());

        // Nếu là UploadedFile (file mới upload)
        if ($image instanceof UploadedFile) {
            // Tạo tên file mới với định dạng webp
            $filename = $this->generateFilename($customName) . '.webp';

            // Đọc hình ảnh từ file tạm
            $img = $manager->read($image->getRealPath());

            // Resize giữ nguyên tỷ lệ - chỉ resize nếu ảnh lớn hơn kích thước tối đa
            $originalWidth = $img->width();
            $originalHeight = $img->height();

            if ($originalWidth > $maxWidth || $originalHeight > $maxHeight) {
                // Tính tỷ lệ scale để vừa trong khung mà không méo
                $scaleWidth = $maxWidth / $originalWidth;
                $scaleHeight = $maxHeight / $originalHeight;
                $scale = min($scaleWidth, $scaleHeight);

                $newWidth = (int)($originalWidth * $scale);
                $newHeight = (int)($originalHeight * $scale);

                $img->resize($newWidth, $newHeight);
            }

            // Chuyển đổi sang WebP và tối ưu hóa
            $encodedImage = $img->toWebp($quality);

            // Lưu vào storage với đường dẫn public/{directory}/{filename}
            $path = 'public/' . $directory . '/' . $filename;
            Storage::put($path, $encodedImage);

            // Trả về đường dẫn tương đối trong storage/app/public
            return $directory . '/' . $filename;
        }

        // Nếu là đường dẫn file tuyệt đối (để resize ảnh đã tồn tại)
        elseif (is_string($image) && file_exists($image)) {
            // Tạo tên file mới
            $filename = $this->generateFilename($customName) . '.webp';

            // Đọc ảnh từ đường dẫn
            $img = $manager->read($image);

            // Resize giữ nguyên tỷ lệ - chỉ resize nếu ảnh lớn hơn kích thước tối đa
            $originalWidth = $img->width();
            $originalHeight = $img->height();

            if ($originalWidth > $maxWidth || $originalHeight > $maxHeight) {
                // Tính tỷ lệ scale để vừa trong khung mà không méo
                $scaleWidth = $maxWidth / $originalWidth;
                $scaleHeight = $maxHeight / $originalHeight;
                $scale = min($scaleWidth, $scaleHeight);

                $newWidth = (int)($originalWidth * $scale);
                $newHeight = (int)($originalHeight * $scale);

                $img->resize($newWidth, $newHeight);
            }

            // Chuyển đổi sang WebP và tối ưu hóa
            $encodedImage = $img->toWebp($quality);

            // Lưu vào storage với đường dẫn public/{directory}/{filename}
            $path = 'public/' . $directory . '/' . $filename;
            Storage::put($path, $encodedImage);

            // Trả về đường dẫn tương đối trong storage/app/public
            return $directory . '/' . $filename;
        }

        // Nếu là đường dẫn chuỗi (đã lưu trước đó trong storage)
        elseif (is_string($image) && Storage::exists('public/' . $image)) {
            return $image; // Giữ nguyên nếu đã tồn tại
        }

        return null;
    }

    /**
     * Tạo tên file SEO-friendly
     *
     * @param string|null $customName Tên tùy chỉnh
     * @return string
     */
    private function generateFilename(?string $customName = null): string
    {
        if ($customName) {
            // Chuyển đổi tên thành slug SEO-friendly
            $slug = Str::slug($customName, '-');
            // Thêm timestamp để tránh trùng lặp
            return $slug . '-' . time();
        }

        // Fallback về random string nếu không có tên tùy chỉnh
        return Str::random(20);
    }
}