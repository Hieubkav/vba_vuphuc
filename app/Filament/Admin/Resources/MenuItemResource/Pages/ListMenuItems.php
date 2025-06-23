<?php

namespace App\Filament\Admin\Resources\MenuItemResource\Pages;

use App\Filament\Admin\Resources\MenuItemResource;
use App\Providers\ViewServiceProvider;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;

class ListMenuItems extends ListRecords
{
    protected static string $resource = MenuItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Thêm menu mới'),
            Actions\Action::make('refresh_cache')
                ->label('Làm mới cache')
                ->icon('heroicon-o-arrow-path')
                ->color('info')
                ->action(function () {
                    ViewServiceProvider::refreshCache('navigation');
                    Notification::make()
                        ->title('Cache đã được làm mới')
                        ->success()
                        ->send();
                }),
        ];
    }

    public function getTitle(): string
    {
        return 'Quản lý Menu';
    }

    public function reorderTable(array $order): void
    {
        parent::reorderTable($order);

        // Clear cache
        ViewServiceProvider::refreshCache('navigation');
    }




}
