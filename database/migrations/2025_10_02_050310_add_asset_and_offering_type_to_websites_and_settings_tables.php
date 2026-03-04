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
            $table->string('asset_type')->nullable()->default('Common Stock');
            $table->string('offering_type')->nullable()->default('Equity');
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->string('asset_type')->nullable()->default('Common Stock');
            $table->string('offering_type')->nullable()->default('Equity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('websites', function (Blueprint $table) {
            $table->dropColumn(['asset_type', 'offering_type']);
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['asset_type', 'offering_type']);
        });
    }
};
