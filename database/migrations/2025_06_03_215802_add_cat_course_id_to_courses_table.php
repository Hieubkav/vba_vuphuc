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
            $table->foreignId('cat_course_id')->nullable()->after('category_id')->constrained('cat_courses')->nullOnDelete();
            $table->index(['cat_course_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign(['cat_course_id']);
            $table->dropIndex(['cat_course_id', 'status']);
            $table->dropColumn('cat_course_id');
        });
    }
};
