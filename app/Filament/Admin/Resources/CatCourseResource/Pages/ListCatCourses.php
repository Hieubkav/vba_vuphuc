<?php

namespace App\Filament\Admin\Resources\CatCourseResource\Pages;

use App\Filament\Admin\Resources\CatCourseResource;
use App\Providers\ViewServiceProvider;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCatCourses extends ListRecords
{
    protected static string $resource = CatCourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tạo danh mục mới'),
        ];
    }

    protected function afterReorder(): void
    {
        // Force clear cat courses cache for Filament reorder operations
        ViewServiceProvider::refreshCache('storefront');
        ViewServiceProvider::refreshCache('cat_courses');
    }

    public function reorderTable(array $order): void
    {
        // Clear cache before reordering
        ViewServiceProvider::refreshCache('storefront');
        ViewServiceProvider::refreshCache('cat_courses');

        // Call parent reorder method
        parent::reorderTable($order);

        // Clear cache after reordering
        ViewServiceProvider::refreshCache('storefront');
        ViewServiceProvider::refreshCache('cat_courses');
    }
}
