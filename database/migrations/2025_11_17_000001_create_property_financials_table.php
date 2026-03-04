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
        Schema::create('property_financials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->onDelete('cascade');
            
            // Total Investment Value Section
            $table->string('total_investment_label')->nullable()->default('Total Investment Value');
            $table->decimal('total_investment_value', 15, 2)->nullable();
            $table->boolean('show_total_investment')->default(true);
            
            $table->string('underlying_asset_label')->nullable()->default('Underlying asset price');
            $table->decimal('underlying_asset_price', 15, 2)->nullable();
            $table->boolean('show_underlying_asset')->default(false);
            $table->text('underlying_asset_tooltip')->nullable();
            $table->boolean('show_underlying_asset_tooltip')->default(false);
            
            $table->string('closing_costs_label')->nullable()->default('Closing costs');
            $table->decimal('closing_costs', 15, 2)->nullable();
            $table->boolean('show_closing_costs')->default(false);
            $table->text('closing_costs_tooltip')->nullable();
            $table->boolean('show_closing_costs_tooltip')->default(false);
            
            $table->string('upfront_fees_label')->nullable()->default('Upfront DAO LLC fees');
            $table->decimal('upfront_fees', 15, 2)->nullable();
            $table->boolean('show_upfront_fees')->default(false);
            $table->text('upfront_fees_tooltip')->nullable();
            $table->boolean('show_upfront_fees_tooltip')->default(false);
            
            $table->string('operating_reserve_label')->nullable()->default('Operating reserve');
            $table->string('operating_reserve_value')->nullable();
            $table->boolean('show_operating_reserve')->default(false);
            $table->text('operating_reserve_tooltip')->nullable();
            $table->boolean('show_operating_reserve_tooltip')->default(false);
            
            // Projected Annual Return Section
            $table->string('projected_annual_return_label')->nullable()->default('Projected Annual Return');
            $table->decimal('projected_annual_return', 8, 2)->nullable();
            $table->boolean('show_projected_annual_return')->default(false);
            
            $table->string('projected_rental_yield_label')->nullable()->default('Projected Rental Yield');
            $table->decimal('projected_rental_yield', 8, 2)->nullable();
            $table->boolean('show_projected_rental_yield')->default(false);
            $table->text('projected_rental_yield_tooltip')->nullable();
            $table->boolean('show_projected_rental_yield_tooltip')->default(false);
            
            $table->string('projected_appreciation_label')->nullable()->default('Projected Appreciation');
            $table->decimal('projected_appreciation', 8, 2)->nullable();
            $table->boolean('show_projected_appreciation')->default(false);
            $table->text('projected_appreciation_tooltip')->nullable();
            $table->boolean('show_projected_appreciation_tooltip')->default(false);
            
            $table->string('rental_yield_label')->nullable()->default('Rental Yield');
            $table->decimal('rental_yield', 8, 2)->nullable();
            $table->boolean('show_rental_yield')->default(false);
            $table->text('rental_yield_tooltip')->nullable();
            $table->boolean('show_rental_yield_tooltip')->default(false);
            
            // Annual Details Section
            $table->string('annual_gross_rents_label')->nullable()->default('Annual gross rents');
            $table->decimal('annual_gross_rents', 15, 2)->nullable();
            $table->boolean('show_annual_gross_rents')->default(false);
            
            $table->string('property_taxes_label')->nullable()->default('Property taxes');
            $table->decimal('property_taxes', 15, 2)->nullable();
            $table->boolean('show_property_taxes')->default(false);
            $table->text('property_taxes_tooltip')->nullable();
            $table->boolean('show_property_taxes_tooltip')->default(false);
            
            $table->string('homeowners_insurance_label')->nullable()->default('Homeowners insurance');
            $table->decimal('homeowners_insurance', 15, 2)->nullable();
            $table->boolean('show_homeowners_insurance')->default(false);
            $table->text('homeowners_insurance_tooltip')->nullable();
            $table->boolean('show_homeowners_insurance_tooltip')->default(false);
            
            $table->string('property_management_label')->nullable()->default('Property management');
            $table->decimal('property_management', 15, 2)->nullable();
            $table->boolean('show_property_management')->default(false);
            $table->text('property_management_tooltip')->nullable();
            $table->boolean('show_property_management_tooltip')->default(false);
            
            $table->string('annual_llc_fees_label')->nullable()->default('Annual DAO LLC administration and filing fees');
            $table->decimal('annual_llc_fees', 15, 2)->nullable();
            $table->boolean('show_annual_llc_fees')->default(false);
            $table->text('annual_llc_fees_tooltip')->nullable();
            $table->boolean('show_annual_llc_fees_tooltip')->default(false);
            
            $table->string('annual_cash_flow_label')->nullable()->default('Annual cash flow');
            $table->decimal('annual_cash_flow', 15, 2)->nullable();
            $table->boolean('show_annual_cash_flow')->default(false);
            $table->text('annual_cash_flow_tooltip')->nullable();
            $table->boolean('show_annual_cash_flow_tooltip')->default(false);
            
            $table->string('cap_rate_label')->nullable()->default('Cap rate');
            $table->decimal('cap_rate', 8, 2)->nullable();
            $table->boolean('show_cap_rate')->default(false);
            $table->text('cap_rate_tooltip')->nullable();
            $table->boolean('show_cap_rate_tooltip')->default(false);
            
            $table->string('monthly_cash_flow_label')->nullable()->default('Monthly cash flow');
            $table->decimal('monthly_cash_flow', 15, 2)->nullable();
            $table->boolean('show_monthly_cash_flow')->default(false);
            $table->text('monthly_cash_flow_tooltip')->nullable();
            $table->boolean('show_monthly_cash_flow_tooltip')->default(false);
            
            $table->string('projected_annual_cash_flow_label')->nullable()->default('Projected Annual Cash Flow');
            $table->decimal('projected_annual_cash_flow', 15, 2)->nullable();
            $table->boolean('show_projected_annual_cash_flow')->default(false);
            $table->text('projected_annual_cash_flow_tooltip')->nullable();
            $table->boolean('show_projected_annual_cash_flow_tooltip')->default(false);
            
            $table->string('current_loan_label')->nullable()->default('Current loan');
            $table->decimal('current_loan', 15, 2)->nullable();
            $table->boolean('show_current_loan')->default(false);
            $table->text('current_loan_tooltip')->nullable();
            $table->boolean('show_current_loan_tooltip')->default(false);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_financials');
    }
};
