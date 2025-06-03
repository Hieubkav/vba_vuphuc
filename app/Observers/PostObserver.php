<?php

namespace App\Observers;

use App\Models\Post;
use App\Services\ImageService;

class PostObserver
{
    protected $imageService;
    protected $oldThumbnails = []; // Array to store old thumbnails by post ID
    protected $oldOgImages = []; // Array to store old OG images by post ID

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Handle the Post "creating" event.
     */
    public function creating(Post $post): void
    {
        // Tự động copy thumbnail làm OG image nếu OG image trống
        if (empty($post->og_image_link) && !empty($post->thumbnail)) {
            $post->og_image_link = $post->thumbnail;
        }
    }

    /**
     * Handle the Post "created" event.
     */
    public function created(Post $post): void
    {
        // Hình ảnh đã được xử lý trong form Filament
    }

    /**
     * Handle the Post "updating" event.
     */
    public function updating(Post $post): void
    {
        // Tự động copy thumbnail làm OG image nếu OG image trống
        if (empty($post->og_image_link) && !empty($post->thumbnail)) {
            $post->og_image_link = $post->thumbnail;
        }

        // Xử lý xóa file cũ
        if ($post->isDirty('thumbnail') && $post->getOriginal('thumbnail')) {
            // Lưu đường dẫn hình ảnh cũ vào mảng tạm thời để xóa sau
            $this->oldThumbnails[$post->id] = $post->getOriginal('thumbnail');
        }

        if ($post->isDirty('og_image_link') && $post->getOriginal('og_image_link')) {
            // Lưu đường dẫn OG image cũ vào mảng tạm thời để xóa sau
            $this->oldOgImages[$post->id] = $post->getOriginal('og_image_link');
        }
    }

    /**
     * Handle the Post "updated" event.
     */
    public function updated(Post $post): void
    {
        // Nếu có hình ảnh thumbnail cũ cần xóa
        if (isset($this->oldThumbnails[$post->id])) {
            $this->imageService->deleteImage($this->oldThumbnails[$post->id]);
            unset($this->oldThumbnails[$post->id]);
        }

        // Nếu có OG image cũ cần xóa
        if (isset($this->oldOgImages[$post->id])) {
            $this->imageService->deleteImage($this->oldOgImages[$post->id]);
            unset($this->oldOgImages[$post->id]);
        }
    }

    /**
     * Handle the Post "deleted" event.
     */
    public function deleted(Post $post): void
    {
        // Xóa hình ảnh thumbnail khi xóa bài viết
        if ($post->thumbnail) {
            $this->imageService->deleteImage($post->thumbnail);
        }

        // Xóa OG image khi xóa bài viết
        if ($post->og_image_link) {
            $this->imageService->deleteImage($post->og_image_link);
        }

        // Xóa tất cả hình ảnh liên quan trong PostImage
        foreach ($post->images as $postImage) {
            if ($postImage->image_link) {
                $this->imageService->deleteImage($postImage->image_link);
            }
        }
    }

    /**
     * Handle the Post "restored" event.
     */
    public function restored(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "force deleted" event.
     */
    public function forceDeleted(Post $post): void
    {
        //
    }
}
