<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Widgets\StatsOverviewWidget;
use App\Filament\Admin\Widgets\QuickActionsWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Bảng điều khiển';
    protected static ?string $title = 'Bảng điều khiển VBA Vũ Phúc';
    protected static string $view = 'filament.admin.pages.dashboard';

    public function getWidgets(): array
    {
        return [
            StatsOverviewWidget::class,
            QuickActionsWidget::class,
        ];
    }

    public function getColumns(): int | string | array
    {
        return [
            'sm' => 1,
            'md' => 2,
            'lg' => 3,
            'xl' => 4,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('refresh')
                ->label('Làm mới')
                ->icon('heroicon-o-arrow-path')
                ->action(function () {
                    // Clear cache
                    \Illuminate\Support\Facades\Cache::forget('dashboard_stats');
                    \Illuminate\Support\Facades\Cache::forget('recent_activity_query');

                    $this->dispatch('$refresh');

                    \Filament\Notifications\Notification::make()
                        ->title('Đã làm mới dữ liệu')
                        ->success()
                        ->send();
                }),

            \Filament\Actions\Action::make('view_website')
                ->label('Xem website')
                ->icon('heroicon-o-globe-alt')
                ->color('info')
                ->url(route('storeFront'))
                ->openUrlInNewTab(),
        ];
    }
}
