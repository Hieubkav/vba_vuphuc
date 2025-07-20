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
    }

    protected function beforeSave(): void
    {
        // Clear cache before save to ensure fresh data
        ViewServiceProvider::refreshCache('storefront');
        ViewServiceProvider::refreshCache('albums');
    }
}
