<?php

namespace App\Filament\Admin\Resources\SliderResource\Pages;

use App\Filament\Admin\Resources\SliderResource;
use App\Providers\ViewServiceProvider;
use Filament\Resources\Pages\CreateRecord;

class CreateSlider extends CreateRecord
{
    protected static string $resource = SliderResource::class;

    public function getTitle(): string
    {
        return 'Tạo Slider Mới';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Slider đã được tạo thành công';
    }

    protected function afterCreate(): void
    {
        // Clear cache after creating slider
        ViewServiceProvider::refreshCache('storefront');
        ViewServiceProvider::refreshCache('sliders');
    }
}
