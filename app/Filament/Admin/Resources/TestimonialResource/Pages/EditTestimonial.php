<?php

namespace App\Filament\Admin\Resources\TestimonialResource\Pages;

use App\Filament\Admin\Resources\TestimonialResource;
use App\Helpers\AvatarHelper;
use App\Providers\ViewServiceProvider;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTestimonial extends EditRecord
{
    protected static string $resource = TestimonialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->after(function () {
                    // Clear cache after deleting testimonial
                    ViewServiceProvider::refreshCache('storefront');
                    ViewServiceProvider::refreshCache('testimonials');
                }),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Xử lý avatar preservation
        if (!array_key_exists('avatar', $data) || empty($data['avatar'])) {
            // Nếu không có avatar field trong data (do dehydrated = false) hoặc avatar rỗng
            if ($this->record->avatar) {
                if (AvatarHelper::isInitialsAvatar($this->record->avatar)) {
                    // Nếu là avatar chữ cái, giữ nguyên
                    $data['avatar'] = $this->record->avatar;
                } else {
                    // Nếu có ảnh thật hiện tại, giữ nguyên
                    $data['avatar'] = $this->record->avatar;
                }
            } else {
                // Nếu chưa có avatar, tạo avatar chữ cái
                $data['avatar'] = AvatarHelper::generateAvatarString($data['name']);
            }
        }
        // Nếu có avatar mới (upload file), để nguyên data['avatar'] từ FileUpload

        return $data;
    }

    protected function afterSave(): void
    {
        // Clear cache after saving testimonial
        ViewServiceProvider::refreshCache('storefront');
        ViewServiceProvider::refreshCache('testimonials');
    }
}
