<?php

namespace App\Filament\Admin\Resources\CourseMaterialResource\Pages;

use App\Filament\Admin\Resources\CourseMaterialResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCourseMaterials extends ListRecords
{
    protected static string $resource = CourseMaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
