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
        Schema::table('course_groups', function (Blueprint $table) {
            // Xóa các cột không cần thiết theo nguyên tắc KISS
            $table->dropColumn([
                'level',
                'color',
                'instructor_name',
                'instructor_bio',
                'thumbnail',
                'icon',
                'is_featured'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_groups', function (Blueprint $table) {
            // Khôi phục các cột đã xóa
            $table->enum('level', ['beginner', 'intermediate', 'advanced'])->default('beginner')->after('group_type');
            $table->string('color', 7)->default('#dc2626')->after('level');
            $table->string('instructor_name')->nullable()->after('current_members');
            $table->text('instructor_bio')->nullable()->after('instructor_name');
            $table->string('thumbnail')->nullable()->after('description');
            $table->string('icon')->default('fas fa-users')->after('color');
            $table->boolean('is_featured')->default(false)->after('icon');
        });
    }
};
