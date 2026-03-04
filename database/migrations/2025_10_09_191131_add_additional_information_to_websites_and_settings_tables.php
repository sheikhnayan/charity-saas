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
            $table->text('additional_information')->nullable()->after('investment_disclaimer');
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->text('additional_information')->nullable()->after('investment_disclaimer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('websites', function (Blueprint $table) {
            $table->dropColumn('additional_information');
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('additional_information');
        });
    }
};
