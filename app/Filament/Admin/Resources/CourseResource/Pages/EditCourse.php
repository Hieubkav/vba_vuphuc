<?php

namespace App\Filament\Admin\Resources\CourseResource\Pages;

use App\Filament\Admin\Resources\CourseResource;
use App\Providers\ViewServiceProvider;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCourse extends EditRecord
{
    protected static string $resource = CourseResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Load courseGroup relationship để hiển thị group_link
        $this->record->load('courseGroup');

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('view_frontend')
                ->label('Xem trên website')
                ->icon('heroicon-o-eye')
                ->color('info')
                ->url(fn () => route('courses.show', $this->record->slug))
                ->openUrlInNewTab(),
            Actions\DeleteAction::make()
                ->label('Xóa')
                ->after(function () {
                    // Clear cache after deleting course
                    ViewServiceProvider::refreshCache('storefront');
                    ViewServiceProvider::refreshCache('courses');
                }),
        ];
    }

    protected function afterSave(): void
    {
        // Clear cache after saving course
        ViewServiceProvider::refreshCache('storefront');
        ViewServiceProvider::refreshCache('courses');
    }
}
