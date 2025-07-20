<?php

namespace App\Filament\Admin\Resources\FaqResource\Pages;

use App\Filament\Admin\Resources\FaqResource;
use App\Providers\ViewServiceProvider;
use Filament\Resources\Pages\CreateRecord;

class CreateFaq extends CreateRecord
{
    protected static string $resource = FaqResource::class;

    protected function afterCreate(): void
    {
        // Clear cache after creating faq
        ViewServiceProvider::refreshCache('storefront');
        ViewServiceProvider::refreshCache('faqs');
    }
}
