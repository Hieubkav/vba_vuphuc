<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Chuyển đổi trường thumbnail từ string sang JSON array
        // để hỗ trợ multiple images

        // Lấy tất cả albums có thumbnail
        $albums = DB::table('albums')
            ->whereNotNull('thumbnail')
            ->where('thumbnail', '!=', '')
            ->get();

        foreach ($albums as $album) {
            // Nếu thumbnail hiện tại là string, chuyển thành array
            if (!empty($album->thumbnail)) {
                // Kiểm tra xem có phải đã là JSON không
                $decoded = json_decode($album->thumbnail, true);

                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    // Đã là JSON array, không cần convert
                    continue;
                }

                // Convert string thành array
                $thumbnailArray = [$album->thumbnail];

                DB::table('albums')
                    ->where('id', $album->id)
                    ->update([
                        'thumbnail' => json_encode($thumbnailArray)
                    ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Chuyển đổi ngược từ JSON array về string (lấy phần tử đầu tiên)

        $albums = DB::table('albums')
            ->whereNotNull('thumbnail')
            ->where('thumbnail', '!=', '')
            ->get();

        foreach ($albums as $album) {
            if (!empty($album->thumbnail)) {
                $decoded = json_decode($album->thumbnail, true);

                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded) && !empty($decoded)) {
                    // Lấy phần tử đầu tiên của array
                    $firstThumbnail = $decoded[0];

                    DB::table('albums')
                        ->where('id', $album->id)
                        ->update([
                            'thumbnail' => $firstThumbnail
                        ]);
                }
            }
        }
    }
};
