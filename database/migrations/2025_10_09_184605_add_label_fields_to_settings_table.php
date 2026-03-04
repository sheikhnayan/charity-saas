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
            $table->string('offering_type_label')->nullable()->default('OFFERING TYPE')->after('offering_type');
            $table->string('asset_type_label')->nullable()->default('ASSET TYPE')->after('asset_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['offering_type_label', 'asset_type_label']);
        });
    }
};
