<?php

namespace App\Filament\Admin\Resources\CatCourseResource\Pages;

use App\Filament\Admin\Resources\CatCourseResource;
use App\Providers\ViewServiceProvider;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCatCourse extends EditRecord
{
    protected static string $resource = CatCourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('view_frontend')
                ->label('Xem trên website')
                ->icon('heroicon-o-eye')
                ->color('info')
                ->url(fn () => route('courses.index', ['category' => $this->record->slug]))
                ->openUrlInNewTab(),
            Actions\Action::make('manage_courses')
                ->label('Quản lý khóa học')
                ->icon('heroicon-o-academic-cap')
                ->color('success')
                ->url(fn () => \App\Filament\Admin\Resources\CourseResource::getUrl('index', ['tableFilters[cat_course_id][value]' => $this->record->id]))
                ->visible(fn () => $this->record->courses_count > 0),
            Actions\DeleteAction::make()
                ->label('Xóa')
                ->after(function () {
                    // Clear cache after deleting cat course
                    ViewServiceProvider::refreshCache('storefront');
                    ViewServiceProvider::refreshCache('cat_courses');
                }),
        ];
    }

    protected function afterSave(): void
    {
        // Clear cache after saving cat course
        ViewServiceProvider::refreshCache('storefront');
        ViewServiceProvider::refreshCache('cat_courses');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
