<?php

namespace App\Actions;

use App\Models\Post;
use App\Models\PostImage;
use App\Models\PostMedia;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class OptimizePostMedia
{
    /**
     * Optimize all media for a post
     */
    public static function run(Post $post): array
    {
        $results = [
            'images_optimized' => 0,
            'media_processed' => 0,
            'total_size_saved' => 0,
            'errors' => []
        ];

        // Optimize post images
        foreach ($post->images as $image) {
            try {
                $result = self::optimizeImage($image);
                if ($result['success']) {
                    $results['images_optimized']++;
                    $results['total_size_saved'] += $result['size_saved'];
                }
            } catch (\Exception $e) {
                $results['errors'][] = "Image {$image->id}: " . $e->getMessage();
            }
        }

        // Process post media
        foreach ($post->media as $media) {
            try {
                $result = self::processMedia($media);
                if ($result['success']) {
                    $results['media_processed']++;
                }
            } catch (\Exception $e) {
                $results['errors'][] = "Media {$media->id}: " . $e->getMessage();
            }
        }

        return $results;
    }

    /**
     * Optimize a single image
     */
    public static function optimizeImage(PostImage $image): array
    {
        if (!$image->image_link || !Storage::disk('public')->exists($image->image_link)) {
            return ['success' => false, 'message' => 'File not found'];
        }

        $filePath = Storage::disk('public')->path($image->image_link);
        $originalSize = filesize($filePath);

        // Get image info if not already stored
        if (!$image->width || !$image->height || !$image->file_size || !$image->mime_type) {
            $imageInfo = getimagesize($filePath);
            if ($imageInfo) {
                $image->update([
                    'width' => $imageInfo[0],
                    'height' => $imageInfo[1],
                    'mime_type' => $imageInfo['mime'],
                    'file_size' => $originalSize,
                ]);
            }
        }

        // Convert to WebP if not already
        if (!str_ends_with($image->image_link, '.webp')) {
            try {
                $img = Image::make($filePath);
                
                // Resize if too large
                if ($img->width() > 1920) {
                    $img->resize(1920, null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                }

                // Save as WebP
                $webpPath = str_replace(['.jpg', '.jpeg', '.png'], '.webp', $filePath);
                $img->encode('webp', 85)->save($webpPath);

                $newSize = filesize($webpPath);
                $sizeSaved = $originalSize - $newSize;

                // Update database
                $newImageLink = str_replace(['.jpg', '.jpeg', '.png'], '.webp', $image->image_link);
                $image->update([
                    'image_link' => $newImageLink,
                    'file_size' => $newSize,
                    'mime_type' => 'image/webp',
                ]);

                // Delete old file
                if ($filePath !== $webpPath) {
                    Storage::disk('public')->delete($image->getOriginal('image_link'));
                }

                return [
                    'success' => true,
                    'size_saved' => $sizeSaved,
                    'message' => 'Converted to WebP'
                ];
            } catch (\Exception $e) {
                return [
                    'success' => false,
                    'message' => 'WebP conversion failed: ' . $e->getMessage()
                ];
            }
        }

        return ['success' => true, 'size_saved' => 0, 'message' => 'Already optimized'];
    }

    /**
     * Process media metadata
     */
    public static function processMedia(PostMedia $media): array
    {
        if (!$media->file_path || !Storage::disk('public')->exists($media->file_path)) {
            return ['success' => false, 'message' => 'File not found'];
        }

        $filePath = Storage::disk('public')->path($media->file_path);
        $fileSize = filesize($filePath);
        $mimeType = mime_content_type($filePath);

        $updates = [
            'file_size' => $fileSize,
            'mime_type' => $mimeType,
        ];

        // Extract metadata for video files
        if ($media->isVideo() && function_exists('shell_exec')) {
            try {
                // Use ffprobe to get video metadata (if available)
                $command = "ffprobe -v quiet -print_format json -show_format -show_streams " . escapeshellarg($filePath);
                $output = shell_exec($command);
                
                if ($output) {
                    $metadata = json_decode($output, true);
                    if (isset($metadata['format']['duration'])) {
                        $updates['duration'] = (int) $metadata['format']['duration'];
                    }
                    
                    // Store additional metadata
                    $updates['metadata'] = [
                        'format' => $metadata['format'] ?? null,
                        'streams' => $metadata['streams'] ?? null,
                    ];
                }
            } catch (\Exception $e) {
                // Fallback - just update basic info
            }
        }

        // Extract metadata for audio files
        if ($media->isAudio() && function_exists('shell_exec')) {
            try {
                $command = "ffprobe -v quiet -print_format json -show_format " . escapeshellarg($filePath);
                $output = shell_exec($command);
                
                if ($output) {
                    $metadata = json_decode($output, true);
                    if (isset($metadata['format']['duration'])) {
                        $updates['duration'] = (int) $metadata['format']['duration'];
                    }
                    
                    $updates['metadata'] = $metadata['format'] ?? null;
                }
            } catch (\Exception $e) {
                // Fallback
            }
        }

        $media->update($updates);

        return ['success' => true, 'message' => 'Metadata updated'];
    }

    /**
     * Generate thumbnail for video
     */
    public static function generateVideoThumbnail(PostMedia $media): array
    {
        if (!$media->isVideo() || !$media->file_path) {
            return ['success' => false, 'message' => 'Not a video file'];
        }

        if (!function_exists('shell_exec')) {
            return ['success' => false, 'message' => 'Shell exec not available'];
        }

        $videoPath = Storage::disk('public')->path($media->file_path);
        $thumbnailDir = 'posts/video-thumbnails';
        $thumbnailName = 'thumb-' . $media->id . '.jpg';
        $thumbnailPath = $thumbnailDir . '/' . $thumbnailName;
        $fullThumbnailPath = Storage::disk('public')->path($thumbnailPath);

        // Create directory if not exists
        Storage::disk('public')->makeDirectory($thumbnailDir);

        try {
            // Generate thumbnail at 5 seconds
            $command = "ffmpeg -i " . escapeshellarg($videoPath) . " -ss 00:00:05 -vframes 1 -y " . escapeshellarg($fullThumbnailPath);
            shell_exec($command);

            if (file_exists($fullThumbnailPath)) {
                $media->update(['thumbnail_path' => $thumbnailPath]);
                return ['success' => true, 'message' => 'Thumbnail generated'];
            }
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Thumbnail generation failed: ' . $e->getMessage()];
        }

        return ['success' => false, 'message' => 'Thumbnail generation failed'];
    }

    /**
     * Clean up orphaned media files
     */
    public static function cleanupOrphanedFiles(): array
    {
        $results = [
            'files_deleted' => 0,
            'space_freed' => 0,
            'errors' => []
        ];

        // Get all media files from database
        $dbImages = PostImage::pluck('image_link')->filter()->toArray();
        $dbMedia = PostMedia::pluck('file_path')->filter()->toArray();
        $dbThumbnails = PostMedia::pluck('thumbnail_path')->filter()->toArray();
        
        $allDbFiles = array_merge($dbImages, $dbMedia, $dbThumbnails);

        // Scan directories for orphaned files
        $directories = ['posts/thumbnails', 'posts/content', 'posts/galleries', 'posts/media', 'posts/videos', 'posts/audio'];
        
        foreach ($directories as $dir) {
            if (!Storage::disk('public')->exists($dir)) continue;
            
            $files = Storage::disk('public')->allFiles($dir);
            
            foreach ($files as $file) {
                if (!in_array($file, $allDbFiles)) {
                    try {
                        $size = Storage::disk('public')->size($file);
                        Storage::disk('public')->delete($file);
                        
                        $results['files_deleted']++;
                        $results['space_freed'] += $size;
                    } catch (\Exception $e) {
                        $results['errors'][] = "Failed to delete {$file}: " . $e->getMessage();
                    }
                }
            }
        }

        return $results;
    }
}
