<?php

namespace App\Filament\Admin\Resources\TestimonialResource\Pages;

use App\Filament\Admin\Resources\TestimonialResource;
use App\Helpers\AvatarHelper;
use App\Providers\ViewServiceProvider;
use Filament\Resources\Pages\CreateRecord;

class CreateTestimonial extends CreateRecord
{
    protected static string $resource = TestimonialResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Tự động tạo avatar chữ cái nếu không upload ảnh
        if (empty($data['avatar']) && !empty($data['name'])) {
            $data['avatar'] = AvatarHelper::generateAvatarString($data['name']);
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        // Clear cache after creating testimonial
        ViewServiceProvider::refreshCache('storefront');
        ViewServiceProvider::refreshCache('testimonials');
    }
}
