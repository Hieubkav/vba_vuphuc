<?php

namespace App\Filament\Admin\Resources\CourseResource\Pages;

use App\Filament\Admin\Resources\CourseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCourse extends EditRecord
{
    protected static string $resource = CourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Đảm bảo requirements và what_you_learn là array hợp lệ
        if (isset($data['requirements'])) {
            if (is_string($data['requirements'])) {
                $decoded = json_decode($data['requirements'], true);
                $data['requirements'] = (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) ? $decoded : [];
            } elseif (!is_array($data['requirements'])) {
                $data['requirements'] = [];
            }
        } else {
            $data['requirements'] = [];
        }

        if (isset($data['what_you_learn'])) {
            if (is_string($data['what_you_learn'])) {
                $decoded = json_decode($data['what_you_learn'], true);
                $data['what_you_learn'] = (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) ? $decoded : [];
            } elseif (!is_array($data['what_you_learn'])) {
                $data['what_you_learn'] = [];
            }
        } else {
            $data['what_you_learn'] = [];
        }

        return $data;
    }
}
