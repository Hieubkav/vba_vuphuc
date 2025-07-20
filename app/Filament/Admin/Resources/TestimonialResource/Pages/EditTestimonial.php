<?php

namespace App\Filament\Admin\Resources\TestimonialResource\Pages;

use App\Filament\Admin\Resources\TestimonialResource;
use App\Providers\ViewServiceProvider;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTestimonial extends EditRecord
{
    protected static string $resource = TestimonialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->after(function () {
                    // Clear cache after deleting testimonial
                    ViewServiceProvider::refreshCache('storefront');
                    ViewServiceProvider::refreshCache('testimonials');
                }),
        ];
    }

    protected function afterSave(): void
    {
        // Clear cache after saving testimonial
        ViewServiceProvider::refreshCache('storefront');
        ViewServiceProvider::refreshCache('testimonials');
    }
}
