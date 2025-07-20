<?php

namespace App\Filament\Admin\Resources\CourseGroupResource\Pages;

use App\Filament\Admin\Resources\CourseGroupResource;
use App\Providers\ViewServiceProvider;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCourseGroup extends EditRecord
{
    protected static string $resource = CourseGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Xóa')
                ->after(function () {
                    // Clear cache after deleting course group
                    ViewServiceProvider::refreshCache('storefront');
                    ViewServiceProvider::refreshCache('course_groups');
                }),
        ];
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Nhóm khóa học đã được cập nhật thành công';
    }

    protected function afterSave(): void
    {
        // Clear cache after saving course group
        ViewServiceProvider::refreshCache('storefront');
        ViewServiceProvider::refreshCache('course_groups');
    }
}
