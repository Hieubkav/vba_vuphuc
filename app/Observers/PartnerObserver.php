<?php

namespace App\Observers;

use App\Models\Partner;
use App\Services\SimpleWebpService;
use App\Traits\HandlesFileObserver;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class PartnerObserver
{
    use HandlesFileObserver;

    protected $webpService;

    public function __construct(SimpleWebpService $webpService)
    {
        $this->webpService = $webpService;
    }

    /**
     * Handle the Partner "created" event.
     */
    public function created(Partner $partner): void
    {
        // Hình ảnh đã được xử lý trong form Filament
        $this->clearRelatedCache();
    }

    /**
     * Handle the Partner "updating" event.
     */
    public function updating(Partner $partner): void
    {
        $modelClass = get_class($partner);
        $modelId = $partner->id;

        // Lưu logo_link cũ
        if ($partner->isDirty('logo_link')) {
            $this->storeOldFile($modelClass, $modelId, 'logo_link', $partner->getOriginal('logo_link'));
        }
    }

    /**
     * Handle the Partner "updated" event.
     */
    public function updated(Partner $partner): void
    {
        // Xóa file cũ nếu có
        $oldFile = $this->getAndDeleteOldFile(get_class($partner), $partner->id, 'logo_link');
        if ($oldFile) {
            $this->deleteFileIfExists($oldFile);
        }

        $this->clearRelatedCache();
    }

    /**
     * Handle the Partner "deleted" event.
     */
    public function deleted(Partner $partner): void
    {
        // Xóa file logo
        $this->deleteFileIfExists($partner->logo_link);

        $this->clearRelatedCache();
    }

    /**
     * Handle the Partner "restored" event.
     */
    public function restored(Partner $partner): void
    {
        $this->clearRelatedCache();
    }

    /**
     * Handle the Partner "force deleted" event.
     */
    public function forceDeleted(Partner $partner): void
    {
        // Xóa file logo
        $this->deleteFileIfExists($partner->logo_link);

        $this->clearRelatedCache();
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
     * Clear cache liên quan đến partners
     */
    private function clearRelatedCache(): void
    {
        // Sử dụng ViewServiceProvider để clear cache đồng bộ
        \App\Providers\ViewServiceProvider::refreshCache('storefront');

        // Clear cache navigation (partners có thể hiển thị ở footer)
        \App\Providers\ViewServiceProvider::refreshCache('navigation');

        // Clear cache theo pattern cũ (backward compatibility)
        $cacheKeys = [
            'partners_active',
            'partners_ordered',
            'storefront_data'
        ];

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }
    }
}
