<?php

namespace App\Filament\Admin\Resources\PostCategoryResource\Pages;

use App\Filament\Admin\Resources\PostCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePostCategory extends CreateRecord
{
    protected static string $resource = PostCategoryResource::class;

    public function getTitle(): string
    {
        return 'Thêm Danh mục Bài viết Mới';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Danh mục bài viết đã được thêm thành công';
    }
}