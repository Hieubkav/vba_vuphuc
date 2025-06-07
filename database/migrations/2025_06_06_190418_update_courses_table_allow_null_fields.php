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
        Schema::table('courses', function (Blueprint $table) {
            // Cho phép description và duration_hours có thể null
            $table->text('description')->nullable()->change();
            $table->integer('duration_hours')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            // Khôi phục về không null (nếu cần)
            $table->text('description')->nullable(false)->change();
            $table->integer('duration_hours')->nullable(false)->change();
        });
    }
};
