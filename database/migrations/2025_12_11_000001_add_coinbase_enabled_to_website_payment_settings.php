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
            $table->boolean('coinbase_enabled')->default(false)->after('authorize_sandbox');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('website_payment_settings', function (Blueprint $table) {
            $table->dropColumn('coinbase_enabled');
        });
    }
};
