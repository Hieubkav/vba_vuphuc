<?php

namespace App\Filament\Admin\Resources\AlbumResource\Pages;

use App\Filament\Admin\Resources\AlbumResource;
use App\Providers\ViewServiceProvider;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAlbum extends EditRecord
{
    protected static string $resource = AlbumResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        // Force clear albums cache for Filament operations
        ViewServiceProvider::refreshCache('storefront');
        ViewServiceProvider::refreshCache('albums');

        // Additional cache clearing for media type transitions
        $record = $this->getRecord();

        // Always force rebuild for any album save to ensure consistency
        ViewServiceProvider::forceRebuildAlbumsCache();

        // Multiple delayed cache rebuilds to handle Filament timing issues
        dispatch(function () {
            ViewServiceProvider::forceRebuildAlbumsCache();
        })->delay(now()->addSeconds(1));

        dispatch(function () {
            ViewServiceProvider::forceRebuildAlbumsCache();
        })->delay(now()->addSeconds(3));

        dispatch(function () {
            ViewServiceProvider::forceRebuildAlbumsCache();
        })->delay(now()->addSeconds(5));

        // Special handling for media type changes
        if ($record && $record->wasChanged('media_type')) {
            // Additional immediate rebuilds for media type transitions
            dispatch(function () {
                ViewServiceProvider::forceRebuildAlbumsCache();
            })->delay(now()->addSeconds(7));

            // Add JavaScript to force cache refresh via API
            $this->js('
                // Force cache refresh via API call
                fetch("/api/force-refresh-albums-cache", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector("meta[name=csrf-token]").getAttribute("content")
                    }
                }).then(response => response.json())
                .then(data => {
                    console.log("Cache refresh result:", data);
                    // Reload page after cache refresh
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                }).catch(error => {
                    console.error("Cache refresh failed:", error);
                });
            ');
        }
    }

    protected function beforeSave(): void
    {
        // Clear cache before save to ensure fresh data
        ViewServiceProvider::refreshCache('storefront');
        ViewServiceProvider::refreshCache('albums');
    }
}
