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
            $table->decimal('share_price', 10, 2)->nullable()->after('type');
            $table->decimal('min_investment', 10, 2)->nullable()->after('share_price');
            $table->text('investment_tiers')->nullable()->after('min_investment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('websites', function (Blueprint $table) {
            $table->dropColumn(['share_price', 'min_investment', 'investment_tiers']);
        });
    }
};
