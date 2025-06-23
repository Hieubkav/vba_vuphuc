<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('post_images', function (Blueprint $table) {
            // Image type classification
            $table->enum('image_type', ['gallery', 'inline', 'featured', 'thumbnail'])
                  ->default('gallery')
                  ->after('image_link')
                  ->comment('Loại hình ảnh');
            
            // Enhanced metadata
            $table->text('caption')->nullable()->after('alt_text')->comment('Chú thích ảnh');
            $table->integer('width')->nullable()->after('caption')->comment('Chiều rộng (px)');
            $table->integer('height')->nullable()->after('width')->comment('Chiều cao (px)');
            $table->bigInteger('file_size')->nullable()->after('height')->comment('Dung lượng file (bytes)');
            $table->string('mime_type')->nullable()->after('file_size')->comment('Loại file');
            
            // SEO and accessibility
            $table->string('title')->nullable()->after('mime_type')->comment('Tiêu đề ảnh');
            $table->text('description')->nullable()->after('title')->comment('Mô tả chi tiết');
            
            // Performance indexes
            $table->index(['post_id', 'image_type', 'status']);
            $table->index(['post_id', 'order', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('post_images', function (Blueprint $table) {
            // Drop indexes first
            $table->dropIndex(['post_id', 'image_type', 'status']);
            $table->dropIndex(['post_id', 'order', 'status']);
            
            // Drop columns
            $table->dropColumn([
                'image_type',
                'caption',
                'width',
                'height', 
                'file_size',
                'mime_type',
                'title',
                'description'
            ]);
        });
    }
};
