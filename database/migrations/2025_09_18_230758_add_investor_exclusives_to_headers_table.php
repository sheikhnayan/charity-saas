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
        Schema::table('headers', function (Blueprint $table) {
            $table->boolean('show_investor_exclusives')->default(false);
            $table->string('investor_exclusives_text')->default('EXPLORE INVESTOR EXCLUSIVES');
            $table->string('investor_exclusives_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('headers', function (Blueprint $table) {
            $table->dropColumn(['show_investor_exclusives', 'investor_exclusives_text', 'investor_exclusives_url']);
        });
    }
};
