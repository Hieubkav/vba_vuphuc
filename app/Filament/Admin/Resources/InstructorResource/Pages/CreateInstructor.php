<?php

namespace App\Filament\Admin\Resources\InstructorResource\Pages;

use App\Filament\Admin\Resources\InstructorResource;
use App\Providers\ViewServiceProvider;
use Filament\Resources\Pages\CreateRecord;

class CreateInstructor extends CreateRecord
{
    protected static string $resource = InstructorResource::class;

    protected function afterCreate(): void
    {
        ViewServiceProvider::refreshCache('storefront');
        ViewServiceProvider::refreshCache('instructors');
    }
}
