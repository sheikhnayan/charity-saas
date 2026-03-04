<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up(): void
	{
		Schema::table('tickets', function (Blueprint $table) {
			if (!Schema::hasColumn('tickets', 'price_per_share_label')) {
				$table->string('price_per_share_label')->nullable()->after('price_per_share');
			}
		});

		Schema::table('property_financials', function (Blueprint $table) {
			if (!Schema::hasColumn('property_financials', 'custom_total_investment_items')) {
				$table->json('custom_total_investment_items')->nullable()->after('show_operating_reserve_tooltip');
			}
			if (!Schema::hasColumn('property_financials', 'custom_projected_annual_return_items')) {
				$table->json('custom_projected_annual_return_items')->nullable()->after('show_rental_yield_tooltip');
			}
			if (!Schema::hasColumn('property_financials', 'custom_annual_gross_rents_items')) {
				$table->json('custom_annual_gross_rents_items')->nullable()->after('show_current_loan_tooltip');
			}
		});
	}

	public function down(): void
	{
		Schema::table('tickets', function (Blueprint $table) {
			if (Schema::hasColumn('tickets', 'price_per_share_label')) {
				$table->dropColumn('price_per_share_label');
			}
		});
		Schema::table('property_financials', function (Blueprint $table) {
			if (Schema::hasColumn('property_financials', 'custom_total_investment_items')) {
				$table->dropColumn('custom_total_investment_items');
			}
			if (Schema::hasColumn('property_financials', 'custom_projected_annual_return_items')) {
				$table->dropColumn('custom_projected_annual_return_items');
			}
			if (Schema::hasColumn('property_financials', 'custom_annual_gross_rents_items')) {
				$table->dropColumn('custom_annual_gross_rents_items');
			}
		});
	}
};