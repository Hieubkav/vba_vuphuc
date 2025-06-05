<?php

namespace App\Filament\Admin\Resources\AlbumImageResource\Pages;

use App\Filament\Admin\Resources\AlbumImageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAlbumImages extends ListRecords
{
    protected static string $resource = AlbumImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
