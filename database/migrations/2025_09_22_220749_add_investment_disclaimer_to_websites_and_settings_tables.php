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
        // Add investment_disclaimer to websites table
        Schema::table('websites', function (Blueprint $table) {
            if (!Schema::hasColumn('websites', 'investment_disclaimer')) {
                $table->text('investment_disclaimer')->nullable();
            }
        });

        // Add investment_disclaimer to settings table
        Schema::table('settings', function (Blueprint $table) {
            if (!Schema::hasColumn('settings', 'investment_disclaimer')) {
                $table->text('investment_disclaimer')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('websites', function (Blueprint $table) {
            $table->dropColumn('investment_disclaimer');
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('investment_disclaimer');
        });
    }
};
