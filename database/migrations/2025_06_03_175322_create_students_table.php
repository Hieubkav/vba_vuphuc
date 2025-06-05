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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->text('address')->nullable();
            $table->string('occupation')->nullable();
            $table->enum('education_level', ['high_school', 'college', 'university', 'master', 'phd', 'other'])->nullable();
            $table->text('learning_goals')->nullable(); // Mục tiêu học tập
            $table->json('interests')->nullable(); // Sở thích, lĩnh vực quan tâm
            $table->string('avatar')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->integer('order')->default(0);
            $table->timestamps();

            // Indexes để tối ưu performance
            $table->index('email');
            $table->index(['status', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
