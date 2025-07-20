<?php

namespace App\Filament\Admin\Resources\PartnerResource\Pages;

use App\Filament\Admin\Resources\PartnerResource;
use App\Providers\ViewServiceProvider;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPartners extends ListRecords
{
    protected static string $resource = PartnerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function afterReorder(): void
    {
        // Force clear partners cache for Filament reorder operations
        ViewServiceProvider::refreshCache('storefront');
        ViewServiceProvider::refreshCache('partners');
    }

    public function reorderTable(array $order): void
    {
        // Clear cache before reordering
        ViewServiceProvider::refreshCache('storefront');
        ViewServiceProvider::refreshCache('partners');

        // Call parent reorder method
        parent::reorderTable($order);

        // Clear cache after reordering
        ViewServiceProvider::refreshCache('storefront');
        ViewServiceProvider::refreshCache('partners');
    }
}
