<?php

namespace App\Filament\Admin\Resources\CourseResource\Pages;

use App\Filament\Admin\Resources\CourseResource;
use App\Providers\ViewServiceProvider;
use Filament\Resources\Pages\CreateRecord;

class CreateCourse extends CreateRecord
{
    protected static string $resource = CourseResource::class;

    protected function afterCreate(): void
    {
        // Clear cache after creating course
        ViewServiceProvider::refreshCache('storefront');
        ViewServiceProvider::refreshCache('courses');
    }
}
