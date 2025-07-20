<?php

namespace App\Filament\Admin\Resources\TestimonialResource\Pages;

use App\Filament\Admin\Resources\TestimonialResource;
use App\Providers\ViewServiceProvider;
use Filament\Resources\Pages\CreateRecord;

class CreateTestimonial extends CreateRecord
{
    protected static string $resource = TestimonialResource::class;

    protected function afterCreate(): void
    {
        // Clear cache after creating testimonial
        ViewServiceProvider::refreshCache('storefront');
        ViewServiceProvider::refreshCache('testimonials');
    }
}
