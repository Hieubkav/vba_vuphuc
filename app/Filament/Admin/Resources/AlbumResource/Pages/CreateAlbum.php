<?php

namespace App\Filament\Admin\Resources\AlbumResource\Pages;

use App\Filament\Admin\Resources\AlbumResource;
use App\Providers\ViewServiceProvider;
use Filament\Resources\Pages\CreateRecord;

class CreateAlbum extends CreateRecord
{
    protected static string $resource = AlbumResource::class;

    protected function afterCreate(): void
    {
        // Force clear albums cache for Filament operations
        ViewServiceProvider::refreshCache('storefront');
        ViewServiceProvider::refreshCache('albums');
    }
}
