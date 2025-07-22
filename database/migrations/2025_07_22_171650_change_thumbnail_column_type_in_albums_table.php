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
        Schema::table('albums', function (Blueprint $table) {
            // Thay đổi kiểu dữ liệu của trường thumbnail từ string sang json
            // để hỗ trợ lưu trữ multiple images
            $table->json('thumbnail')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('albums', function (Blueprint $table) {
            // Rollback về kiểu string
            $table->string('thumbnail')->nullable()->change();
        });
    }
};
