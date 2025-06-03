<?php

namespace App\Filament\Admin\Resources\OrderResource\Pages;

use App\Filament\Admin\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()
                ->label('Xem chi tiết'),
            Actions\DeleteAction::make()
                ->label('Xóa'),
        ];
    }
    
    public function getTitle(): string
    {
        return 'Chỉnh sửa Đơn hàng';
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
    protected function getSavedNotificationTitle(): ?string
    {
        return 'Đơn hàng đã được cập nhật thành công';
    }
}
