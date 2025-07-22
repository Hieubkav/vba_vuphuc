<?php

namespace App\Listeners;

use App\Providers\ViewServiceProvider;
use Illuminate\Database\Events\TransactionCommitted;
use Illuminate\Support\Facades\Log;

class AlbumCacheRefreshListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TransactionCommitted $event): void
    {
        // Check if any album-related changes were made in this transaction
        if ($this->hasAlbumChanges()) {
            try {
                // Force rebuild albums cache after transaction commit
                ViewServiceProvider::forceRebuildAlbumsCache();

                Log::info('Album cache refreshed after transaction commit');
            } catch (\Exception $e) {
                Log::error('Failed to refresh album cache after transaction: ' . $e->getMessage());
            }
        }
    }

    /**
     * Check if there were album-related changes in the transaction
     */
    private function hasAlbumChanges(): bool
    {
        // Simple check - if we're in an album-related context
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 20);

        foreach ($backtrace as $trace) {
            if (isset($trace['class'])) {
                // Check for Album model or AlbumResource
                if (str_contains($trace['class'], 'Album')) {
                    return true;
                }

                // Check for Filament album operations
                if (str_contains($trace['class'], 'AlbumResource')) {
                    return true;
                }
            }
        }

        return false;
    }
}
