<?php

namespace App\Filament\Admin\Resources\CourseMaterialResource\Pages;

use App\Filament\Admin\Resources\CourseMaterialResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;

class CreateCourseMaterial extends CreateRecord
{
    protected static string $resource = CourseMaterialResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Tự động lấy thông tin file khi upload
        if (isset($data['file_path']) && $data['file_path']) {
            $filePath = $data['file_path'];

            // Lấy tên file từ path
            $data['file_name'] = basename($filePath);

            // Thử lấy thông tin file nếu tồn tại
            if (Storage::exists($filePath)) {
                $data['file_type'] = Storage::mimeType($filePath) ?: 'application/octet-stream';
                $data['file_size'] = Storage::size($filePath) ?: 0;
            } else {
                // Fallback nếu file chưa tồn tại
                $extension = pathinfo($filePath, PATHINFO_EXTENSION);
                $data['file_type'] = $this->getMimeTypeFromExtension($extension);
                $data['file_size'] = 0;
            }
        } else {
            // Fallback values nếu không có file
            $data['file_name'] = $data['title'] ?? 'unknown';
            $data['file_type'] = 'application/octet-stream';
            $data['file_size'] = 0;
        }

        return $data;
    }

    private function getMimeTypeFromExtension(string $extension): string
    {
        $mimeTypes = [
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'ppt' => 'application/vnd.ms-powerpoint',
            'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'txt' => 'text/plain',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'webp' => 'image/webp',
            'mp4' => 'video/mp4',
            'mp3' => 'audio/mpeg',
            'wav' => 'audio/wav',
        ];

        return $mimeTypes[strtolower($extension)] ?? 'application/octet-stream';
    }
}
