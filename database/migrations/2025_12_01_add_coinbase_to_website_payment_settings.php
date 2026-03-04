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
        Schema::table('website_payment_settings', function (Blueprint $table) {
            $table->text('coinbase_api_key')->nullable()->after('authorize_sandbox');
            $table->text('coinbase_webhook_secret')->nullable()->after('coinbase_api_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('website_payment_settings', function (Blueprint $table) {
            $table->dropColumn(['coinbase_api_key', 'coinbase_webhook_secret']);
        });
    }
};
