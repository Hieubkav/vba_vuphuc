<?php

namespace App\Filament\Admin\Resources\AlbumImageResource\Pages;

use App\Filament\Admin\Resources\AlbumImageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAlbumImage extends EditRecord
{
    protected static string $resource = AlbumImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
