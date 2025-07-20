<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
/**
 * File Manager Page - Quản lý tất cả file upload trong dự án
 * Hỗ trợ: Images, Documents, Videos, Audio và các file khác
 */
class FileManager extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-folder';

    protected static ?string $navigationLabel = 'Quản lý File';

    protected static ?string $title = 'Quản lý File Upload';

    protected static ?string $navigationGroup = 'CÀI ĐẶT WEBSITE';

    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.admin.file-manager';

    public $files = [];
    public $totalFiles = 0;
    public $totalSize = 0;
    public $unusedFiles = [];
    public $currentFilter = 'all';
    public $searchTerm = '';

    public function mount()
    {
        $this->loadFiles();
    }

    public function loadFiles()
    {
        $this->files = [];
        $this->totalFiles = 0;
        $this->totalSize = 0;
        $this->unusedFiles = [];

        // Tất cả thư mục chứa file upload
        $directories = [
            'courses', 'posts', 'sliders', 'testimonials', 'associations', 'albums',
            'course-materials', 'instructors', 'partners', 'settings', 'students',
            'employees', 'products', 'services', 'carousel', 'delivery-routes',
            'cat-courses', 'product-categories'
        ];

        foreach ($directories as $dir) {
            if (Storage::disk('public')->exists($dir)) {
                $files = Storage::disk('public')->allFiles($dir);

                foreach ($files as $file) {
                    $extension = pathinfo($file, PATHINFO_EXTENSION);
                    $size = Storage::disk('public')->size($file);
                    $isUsed = $this->checkFileUsage($file);
                    $fileType = $this->getFileType($extension);

                    $fileData = [
                        'path' => $file,
                        'name' => basename($file),
                        'size' => $size,
                        'formatted_size' => $this->formatFileSize($size),
                        'extension' => strtolower($extension),
                        'file_type' => $fileType,
                        'mime_type' => $this->getMimeType($file),
                        'url' => asset('storage/' . $file),
                        'is_used' => $isUsed,
                        'created_at' => date('d/m/Y H:i', Storage::disk('public')->lastModified($file)),
                        'icon' => $this->getFileIcon($fileType, $extension),
                        'can_preview' => $this->canPreview($fileType),
                    ];

                    $this->totalFiles++;
                    $this->totalSize += $size;

                    if (!$isUsed) {
                        $this->unusedFiles[] = $fileData;
                    }

                    // Filter theo loại file nếu có
                    if ($this->currentFilter === 'unused') {
                        $matchesFilter = !$isUsed; // Chỉ hiển thị file không sử dụng
                    } else {
                        $matchesFilter = $this->currentFilter === 'all' || $this->matchesFilter($fileType);
                    }

                    if ($matchesFilter) {
                        // Filter theo search term nếu có
                        if (empty($this->searchTerm) ||
                            stripos($fileData['name'], $this->searchTerm) !== false) {
                            $this->files[] = $fileData;
                        }
                    }
                }
            }
        }
        
        // Sort by created date desc
        usort($this->files, function($a, $b) {
            return Storage::disk('public')->lastModified($b['path']) - Storage::disk('public')->lastModified($a['path']);
        });
    }

    public function setFilter($filter)
    {
        $this->currentFilter = $filter;
        $this->loadFiles();
    }

    public function updateSearch()
    {
        $this->loadFiles();
    }

    public function deleteFile($filePath)
    {
        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);

            Notification::make()
                ->title('Đã xóa file thành công')
                ->success()
                ->send();

            $this->loadFiles(); // Reload
        }
    }

    public function deleteUnusedFiles()
    {
        $deleted = 0;

        foreach ($this->unusedFiles as $file) {
            if (Storage::disk('public')->exists($file['path'])) {
                Storage::disk('public')->delete($file['path']);
                $deleted++;
            }
        }

        Notification::make()
            ->title("Đã xóa {$deleted} file không sử dụng")
            ->success()
            ->send();

        $this->loadFiles(); // Reload
    }

    private function checkFileUsage(string $filePath): bool
    {
        $models = [
            \App\Models\Course::class => ['thumbnail', 'og_image_link'],
            \App\Models\Post::class => ['thumbnail', 'og_image_link'],
            \App\Models\Slider::class => ['image_link'],
            \App\Models\Testimonial::class => ['avatar'],
            \App\Models\Association::class => ['image_link'],
            \App\Models\CourseMaterial::class => ['file_path'],
            \App\Models\Album::class => ['thumbnail', 'pdf_file'],
            \App\Models\Instructor::class => ['avatar'],
            \App\Models\Partner::class => ['logo_link'], // Sửa từ 'logo' thành 'logo_link'
            \App\Models\Student::class => ['avatar'],
            \App\Models\Setting::class => ['logo_link', 'favicon_link', 'og_image_link', 'placeholder_image'],
            \App\Models\CatCourse::class => ['image', 'og_image_link'], // Thêm CatCourse
        ];

        // Kiểm tra các model có tồn tại không
        if (class_exists(\App\Models\PostImage::class)) {
            $models[\App\Models\PostImage::class] = ['image_link'];
        }
        if (class_exists(\App\Models\CourseImage::class)) {
            $models[\App\Models\CourseImage::class] = ['image_link'];
        }

        foreach ($models as $model => $fields) {
            foreach ($fields as $field) {
                try {
                    if ($model::where($field, $filePath)->exists()) {
                        return true;
                    }
                } catch (\Exception) {
                    // Bỏ qua nếu bảng không tồn tại
                    continue;
                }
            }
        }

        // Kiểm tra đặc biệt cho Post content_builder (JSON field)
        if ($this->checkPostBuilderUsage($filePath)) {
            return true;
        }

        return false;
    }

    /**
     * Kiểm tra file có được sử dụng trong Post content_builder không
     */
    private function checkPostBuilderUsage(string $filePath): bool
    {
        try {
            // Kiểm tra trong content_builder JSON field
            $posts = \App\Models\Post::whereNotNull('content_builder')->get();

            foreach ($posts as $post) {
                if (empty($post->content_builder)) {
                    continue;
                }

                $contentBuilder = is_string($post->content_builder)
                    ? json_decode($post->content_builder, true)
                    : $post->content_builder;

                if (!is_array($contentBuilder)) {
                    continue;
                }

                // Tìm kiếm file path trong JSON
                if ($this->searchInArray($contentBuilder, $filePath)) {
                    return true;
                }
            }

            return false;
        } catch (\Exception) {
            return false;
        }
    }

    /**
     * Tìm kiếm file path trong array đa chiều
     */
    private function searchInArray(array $array, string $needle): bool
    {
        foreach ($array as $value) {
            if (is_array($value)) {
                if ($this->searchInArray($value, $needle)) {
                    return true;
                }
            } elseif (is_string($value) && str_contains($value, $needle)) {
                return true;
            }
        }

        return false;
    }

    private function formatFileSize(int $bytes): string
    {
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return round($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' B';
    }

    public function getFormattedTotalSize(): string
    {
        return $this->formatFileSize($this->totalSize);
    }

    private function getFileType(string $extension): string
    {
        $extension = strtolower($extension);

        $imageTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp'];
        $documentTypes = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt'];
        $videoTypes = ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm'];
        $audioTypes = ['mp3', 'wav', 'aac', 'ogg', 'wma'];

        if (in_array($extension, $imageTypes)) return 'image';
        if (in_array($extension, $documentTypes)) return 'document';
        if (in_array($extension, $videoTypes)) return 'video';
        if (in_array($extension, $audioTypes)) return 'audio';

        return 'other';
    }

    private function getMimeType(string $filePath): string
    {
        try {
            $fullPath = storage_path('app/public/' . $filePath);
            if (file_exists($fullPath)) {
                return mime_content_type($fullPath) ?? 'application/octet-stream';
            }
            return 'application/octet-stream';
        } catch (\Exception) {
            return 'application/octet-stream';
        }
    }

    private function getFileIcon(string $fileType, string $extension): string
    {
        return match($fileType) {
            'image' => 'heroicon-o-photo',
            'document' => match(strtolower($extension)) {
                'pdf' => 'heroicon-o-document-text',
                'doc', 'docx' => 'heroicon-o-document',
                'xls', 'xlsx' => 'heroicon-o-table-cells',
                'ppt', 'pptx' => 'heroicon-o-presentation-chart-bar',
                default => 'heroicon-o-document'
            },
            'video' => 'heroicon-o-video-camera',
            'audio' => 'heroicon-o-musical-note',
            default => 'heroicon-o-document'
        };
    }

    private function canPreview(string $fileType): bool
    {
        return in_array($fileType, ['image', 'document']);
    }

    private function matchesFilter(string $fileType): bool
    {
        return match($this->currentFilter) {
            'images' => $fileType === 'image',
            'documents' => $fileType === 'document',
            'videos' => $fileType === 'video',
            'audio' => $fileType === 'audio',
            default => true
        };
    }

    public function getFilterCounts(): array
    {
        $counts = [
            'all' => $this->totalFiles,
            'images' => 0,
            'documents' => 0,
            'videos' => 0,
            'audio' => 0,
            'unused' => count($this->unusedFiles)
        ];

        // Đếm theo loại file (cần load lại toàn bộ để đếm chính xác)
        $tempFilter = $this->currentFilter;
        $this->currentFilter = 'all';
        $this->loadFiles();

        foreach ($this->files as $file) {
            $counts[$file['file_type'] . 's']++;
        }

        $this->currentFilter = $tempFilter;
        $this->loadFiles();

        return $counts;
    }
}
