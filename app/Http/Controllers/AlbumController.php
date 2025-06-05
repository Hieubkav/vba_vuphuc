<?php

namespace App\Http\Controllers;

use App\Models\Album;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AlbumController extends Controller
{
    /**
     * Tăng số lượt xem album
     */
    public function incrementView(Album $album): JsonResponse
    {
        $album->incrementViewCount();
        
        return response()->json([
            'success' => true,
            'view_count' => $album->fresh()->view_count
        ]);
    }

    /**
     * Tăng số lượt tải album
     */
    public function incrementDownload(Album $album): JsonResponse
    {
        $album->incrementDownloadCount();
        
        return response()->json([
            'success' => true,
            'download_count' => $album->fresh()->download_count
        ]);
    }

    /**
     * Lấy danh sách ảnh của album
     */
    public function getImages(Album $album): JsonResponse
    {
        $images = $album->images()
            ->where('status', 'active')
            ->orderBy('order')
            ->orderBy('created_at')
            ->get()
            ->map(function ($image) {
                return [
                    'id' => $image->id,
                    'url' => $image->image_url,
                    'alt_text' => $image->alt_text,
                    'caption' => $image->caption,
                    'is_featured' => $image->is_featured,
                ];
            });

        return response()->json([
            'success' => true,
            'images' => $images
        ]);
    }
}
