<?php

namespace App\Filament\Admin\Resources\CourseGroupResource\Pages;

use App\Filament\Admin\Resources\CourseGroupResource;
use App\Providers\ViewServiceProvider;
use Filament\Resources\Pages\CreateRecord;

class CreateCourseGroup extends CreateRecord
{
    protected static string $resource = CourseGroupResource::class;

    protected function getRedirectUrl(): string
    {
        // Redirect về index page sau khi tạo
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Nhóm khóa học đã được tạo thành công';
    }

    protected function afterCreate(): void
    {
        // Clear cache after creating course group
        ViewServiceProvider::refreshCache('storefront');
        ViewServiceProvider::refreshCache('course_groups');
    }
}
