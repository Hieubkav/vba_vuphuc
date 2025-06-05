<?php

namespace App\Filament\Admin\Resources\CatCourseResource\Pages;

use App\Filament\Admin\Resources\CatCourseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCatCourses extends ListRecords
{
    protected static string $resource = CatCourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tạo danh mục mới'),
        ];
    }
}
