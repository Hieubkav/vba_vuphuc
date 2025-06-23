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
        Schema::create('post_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('posts')->cascadeOnDelete();
            
            // Media type and file info
            $table->enum('media_type', ['video', 'audio', 'document', 'embed', 'download'])
                  ->comment('Loại media');
            $table->string('file_path')->nullable()->comment('Đường dẫn file');
            $table->string('file_name')->nullable()->comment('Tên file gốc');
            $table->bigInteger('file_size')->nullable()->comment('Dung lượng file (bytes)');
            $table->string('mime_type')->nullable()->comment('Loại MIME');
            
            // Content metadata
            $table->string('title')->comment('Tiêu đề media');
            $table->text('description')->nullable()->comment('Mô tả chi tiết');
            $table->string('alt_text')->nullable()->comment('Alt text');
            
            // Video/Audio specific
            $table->string('thumbnail_path')->nullable()->comment('Ảnh thumbnail cho video');
            $table->integer('duration')->nullable()->comment('Thời lượng (giây)');
            $table->json('metadata')->nullable()->comment('Metadata bổ sung');
            
            // Embed content
            $table->text('embed_code')->nullable()->comment('Mã nhúng (YouTube, Vimeo, etc.)');
            $table->string('embed_url')->nullable()->comment('URL embed');
            
            // Organization
            $table->integer('order')->default(0)->comment('Thứ tự hiển thị');
            $table->enum('status', ['active', 'inactive'])->default('active');
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['post_id', 'media_type', 'status']);
            $table->index(['post_id', 'order', 'status']);
            $table->index(['media_type', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_media');
    }
};
