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
        Schema::table('tickets', function (Blueprint $table) {
            $table->decimal('price_per_share', 10, 2)->nullable()->after('price');
            $table->integer('total_shares')->nullable()->after('price_per_share');
            $table->integer('available_shares')->nullable()->after('total_shares');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn(['price_per_share', 'total_shares', 'available_shares']);
        });
    }
};
