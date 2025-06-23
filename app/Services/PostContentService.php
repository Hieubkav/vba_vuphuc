<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Support\Str;

class PostContentService
{
    /**
     * Tự động tính toán thời gian đọc từ nội dung
     */
    public function calculateReadingTime(Post $post): int
    {
        $content = '';
        
        // Lấy nội dung từ content_builder
        if ($post->content_builder && is_array($post->content_builder)) {
            foreach ($post->content_builder as $block) {
                if (isset($block['data']['content'])) {
                    $content .= ' ' . strip_tags($block['data']['content']);
                }
                if (isset($block['data']['text'])) {
                    $content .= ' ' . $block['data']['text'];
                }
            }
        }
        
        // Thêm nội dung truyền thống nếu có
        if ($post->content) {
            $content .= ' ' . strip_tags($post->content);
        }
        
        $wordCount = str_word_count($content);
        return max(1, ceil($wordCount / 200)); // 200 từ/phút
    }

    /**
     * Tự động tạo excerpt từ nội dung
     */
    public function generateExcerpt(Post $post, int $length = 160): string
    {
        $content = '';
        
        // Ưu tiên lấy từ paragraph đầu tiên trong content_builder
        if ($post->content_builder && is_array($post->content_builder)) {
            foreach ($post->content_builder as $block) {
                if ($block['type'] === 'paragraph' && isset($block['data']['content'])) {
                    $content = strip_tags($block['data']['content']);
                    break;
                }
            }
        }
        
        // Fallback sang content truyền thống
        if (empty($content) && $post->content) {
            $content = strip_tags($post->content);
        }
        
        $content = preg_replace('/\s+/', ' ', trim($content));
        
        if (strlen($content) <= $length) {
            return $content;
        }
        
        $truncated = substr($content, 0, $length - 3);
        $lastSpace = strrpos($truncated, ' ');
        
        if ($lastSpace !== false) {
            $truncated = substr($truncated, 0, $lastSpace);
        }
        
        return $truncated . '...';
    }

    /**
     * Tự động tạo SEO title
     */
    public function generateSeoTitle(Post $post): string
    {
        $title = $post->title;
        $maxLength = 60;
        
        if (strlen($title) <= $maxLength) {
            return $title;
        }
        
        $truncated = substr($title, 0, $maxLength - 3);
        $lastSpace = strrpos($truncated, ' ');
        
        if ($lastSpace !== false) {
            $truncated = substr($truncated, 0, $lastSpace);
        }
        
        return $truncated . '...';
    }

    /**
     * Tự động tạo SEO description
     */
    public function generateSeoDescription(Post $post): string
    {
        if ($post->excerpt) {
            return Str::limit($post->excerpt, 155);
        }
        
        return Str::limit($this->generateExcerpt($post, 155), 155);
    }

    /**
     * Lấy tất cả hình ảnh từ content_builder
     */
    public function extractImagesFromContent(Post $post): array
    {
        $images = [];
        
        if ($post->content_builder && is_array($post->content_builder)) {
            foreach ($post->content_builder as $block) {
                if ($block['type'] === 'image' && !empty($block['data']['image'])) {
                    $images[] = [
                        'path' => $block['data']['image'],
                        'alt' => $block['data']['alt'] ?? '',
                        'caption' => $block['data']['caption'] ?? '',
                        'alignment' => $block['data']['alignment'] ?? 'center',
                    ];
                }
                
                if ($block['type'] === 'gallery' && !empty($block['data']['images'])) {
                    foreach ($block['data']['images'] as $image) {
                        $images[] = [
                            'path' => $image,
                            'alt' => 'Gallery image',
                            'caption' => $block['data']['caption'] ?? '',
                            'alignment' => 'center',
                        ];
                    }
                }
            }
        }
        
        return $images;
    }

    /**
     * Kiểm tra xem bài viết có nội dung đa dạng không
     */
    public function hasRichContent(Post $post): bool
    {
        if (!$post->content_builder || !is_array($post->content_builder)) {
            return false;
        }
        
        // Kiểm tra có block nào khác paragraph không
        foreach ($post->content_builder as $block) {
            if (isset($block['type']) && $block['type'] !== 'paragraph') {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Lấy thống kê nội dung
     */
    public function getContentStats(Post $post): array
    {
        $stats = [
            'word_count' => 0,
            'reading_time' => 0,
            'image_count' => 0,
            'gallery_count' => 0,
            'heading_count' => 0,
            'paragraph_count' => 0,
        ];
        
        if ($post->content_builder && is_array($post->content_builder)) {
            foreach ($post->content_builder as $block) {
                $type = $block['type'] ?? '';
                
                switch ($type) {
                    case 'paragraph':
                        $stats['paragraph_count']++;
                        if (isset($block['data']['content'])) {
                            $stats['word_count'] += str_word_count(strip_tags($block['data']['content']));
                        }
                        break;
                    case 'heading':
                        $stats['heading_count']++;
                        break;
                    case 'image':
                        $stats['image_count']++;
                        break;
                    case 'gallery':
                        $stats['gallery_count']++;
                        if (isset($block['data']['images']) && is_array($block['data']['images'])) {
                            $stats['image_count'] += count($block['data']['images']);
                        }
                        break;
                }
            }
        }
        
        // Thêm từ content truyền thống
        if ($post->content) {
            $stats['word_count'] += str_word_count(strip_tags($post->content));
        }
        
        $stats['reading_time'] = max(1, ceil($stats['word_count'] / 200));
        
        return $stats;
    }

    /**
     * Cập nhật tự động các trường SEO và metadata
     */
    public function updateAutoFields(Post $post): void
    {
        // Tự động tính reading time nếu chưa có
        if (!$post->reading_time) {
            $post->reading_time = $this->calculateReadingTime($post);
        }
        
        // Tự động tạo excerpt nếu chưa có
        if (!$post->excerpt) {
            $post->excerpt = $this->generateExcerpt($post);
        }
        
        // Tự động tạo SEO title nếu chưa có
        if (!$post->seo_title) {
            $post->seo_title = $this->generateSeoTitle($post);
        }
        
        // Tự động tạo SEO description nếu chưa có
        if (!$post->seo_description) {
            $post->seo_description = $this->generateSeoDescription($post);
        }
        
        // Tự động set published_at nếu chưa có và status là active
        if (!$post->published_at && $post->status === 'active') {
            $post->published_at = now();
        }
    }
}
