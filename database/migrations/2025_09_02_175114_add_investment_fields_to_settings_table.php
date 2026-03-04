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
        Schema::table('settings', function (Blueprint $table) {
            $table->string('deal_id')->nullable();
            $table->decimal('share_price', 8, 2)->default(1.00);
            $table->decimal('min_investment', 10, 2)->default(1000.00);
            $table->text('investment_tiers')->nullable(); // Comma-separated values
            $table->string('button_color')->default('#007bff');
            $table->string('brand_color')->default('#000000');
            $table->string('company_email')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('favicon')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'deal_id', 'share_price', 'min_investment', 'investment_tiers',
                'button_color', 'brand_color', 'company_email', 'facebook_url',
                'instagram_url', 'twitter_url', 'favicon'
            ]);
        });
    }
};
