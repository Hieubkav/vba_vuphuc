<?php

namespace App\Filament\Admin\Resources\PostResource\Pages;

use App\Filament\Admin\Resources\PostResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

    private ?array $categoriesData = null;

    public function getTitle(): string
    {
        return 'Thêm Bài viết Mới';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Bài viết đã được thêm thành công';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Loại bỏ categories khỏi data vì nó sẽ được xử lý trong afterCreate
        if (isset($data['categories'])) {
            $this->categoriesData = $data['categories'];
            unset($data['categories']);
        }

        // Tự động tạo content từ content_builder
        if (!empty($data['content_builder'])) {
            $data['content'] = $this->extractContentFromBuilder($data['content_builder']);
        } else {
            // Nếu không có content_builder, đặt content rỗng
            $data['content'] = '';
        }

        // Tự động tạo SEO title nếu trống
        if (empty($data['seo_title']) && !empty($data['title'])) {
            $data['seo_title'] = PostResource::generateSeoTitle($data['title']);
        }

        // Tự động tạo SEO description nếu trống từ content đã được tạo
        if (empty($data['seo_description']) && !empty($data['content'])) {
            $data['seo_description'] = PostResource::generateSeoDescription($data['content']);
        }

        // Tự động copy thumbnail làm OG image nếu OG image trống
        if (empty($data['og_image_link']) && !empty($data['thumbnail'])) {
            $data['og_image_link'] = $data['thumbnail'];
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        // Đồng bộ categories sau khi tạo record
        if ($this->categoriesData !== null) {
            $this->getRecord()->categories()->sync($this->categoriesData);
        }
    }

    /**
     * Trích xuất nội dung text từ content_builder
     */
    private function extractContentFromBuilder(array $contentBuilder): string
    {
        $content = '';

        foreach ($contentBuilder as $block) {
            if (!isset($block['type']) || !isset($block['data'])) {
                continue;
            }

            switch ($block['type']) {
                case 'paragraph':
                    if (!empty($block['data']['content'])) {
                        $content .= strip_tags($block['data']['content']) . "\n\n";
                    }
                    break;

                case 'heading':
                    if (!empty($block['data']['text'])) {
                        $content .= strip_tags($block['data']['text']) . "\n\n";
                    }
                    break;

                case 'quote':
                    if (!empty($block['data']['text'])) {
                        $content .= '"' . strip_tags($block['data']['text']) . '"' . "\n\n";
                    }
                    break;

                case 'list':
                    if (!empty($block['data']['items'])) {
                        foreach ($block['data']['items'] as $item) {
                            $content .= '- ' . strip_tags($item) . "\n";
                        }
                        $content .= "\n";
                    }
                    break;
            }
        }

        return trim($content);
    }
}