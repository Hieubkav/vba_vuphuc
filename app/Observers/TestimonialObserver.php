<?php

namespace App\Observers;

use App\Models\Testimonial;
use App\Traits\HandlesFileObserver;
use Illuminate\Support\Facades\Storage;

class TestimonialObserver
{
    use HandlesFileObserver;

    /**
     * Handle the Testimonial "updating" event.
     */
    public function updating(Testimonial $testimonial): void
    {
        // Lưu file avatar cũ để xóa sau khi update
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
        // Xóa file avatar cũ nếu có
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
    }

    /**
     * Handle the Testimonial "deleted" event.
     */
    public function deleted(Testimonial $testimonial): void
    {
        // Xóa file avatar khi xóa testimonial
        if ($testimonial->avatar && Storage::disk('public')->exists($testimonial->avatar)) {
            Storage::disk('public')->delete($testimonial->avatar);
        }
    }
}
