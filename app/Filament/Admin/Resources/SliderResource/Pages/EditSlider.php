<?php

namespace App\Filament\Admin\Resources\SliderResource\Pages;

use App\Filament\Admin\Resources\SliderResource;
use App\Providers\ViewServiceProvider;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSlider extends EditRecord
{
    protected static string $resource = SliderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Xóa')
                ->after(function () {
                    // Clear cache after deleting slider
                    ViewServiceProvider::refreshCache('storefront');
                    ViewServiceProvider::refreshCache('sliders');
                }),
        ];
    }

    public function getTitle(): string
    {
        return 'Chỉnh sửa Slider';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Slider đã được cập nhật thành công';
    }

    protected function afterSave(): void
    {
        // Clear cache after saving slider
        ViewServiceProvider::refreshCache('storefront');
        ViewServiceProvider::refreshCache('sliders');
    }
}
