<?php

namespace App\Filament\Admin\Resources\PartnerResource\Pages;

use App\Filament\Admin\Resources\PartnerResource;
use App\Providers\ViewServiceProvider;
use Filament\Resources\Pages\CreateRecord;

class CreatePartner extends CreateRecord
{
    protected static string $resource = PartnerResource::class;

    protected function afterCreate(): void
    {
        // Clear cache after creating partner
        ViewServiceProvider::refreshCache('storefront');
        ViewServiceProvider::refreshCache('partners');
    }
}
