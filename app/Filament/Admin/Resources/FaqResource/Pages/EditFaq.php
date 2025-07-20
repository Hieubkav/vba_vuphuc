<?php

namespace App\Filament\Admin\Resources\FaqResource\Pages;

use App\Filament\Admin\Resources\FaqResource;
use App\Providers\ViewServiceProvider;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFaq extends EditRecord
{
    protected static string $resource = FaqResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->after(function () {
                    // Clear cache after deleting faq
                    ViewServiceProvider::refreshCache('storefront');
                    ViewServiceProvider::refreshCache('faqs');
                }),
        ];
    }

    protected function afterSave(): void
    {
        // Clear cache after saving faq
        ViewServiceProvider::refreshCache('storefront');
        ViewServiceProvider::refreshCache('faqs');
    }
}
