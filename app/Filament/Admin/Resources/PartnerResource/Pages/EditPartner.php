<?php

namespace App\Filament\Admin\Resources\PartnerResource\Pages;

use App\Filament\Admin\Resources\PartnerResource;
use App\Providers\ViewServiceProvider;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPartner extends EditRecord
{
    protected static string $resource = PartnerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->after(function () {
                    // Clear cache after deleting partner
                    ViewServiceProvider::refreshCache('storefront');
                    ViewServiceProvider::refreshCache('partners');
                }),
        ];
    }

    protected function afterSave(): void
    {
        // Clear cache after saving partner
        ViewServiceProvider::refreshCache('storefront');
        ViewServiceProvider::refreshCache('partners');
    }
}
