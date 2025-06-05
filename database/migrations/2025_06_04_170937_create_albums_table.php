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
        Schema::create('albums', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('og_image_link')->nullable();
            $table->string('slug')->unique();
            $table->string('pdf_file')->nullable();
            $table->string('thumbnail')->nullable();
            $table->date('published_date')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->integer('order')->default(0);
            $table->boolean('featured')->default(false);
            $table->integer('total_pages')->nullable();
            $table->bigInteger('file_size')->nullable(); // bytes
            $table->integer('download_count')->default(0);
            $table->integer('view_count')->default(0);
            $table->timestamps();

            // Indexes
            $table->index(['status', 'order']);
            $table->index(['featured', 'published_date']);
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('albums');
    }
};
