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
            $table->text('investment_title')->nullable();
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->text('investment_title')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('websites', function (Blueprint $table) {
            $table->dropColumn('investment_title');
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('investment_title');
        });
    }
};
