<?php

namespace App\Filament\Admin\Resources\CatCourseResource\Pages;

use App\Filament\Admin\Resources\CatCourseResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCatCourse extends CreateRecord
{
    protected static string $resource = CatCourseResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
