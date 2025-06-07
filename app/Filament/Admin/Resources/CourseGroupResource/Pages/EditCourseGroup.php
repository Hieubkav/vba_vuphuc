<?php

namespace App\Filament\Admin\Resources\CourseGroupResource\Pages;

use App\Filament\Admin\Resources\CourseGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCourseGroup extends EditRecord
{
    protected static string $resource = CourseGroupResource::class;



    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Xóa'),
        ];
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Nhóm khóa học đã được cập nhật thành công';
    }
}
