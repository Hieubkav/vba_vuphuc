<?php

namespace App\Filament\Admin\Resources\CourseResource\Pages;

use App\Filament\Admin\Resources\CourseResource;
use App\Providers\ViewServiceProvider;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCourses extends ListRecords
{
    protected static string $resource = CourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function afterReorder(): void
    {
        // Force clear courses cache for Filament reorder operations
        ViewServiceProvider::refreshCache('storefront');
        ViewServiceProvider::refreshCache('courses');
    }

    public function reorderTable(array $order): void
    {
        // Clear cache before reordering
        ViewServiceProvider::refreshCache('storefront');
        ViewServiceProvider::refreshCache('courses');

        // Call parent reorder method
        parent::reorderTable($order);

        // Clear cache after reordering
        ViewServiceProvider::refreshCache('storefront');
        ViewServiceProvider::refreshCache('courses');
    }
}
