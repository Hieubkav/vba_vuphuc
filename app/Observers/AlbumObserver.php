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
                // Tự động xóa thumbnail khi chuyển sang PDF
                if (Storage::disk('public')->exists($album->thumbnail)) {
                    Storage::disk('public')->delete($album->thumbnail);
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
            }
        }

        // Lưu thumbnail cũ để xóa sau khi update
        if ($album->isDirty('thumbnail')) {
            $this->storeOldFile(
                get_class($album),
                $album->id,
                'thumbnail',
                $album->getOriginal('thumbnail')
            );
        }
    }

    /**
     * Handle the Album "updated" event.
     */
    public function updated(Album $album): void
    {
        // Xóa thumbnail cũ nếu có
        if ($album->wasChanged('thumbnail')) {
            $oldFile = $this->getAndDeleteOldFile(
                get_class($album),
                $album->id,
                'thumbnail'
            );

            if ($oldFile && Storage::disk('public')->exists($oldFile)) {
                Storage::disk('public')->delete($oldFile);
            }
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
            // Xóa thumbnail image
            if ($album->thumbnail && Storage::disk('public')->exists($album->thumbnail)) {
                Storage::disk('public')->delete($album->thumbnail);
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
            // Xóa thumbnail image
            if ($album->thumbnail && Storage::disk('public')->exists($album->thumbnail)) {
                Storage::disk('public')->delete($album->thumbnail);
            }
        }

        // Xóa OG image nếu có
        if ($album->og_image_link && Storage::disk('public')->exists($album->og_image_link)) {
            Storage::disk('public')->delete($album->og_image_link);
        }

        // Cache clearing được handle bởi CacheObserver
    }
}
