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
            // Thêm type và post fields cho footer policies
            $table->string('footer_policy_1_type')->default('custom')->after('footer_policy_1_title');
            $table->string('footer_policy_1_post')->nullable()->after('footer_policy_1_url');
            $table->string('footer_policy_2_type')->default('custom')->after('footer_policy_2_title');
            $table->string('footer_policy_2_post')->nullable()->after('footer_policy_2_url');
            $table->string('footer_policy_3_type')->default('custom')->after('footer_policy_3_title');
            $table->string('footer_policy_3_post')->nullable()->after('footer_policy_3_url');

            // Thêm company info fields cho footer
            $table->string('footer_company_brand_name')->default('VUPHUC BAKING®')->after('footer_copyright');
            $table->string('footer_company_business_license')->default('Giấy phép kinh doanh số 1800935879 cấp ngày 29/4/2009')->after('footer_company_brand_name');
            $table->string('footer_company_director_info')->default('Chịu trách nhiệm nội dung: Trần Uy Vũ - Tổng Giám đốc')->after('footer_company_business_license');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('web_designs', function (Blueprint $table) {
            $table->dropColumn([
                'footer_policy_1_type',
                'footer_policy_1_post',
                'footer_policy_2_type',
                'footer_policy_2_post',
                'footer_policy_3_type',
                'footer_policy_3_post',
                'footer_company_brand_name',
                'footer_company_business_license',
                'footer_company_director_info',
            ]);
        });
    }
};
