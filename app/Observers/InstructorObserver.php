<?php

namespace App\Observers;

use App\Models\Instructor;
use App\Traits\HandlesFileObserver;
use Illuminate\Support\Facades\Storage;

class InstructorObserver
{
    use HandlesFileObserver;

    /**
     * Handle the Instructor "updating" event.
     */
    public function updating(Instructor $instructor): void
    {
        // Lưu file avatar cũ để xóa sau khi update
        if ($instructor->isDirty('avatar')) {
            $this->storeOldFile(
                get_class($instructor),
                $instructor->id,
                'avatar',
                $instructor->getOriginal('avatar')
            );
        }
    }

    /**
     * Handle the Instructor "updated" event.
     */
    public function updated(Instructor $instructor): void
    {
        // Xóa file avatar cũ nếu có
        if ($instructor->wasChanged('avatar')) {
            $oldFile = $this->getAndDeleteOldFile(
                get_class($instructor),
                $instructor->id,
                'avatar'
            );

            if ($oldFile && Storage::disk('public')->exists($oldFile)) {
                Storage::disk('public')->delete($oldFile);
            }
        }
    }

    /**
     * Handle the Instructor "deleted" event.
     */
    public function deleted(Instructor $instructor): void
    {
        // Xóa file avatar khi xóa instructor
        if ($instructor->avatar && Storage::disk('public')->exists($instructor->avatar)) {
            Storage::disk('public')->delete($instructor->avatar);
        }
    }

    /**
     * Handle the Instructor "force deleted" event.
     */
    public function forceDeleted(Instructor $instructor): void
    {
        // Xóa file avatar khi force delete
        if ($instructor->avatar && Storage::disk('public')->exists($instructor->avatar)) {
            Storage::disk('public')->delete($instructor->avatar);
        }
    }
}
