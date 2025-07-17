<?php

namespace App\Filament\Admin\Resources\CourseGroupResource\Pages;

use App\Filament\Admin\Resources\CourseGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCourseGroups extends ListRecords
{
    protected static string $resource = CourseGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('view_frontend')
                ->label('Xem trên website')
                ->icon('heroicon-o-globe-alt')
                ->color('info')
                ->url(fn () => route('course-groups.index'))
                ->openUrlInNewTab(),
            Actions\CreateAction::make(),
        ];
    }
}
