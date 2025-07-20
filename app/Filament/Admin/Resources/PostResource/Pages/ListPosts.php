<?php

namespace App\Filament\Admin\Resources\PostResource\Pages;

use App\Filament\Admin\Resources\PostResource;
use App\Providers\ViewServiceProvider;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPosts extends ListRecords
{
    protected static string $resource = PostResource::class;

    public function getTitle(): string
    {
        return 'Danh sách Bài viết';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Thêm bài viết'),
        ];
    }

    protected function afterReorder(): void
    {
        // Force clear posts cache for Filament reorder operations
        ViewServiceProvider::refreshCache('storefront');
        ViewServiceProvider::refreshCache('posts');
    }

    public function reorderTable(array $order): void
    {
        // Clear cache before reordering
        ViewServiceProvider::refreshCache('storefront');
        ViewServiceProvider::refreshCache('posts');

        // Call parent reorder method
        parent::reorderTable($order);

        // Clear cache after reordering
        ViewServiceProvider::refreshCache('storefront');
        ViewServiceProvider::refreshCache('posts');
    }
}