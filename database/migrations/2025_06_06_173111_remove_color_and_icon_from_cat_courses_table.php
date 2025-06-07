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
        Schema::table('cat_courses', function (Blueprint $table) {
            $table->dropColumn(['color', 'icon']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cat_courses', function (Blueprint $table) {
            $table->string('color')->default('#dc2626')->after('description');
            $table->string('icon')->nullable()->after('color');
        });
    }
};
