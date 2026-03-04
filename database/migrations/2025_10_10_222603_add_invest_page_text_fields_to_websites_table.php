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
        Schema::table('websites', function (Blueprint $table) {
            $table->string('invest_page_title')->nullable()->default('Complete Your Investment')->after('additional_information');
            $table->string('invest_amount_title')->nullable()->default('Select Investment Amount')->after('invest_page_title');
            $table->string('share_price_label')->nullable()->default('SHARE PRICE')->after('invest_amount_title');
            $table->string('minimum_investment_label')->nullable()->default('MINIMUM INVESTMENT')->after('share_price_label');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('websites', function (Blueprint $table) {
            $table->dropColumn(['invest_page_title', 'invest_amount_title', 'share_price_label', 'minimum_investment_label']);
        });
    }
};
