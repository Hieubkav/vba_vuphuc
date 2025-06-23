<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Di chuyển dữ liệu từ quan hệ one-to-many (category_id) sang many-to-many (post_categories)
        $posts = DB::table('posts')
            ->whereNotNull('category_id')
            ->select('id', 'category_id')
            ->get();

        foreach ($posts as $post) {
            // Kiểm tra xem đã có record trong post_categories chưa
            $exists = DB::table('post_categories')
                ->where('post_id', $post->id)
                ->where('cat_post_id', $post->category_id)
                ->exists();

            if (!$exists) {
                DB::table('post_categories')->insert([
                    'post_id' => $post->id,
                    'cat_post_id' => $post->category_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Xóa tất cả dữ liệu trong post_categories (có thể khôi phục từ category_id)
        DB::table('post_categories')->truncate();
    }
};
