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
        Schema::table('property_financials', function (Blueprint $table) {
            // Convert all financial value columns from decimal to text
            $table->text('total_investment_value')->nullable()->change();
            $table->text('underlying_asset_price')->nullable()->change();
            $table->text('closing_costs')->nullable()->change();
            $table->text('upfront_fees')->nullable()->change();
            
            $table->text('projected_annual_return')->nullable()->change();
            $table->text('projected_rental_yield')->nullable()->change();
            $table->text('projected_appreciation')->nullable()->change();
            $table->text('rental_yield')->nullable()->change();
            
            $table->text('annual_gross_rents')->nullable()->change();
            $table->text('property_taxes')->nullable()->change();
            $table->text('homeowners_insurance')->nullable()->change();
            $table->text('property_management')->nullable()->change();
            $table->text('annual_llc_fees')->nullable()->change();
            $table->text('annual_cash_flow')->nullable()->change();
            $table->text('cap_rate')->nullable()->change();
            $table->text('monthly_cash_flow')->nullable()->change();
            $table->text('projected_annual_cash_flow')->nullable()->change();
            $table->text('current_loan')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('property_financials', function (Blueprint $table) {
            // Revert back to decimal
            $table->decimal('total_investment_value', 15, 2)->nullable()->change();
            $table->decimal('underlying_asset_price', 15, 2)->nullable()->change();
            $table->decimal('closing_costs', 15, 2)->nullable()->change();
            $table->decimal('upfront_fees', 15, 2)->nullable()->change();
            
            $table->decimal('projected_annual_return', 8, 2)->nullable()->change();
            $table->decimal('projected_rental_yield', 8, 2)->nullable()->change();
            $table->decimal('projected_appreciation', 8, 2)->nullable()->change();
            $table->decimal('rental_yield', 8, 2)->nullable()->change();
            
            $table->decimal('annual_gross_rents', 15, 2)->nullable()->change();
            $table->decimal('property_taxes', 15, 2)->nullable()->change();
            $table->decimal('homeowners_insurance', 15, 2)->nullable()->change();
            $table->decimal('property_management', 15, 2)->nullable()->change();
            $table->decimal('annual_llc_fees', 15, 2)->nullable()->change();
            $table->decimal('annual_cash_flow', 15, 2)->nullable()->change();
            $table->decimal('cap_rate', 8, 2)->nullable()->change();
            $table->decimal('monthly_cash_flow', 15, 2)->nullable()->change();
            $table->decimal('projected_annual_cash_flow', 15, 2)->nullable()->change();
            $table->decimal('current_loan', 15, 2)->nullable()->change();
        });
    }
};
