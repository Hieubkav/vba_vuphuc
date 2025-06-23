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
        Schema::table('posts', function (Blueprint $table) {
            // Content builder fields
            $table->json('content_builder')->nullable()->after('content')->comment('Structured content blocks');
            $table->text('excerpt')->nullable()->after('content_builder')->comment('Tóm tắt bài viết');
            $table->integer('reading_time')->nullable()->after('excerpt')->comment('Thời gian đọc (phút)');
            
            // Enhanced image fields
            $table->string('featured_image_alt')->nullable()->after('thumbnail')->comment('Alt text cho thumbnail');
            
            // SEO enhancements
            $table->string('meta_keywords')->nullable()->after('seo_description')->comment('Từ khóa SEO');
            
            // Publishing fields
            $table->boolean('is_featured')->default(false)->after('status')->comment('Bài viết nổi bật');
            $table->timestamp('published_at')->nullable()->after('is_featured')->comment('Thời gian xuất bản');
            
            // Indexes for performance
            $table->index(['is_featured', 'status', 'published_at']);
            $table->index(['category_id', 'status', 'published_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // Drop indexes first
            $table->dropIndex(['is_featured', 'status', 'published_at']);
            $table->dropIndex(['category_id', 'status', 'published_at']);
            
            // Drop columns
            $table->dropColumn([
                'content_builder',
                'excerpt', 
                'reading_time',
                'featured_image_alt',
                'meta_keywords',
                'is_featured',
                'published_at'
            ]);
        });
    }
};
