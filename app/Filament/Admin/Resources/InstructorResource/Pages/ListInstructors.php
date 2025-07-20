<?php

namespace App\Filament\Admin\Resources\InstructorResource\Pages;

use App\Filament\Admin\Resources\InstructorResource;
use App\Providers\ViewServiceProvider;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInstructors extends ListRecords
{
    protected static string $resource = InstructorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function afterReorder(): void
    {
        ViewServiceProvider::refreshCache('storefront');
        ViewServiceProvider::refreshCache('instructors');
    }

    public function reorderTable(array $order): void
    {
        ViewServiceProvider::refreshCache('storefront');
        ViewServiceProvider::refreshCache('instructors');
        parent::reorderTable($order);
        ViewServiceProvider::refreshCache('storefront');
        ViewServiceProvider::refreshCache('instructors');
    }
}
