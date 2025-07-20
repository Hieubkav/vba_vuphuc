<?php

namespace App\Observers;

use App\Models\Testimonial;
use App\Traits\HandlesFileObserver;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class TestimonialObserver
{
    use HandlesFileObserver;

    /**
     * Handle the Testimonial "created" event.
     */
    public function created(Testimonial $testimonial): void
    {
        $this->clearTestimonialsCache();
    }

    /**
     * Handle the Testimonial "updating" event.
     */
    public function updating(Testimonial $testimonial): void
    {
        // Lưu avatar cũ để xóa sau khi update
        if ($testimonial->isDirty('avatar')) {
            $this->storeOldFile(
                get_class($testimonial),
                $testimonial->id,
                'avatar',
                $testimonial->getOriginal('avatar')
            );
        }
    }

    /**
     * Handle the Testimonial "updated" event.
     */
    public function updated(Testimonial $testimonial): void
    {
        // Xóa avatar cũ nếu có
        if ($testimonial->wasChanged('avatar')) {
            $oldFile = $this->getAndDeleteOldFile(
                get_class($testimonial),
                $testimonial->id,
                'avatar'
            );

            if ($oldFile && Storage::disk('public')->exists($oldFile)) {
                Storage::disk('public')->delete($oldFile);
            }
        }

        $this->clearTestimonialsCache();
    }

    /**
     * Handle the Testimonial "deleted" event.
     */
    public function deleted(Testimonial $testimonial): void
    {
        // Xóa avatar khi xóa record
        if ($testimonial->avatar && Storage::disk('public')->exists($testimonial->avatar)) {
            Storage::disk('public')->delete($testimonial->avatar);
        }

        $this->clearTestimonialsCache();
    }

    /**
     * Handle the Testimonial "restored" event.
     */
    public function restored(Testimonial $testimonial): void
    {
        $this->clearTestimonialsCache();
    }

    /**
     * Handle the Testimonial "force deleted" event.
     */
    public function forceDeleted(Testimonial $testimonial): void
    {
        // Xóa avatar khi force delete
        if ($testimonial->avatar && Storage::disk('public')->exists($testimonial->avatar)) {
            Storage::disk('public')->delete($testimonial->avatar);
        }

        $this->clearTestimonialsCache();
    }

    /**
     * Clear testimonials cache using ViewServiceProvider
     */
    protected function clearTestimonialsCache(): void
    {
        // Use ViewServiceProvider for consistent cache clearing
        \App\Providers\ViewServiceProvider::refreshCache('storefront');
        \App\Providers\ViewServiceProvider::refreshCache('testimonials');
    }
}
