<?php

namespace App\Observers;

use App\Models\Album;
use App\Traits\HandlesFileObserver;
use Illuminate\Support\Facades\Storage;

class AlbumObserver
{
    use HandlesFileObserver;

    /**
     * Handle the Album "created" event.
     */
    public function created(Album $album): void
    {
        // Cache clearing được handle bởi CacheObserver
    }

    /**
     * Handle the Album "updating" event.
     */
    public function updating(Album $album): void
    {
        // Validation logic cho media_type
        if ($album->isDirty('media_type')) {
            if ($album->media_type === 'pdf' && $album->thumbnail) {
                // Tự động xóa thumbnail khi chuyển sang PDF (hỗ trợ multiple files)
                $filesToDelete = is_array($album->thumbnail) ? $album->thumbnail : [$album->thumbnail];

                foreach ($filesToDelete as $file) {
                    if ($file && Storage::disk('public')->exists($file)) {
                        Storage::disk('public')->delete($file);
                    }
                }
                $album->thumbnail = null;
            }
            if ($album->media_type === 'images' && $album->pdf_file) {
                // Tự động xóa PDF file khi chuyển sang images
                if (Storage::disk('public')->exists($album->pdf_file)) {
                    Storage::disk('public')->delete($album->pdf_file);
                }
                $album->pdf_file = null;
                $album->total_pages = null;
                $album->file_size = null;

                // Reset thumbnail array để tránh accumulation khi upload images mới
                $album->thumbnail = null;
            }
        }

        // Lưu thumbnail cũ để xóa sau khi update (hỗ trợ multiple files)
        if ($album->isDirty('thumbnail')) {
            $originalThumbnail = $album->getOriginal('thumbnail');

            // Chuyển array thành JSON string để lưu vào cache
            if (is_array($originalThumbnail)) {
                $originalThumbnail = json_encode($originalThumbnail);
            }

            $this->storeOldFile(
                get_class($album),
                $album->id,
                'thumbnail',
                $originalThumbnail
            );
        }

        // Xử lý khi media_type thay đổi - clear old files ngay lập tức
        if ($album->isDirty('media_type')) {
            $originalMediaType = $album->getOriginal('media_type');

            // Nếu chuyển từ images sang PDF, xóa thumbnail cũ
            if ($originalMediaType === 'images' && $album->media_type === 'pdf') {
                $originalThumbnail = $album->getOriginal('thumbnail');
                if ($originalThumbnail) {
                    $filesToDelete = is_array($originalThumbnail) ? $originalThumbnail : [$originalThumbnail];
                    foreach ($filesToDelete as $file) {
                        if ($file && Storage::disk('public')->exists($file)) {
                            Storage::disk('public')->delete($file);
                        }
                    }
                }
            }

            // Nếu chuyển từ PDF sang images, xóa PDF cũ
            if ($originalMediaType === 'pdf' && $album->media_type === 'images') {
                $originalPdfFile = $album->getOriginal('pdf_file');
                if ($originalPdfFile && Storage::disk('public')->exists($originalPdfFile)) {
                    Storage::disk('public')->delete($originalPdfFile);
                }
            }

            // Store flag để biết có media_type change để xử lý trong updated()
            $this->storeOldFile(
                get_class($album),
                $album->id,
                'media_type_changed',
                $originalMediaType . '->' . $album->media_type
            );
        }
    }

    /**
     * Handle the Album "updated" event.
     */
    public function updated(Album $album): void
    {
        // Xóa thumbnail cũ nếu có (hỗ trợ multiple files)
        if ($album->wasChanged('thumbnail')) {
            $oldFiles = $this->getAndDeleteOldFile(
                get_class($album),
                $album->id,
                'thumbnail'
            );

            if ($oldFiles) {
                // Nếu là JSON string, decode về array
                if (is_string($oldFiles) && str_starts_with($oldFiles, '[')) {
                    $oldFiles = json_decode($oldFiles, true) ?? [$oldFiles];
                }

                // Xử lý cả single file (string) và multiple files (array)
                $filesToDelete = is_array($oldFiles) ? $oldFiles : [$oldFiles];

                foreach ($filesToDelete as $oldFile) {
                    if ($oldFile && Storage::disk('public')->exists($oldFile)) {
                        Storage::disk('public')->delete($oldFile);
                    }
                }
            }
        }

        // Xử lý media_type changes - force cache rebuild
        $mediaTypeChange = $this->getAndDeleteOldFile(
            get_class($album),
            $album->id,
            'media_type_changed'
        );

        if ($mediaTypeChange) {
            // Force immediate cache rebuild for media type transitions
            \App\Providers\ViewServiceProvider::forceRebuildAlbumsCache();
        }

        // Cache clearing được handle bởi CacheObserver
        // Không cần clear cache ở đây nữa
    }

    /**
     * Handle the Album "deleted" event.
     */
    public function deleted(Album $album): void
    {
        // Xóa file theo media_type
        if ($album->media_type === 'pdf') {
            // Xóa file PDF
            if ($album->pdf_file && Storage::disk('public')->exists($album->pdf_file)) {
                Storage::disk('public')->delete($album->pdf_file);
            }
        } elseif ($album->media_type === 'images') {
            // Xóa thumbnail images (hỗ trợ multiple files)
            if ($album->thumbnail) {
                $filesToDelete = is_array($album->thumbnail) ? $album->thumbnail : [$album->thumbnail];

                foreach ($filesToDelete as $file) {
                    if ($file && Storage::disk('public')->exists($file)) {
                        Storage::disk('public')->delete($file);
                    }
                }
            }
        }

        // Xóa OG image nếu có
        if ($album->og_image_link && Storage::disk('public')->exists($album->og_image_link)) {
            Storage::disk('public')->delete($album->og_image_link);
        }

        // Cache clearing được handle bởi CacheObserver
    }

    /**
     * Handle the Album "restored" event.
     */
    public function restored(Album $album): void
    {
        // Cache clearing được handle bởi CacheObserver
    }

    /**
     * Handle the Album "force deleted" event.
     */
    public function forceDeleted(Album $album): void
    {
        // Xóa file theo media_type
        if ($album->media_type === 'pdf') {
            // Xóa file PDF
            if ($album->pdf_file && Storage::disk('public')->exists($album->pdf_file)) {
                Storage::disk('public')->delete($album->pdf_file);
            }
        } elseif ($album->media_type === 'images') {
            // Xóa thumbnail images (hỗ trợ multiple files)
            if ($album->thumbnail) {
                $filesToDelete = is_array($album->thumbnail) ? $album->thumbnail : [$album->thumbnail];

                foreach ($filesToDelete as $file) {
                    if ($file && Storage::disk('public')->exists($file)) {
                        Storage::disk('public')->delete($file);
                    }
                }
            }
        }

        // Xóa OG image nếu có
        if ($album->og_image_link && Storage::disk('public')->exists($album->og_image_link)) {
            Storage::disk('public')->delete($album->og_image_link);
        }

        // Cache clearing được handle bởi CacheObserver
    }
}
