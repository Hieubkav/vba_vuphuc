<?php

namespace App\Observers;

use App\Models\Course;
use App\Traits\HandlesFileObserver;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class CourseObserver
{
    use HandlesFileObserver;

    /**
     * Handle the Course "creating" event.
     */
    public function creating(Course $course): void
    {
        // Tự động tạo slug nếu chưa có
        if (empty($course->slug)) {
            $course->slug = $course->generateSlug();
        }
    }

    /**
     * Handle the Course "created" event.
     */
    public function created(Course $course): void
    {
        // Clear cache khi tạo course mới
        $this->clearRelatedCache();
    }

    /**
     * Handle the Course "updating" event.
     */
    public function updating(Course $course): void
    {
        // Lưu file cũ để xóa sau khi update
        if ($course->isDirty('thumbnail') && $course->getOriginal('thumbnail')) {
            $this->storeOldFile(
                Course::class,
                $course->id,
                'thumbnail',
                $course->getOriginal('thumbnail')
            );
        }

        if ($course->isDirty('og_image_link') && $course->getOriginal('og_image_link')) {
            $this->storeOldFile(
                Course::class,
                $course->id,
                'og_image_link',
                $course->getOriginal('og_image_link')
            );
        }

        // Cập nhật slug nếu title thay đổi
        if ($course->isDirty('title') && empty($course->slug)) {
            $course->slug = $course->generateSlug();
        }
    }

    /**
     * Handle the Course "updated" event.
     */
    public function updated(Course $course): void
    {
        // Xóa file cũ nếu có
        $this->deleteOldFileIfExists($course, 'thumbnail');
        $this->deleteOldFileIfExists($course, 'og_image_link');

        // Clear cache khi update
        $this->clearRelatedCache();
    }

    /**
     * Handle the Course "deleted" event.
     */
    public function deleted(Course $course): void
    {
        // Xóa tất cả files liên quan
        $this->deleteFileIfExists($course->thumbnail);
        $this->deleteFileIfExists($course->og_image_link);

        // Clear cache
        $this->clearRelatedCache();
    }

    /**
     * Handle the Course "restored" event.
     */
    public function restored(Course $course): void
    {
        $this->clearRelatedCache();
    }

    /**
     * Handle the Course "force deleted" event.
     */
    public function forceDeleted(Course $course): void
    {
        // Xóa tất cả files liên quan
        $this->deleteFileIfExists($course->thumbnail);
        $this->deleteFileIfExists($course->og_image_link);

        $this->clearRelatedCache();
    }

    /**
     * Xóa file cũ nếu tồn tại
     */
    private function deleteOldFileIfExists(Course $course, string $field): void
    {
        $oldFile = $this->getAndDeleteOldFile(Course::class, $course->id, $field);
        if ($oldFile) {
            $this->deleteFileIfExists($oldFile);
        }
    }

    /**
     * Xóa file nếu tồn tại
     */
    private function deleteFileIfExists(?string $filePath): void
    {
        if (!$filePath) return;

        // Chỉ xóa file trong storage, không xóa URL external
        if (!str_starts_with($filePath, 'http') && Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }
    }

    /**
     * Clear cache liên quan đến courses using ViewServiceProvider
     */
    private function clearRelatedCache(): void
    {
        // Use ViewServiceProvider for consistent cache clearing
        \App\Providers\ViewServiceProvider::refreshCache('storefront');
        \App\Providers\ViewServiceProvider::refreshCache('courses');
    }
}
