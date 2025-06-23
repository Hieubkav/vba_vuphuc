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
            // Drop unused fields
            if (Schema::hasColumn('posts', 'featured_image_alt')) {
                $table->dropColumn('featured_image_alt');
            }
            if (Schema::hasColumn('posts', 'meta_keywords')) {
                $table->dropColumn('meta_keywords');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // Restore dropped fields
            $table->string('featured_image_alt')->nullable()->after('thumbnail')->comment('Alt text cho thumbnail');
            $table->string('meta_keywords')->nullable()->after('seo_description')->comment('Từ khóa SEO');
        });
    }
};
