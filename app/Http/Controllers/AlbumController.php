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
     * Lấy thông tin PDF của album
     */
    public function getPdf(Album $album): JsonResponse
    {
        $pdfUrl = $album->pdf_file ? asset('storage/' . $album->pdf_file) : null;

        return response()->json([
            'success' => true,
            'pdf_url' => $pdfUrl,
            'title' => $album->title,
            'total_pages' => $album->total_pages,
            'file_size' => $album->file_size,
        ]);
    }
}
