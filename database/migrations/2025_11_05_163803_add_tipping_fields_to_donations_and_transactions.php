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
        // Add tipping fields to donations table
        Schema::table('donations', function (Blueprint $table) {
            $table->decimal('tip_amount', 10, 2)->default(0)->after('amount');
            $table->decimal('tip_percentage', 5, 2)->nullable()->after('tip_amount');
            $table->boolean('tip_enabled')->default(0)->after('tip_percentage');
        });

        // Add tipping fields to transactions table
        Schema::table('transactions', function (Blueprint $table) {
            $table->decimal('tip_amount', 10, 2)->default(0)->after('amount');
            $table->decimal('tip_percentage', 5, 2)->nullable()->after('tip_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropColumn(['tip_amount', 'tip_percentage', 'tip_enabled']);
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['tip_amount', 'tip_percentage']);
        });
    }
};
