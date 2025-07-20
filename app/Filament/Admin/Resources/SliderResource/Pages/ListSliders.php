<?php

namespace App\Filament\Admin\Resources\SliderResource\Pages;

use App\Filament\Admin\Resources\SliderResource;
use App\Providers\ViewServiceProvider;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSliders extends ListRecords
{
    protected static string $resource = SliderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tạo slider mới'),
        ];
    }

    public function getTitle(): string
    {
        return 'Quản lý Slider';
    }

    protected function afterReorder(): void
    {
        // Force clear sliders cache for Filament reorder operations
        ViewServiceProvider::refreshCache('storefront');
        ViewServiceProvider::refreshCache('sliders');
    }

    public function reorderTable(array $order): void
    {
        // Clear cache before reordering
        ViewServiceProvider::refreshCache('storefront');
        ViewServiceProvider::refreshCache('sliders');

        // Call parent reorder method
        parent::reorderTable($order);

        // Clear cache after reordering
        ViewServiceProvider::refreshCache('storefront');
        ViewServiceProvider::refreshCache('sliders');
    }
}
