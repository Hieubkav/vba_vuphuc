<?php

namespace App\Filament\Admin\Resources\InstructorResource\Pages;

use App\Filament\Admin\Resources\InstructorResource;
use App\Providers\ViewServiceProvider;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInstructor extends EditRecord
{
    protected static string $resource = InstructorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->after(function () {
                    ViewServiceProvider::refreshCache('storefront');
                    ViewServiceProvider::refreshCache('instructors');
                }),
        ];
    }

    protected function afterSave(): void
    {
        ViewServiceProvider::refreshCache('storefront');
        ViewServiceProvider::refreshCache('instructors');
    }
}
