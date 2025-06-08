<?php

namespace App\Traits;

use App\Services\SimpleWebpService;
use Filament\Forms\Components\FileUpload;

trait HasImageUpload
{
    /**
     * Tạo FileUpload component với WebP conversion tự động
     * 
     * @param string $field Tên trường
     * @param string $label Label hiển thị
     * @param string $directory Thư mục lưu trữ
     * @param int $maxWidth Chiều rộng tối đa (0 = giữ nguyên)
     * @param int $maxHeight Chiều cao tối đa (0 = giữ nguyên)
     * @param int $maxSize Kích thước file tối đa (KB)
     * @param string|null $helperText Text hướng dẫn
     * @param array $aspectRatios Tỷ lệ khung hình cho editor
     * @param bool $required Bắt buộc hay không
     * @param bool $keepAspectRatio Giữ nguyên tỷ lệ khung hình
     * @return FileUpload
     */
    protected static function createImageUpload(
        string $field,
        string $label,
        string $directory,
        int $maxWidth = 0,
        int $maxHeight = 0,
        int $maxSize = 5120,
        ?string $helperText = null,
        array $aspectRatios = ['16:9', '4:3', '1:1'],
        bool $required = false,
        bool $keepAspectRatio = false
    ): FileUpload {
        $upload = FileUpload::make($field)
            ->label($label)
            ->image()
            ->directory($directory)
            ->visibility('public')
            ->maxSize($maxSize)
            ->imageEditor()
            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp']);

        // Thêm helper text nếu có
        if ($helperText) {
            $upload->helperText($helperText);
        } else {
            $upload->helperText('Chọn ảnh định dạng JPG, PNG hoặc WebP. Kích thước tối đa: ' . ($maxSize/1024) . 'MB');
        }

        // Thêm aspect ratios cho image editor
        if (!empty($aspectRatios)) {
            $upload->imageEditorAspectRatios($aspectRatios);
        }

        // Thiết lập resize nếu có kích thước
        if ($maxWidth > 0 && $maxHeight > 0) {
            $upload->imageResizeMode('cover')
                   ->imageResizeTargetWidth($maxWidth)
                   ->imageResizeTargetHeight($maxHeight);
        }

        // Thiết lập required
        if ($required) {
            $upload->required();
        }

        return $upload;
    }

    /**
     * Tạo FileUpload cho thumbnail/logo với aspect ratio
     */
    protected static function createThumbnailUpload(
        string $field = 'thumbnail',
        string $label = 'Hình đại diện',
        string $directory = 'thumbnails',
        int $maxWidth = 400,
        int $maxHeight = 300
    ): FileUpload {
        return self::createImageUpload(
            $field,
            $label,
            $directory,
            $maxWidth,
            $maxHeight,
            5120, // 5MB
            '💡 Kích thước khuyến nghị: ' . $maxWidth . 'x' . $maxHeight . 'px để hiển thị tối ưu',
            ['16:9', '4:3', '1:1'],
            false,
            true // Giữ nguyên tỷ lệ
        );
    }

    /**
     * Tạo FileUpload cho banner/hero image
     */
    protected static function createBannerUpload(
        string $field = 'image_link',
        string $label = 'Hình ảnh Banner',
        string $directory = 'banners',
        int $width = 1920,
        int $height = 1080
    ): FileUpload {
        return self::createImageUpload(
            $field,
            $label,
            $directory,
            $width,
            $height,
            8192, // 8MB
            'Kích thước tối ưu: ' . $width . 'x' . $height . 'px (16:9) cho desktop, 800x450px cho mobile.',
            ['16:9'],
            true, // Required
            false // Không giữ tỷ lệ, crop theo kích thước
        );
    }



    /**
     * Tạo FileUpload cho logo với kích thước vuông
     */
    protected static function createLogoUpload(
        string $field = 'logo_link',
        string $label = 'Logo',
        string $directory = 'logos',
        int $size = 400
    ): FileUpload {
        return self::createImageUpload(
            $field,
            $label,
            $directory,
            $size,
            $size,
            2048, // 2MB
            'Khuyến nghị kích thước: ' . $size . 'x' . $size . 'px (hình vuông)',
            ['1:1'],
            false,
            true // Giữ nguyên tỷ lệ
        );
    }

    /**
     * Tạo FileUpload cho gallery images trong RelationManager
     */
    protected function createGalleryUpload(
        string $field = 'image_link',
        string $label = 'Hình ảnh',
        string $directory = 'gallery',
        int $width = 800,
        int $height = 600
    ): FileUpload {
        return FileUpload::make($field)
            ->label($label)
            ->image()
            ->directory($directory)
            ->visibility('public')
            ->maxSize(5120)
            ->imageEditor()
            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
            ->saveUploadedFileUsing(function ($file, $get, $livewire) use ($directory, $width, $height) {
                // Lấy tên từ owner record
                $customName = 'gallery';
                if (isset($livewire->ownerRecord)) {
                    if ($livewire->ownerRecord->title) {
                        $customName = 'gallery-' . $livewire->ownerRecord->title;
                    } elseif ($livewire->ownerRecord->name) {
                        $customName = 'gallery-' . $livewire->ownerRecord->name;
                    }
                }

                return \App\Actions\ConvertImageToWebp::run(
                    $file,
                    $directory,
                    $customName,
                    $width,
                    $height
                );
            })
            ->helperText('Ảnh sẽ được tự động chuyển sang định dạng WebP với chất lượng 95%');
    }
}
