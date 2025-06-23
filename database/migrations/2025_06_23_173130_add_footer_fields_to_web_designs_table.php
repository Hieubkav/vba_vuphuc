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
        Schema::table('web_designs', function (Blueprint $table) {
            // Footer fields
            $table->boolean('footer_enabled')->default(true)->after('homepage_cta_secondary_button_url');
            $table->integer('footer_order')->default(10)->after('footer_enabled');
            $table->string('footer_policy_1_title')->nullable()->after('footer_order');
            $table->string('footer_policy_1_url')->nullable()->after('footer_policy_1_title');
            $table->string('footer_policy_2_title')->nullable()->after('footer_policy_1_url');
            $table->string('footer_policy_2_url')->nullable()->after('footer_policy_2_title');
            $table->string('footer_policy_3_title')->nullable()->after('footer_policy_2_url');
            $table->string('footer_policy_3_url')->nullable()->after('footer_policy_3_title');
            $table->text('footer_copyright')->nullable()->after('footer_policy_3_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('web_designs', function (Blueprint $table) {
            $table->dropColumn([
                'footer_enabled',
                'footer_order',
                'footer_policy_1_title',
                'footer_policy_1_url',
                'footer_policy_2_title',
                'footer_policy_2_url',
                'footer_policy_3_title',
                'footer_policy_3_url',
                'footer_copyright',
            ]);
        });
    }
};
