<?php

namespace App\Filament\Admin\Resources\PostCategoryResource\Pages;

use App\Filament\Admin\Resources\PostCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPostCategory extends EditRecord
{
    protected static string $resource = PostCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Xóa'),
        ];
    }

    public function getTitle(): string
    {
        return 'Chỉnh sửa Danh mục Bài viết';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Danh mục bài viết đã được cập nhật thành công';
    }
}