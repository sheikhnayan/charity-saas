<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyFinancial extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        
        // Total Investment Value
        'total_investment_label',
        'total_investment_value',
        'show_total_investment',
        
        'underlying_asset_label',
        'underlying_asset_price',
        'show_underlying_asset',
        'underlying_asset_tooltip',
        'show_underlying_asset_tooltip',
        
        'closing_costs_label',
        'closing_costs',
        'show_closing_costs',
        'closing_costs_tooltip',
        'show_closing_costs_tooltip',
        
        'upfront_fees_label',
        'upfront_fees',
        'show_upfront_fees',
        'upfront_fees_tooltip',
        'show_upfront_fees_tooltip',
        
        'operating_reserve_label',
        'operating_reserve_value',
        'show_operating_reserve',
        'operating_reserve_tooltip',
        'show_operating_reserve_tooltip',
        
        // Projected Annual Return
        'projected_annual_return_label',
        'projected_annual_return',
        'show_projected_annual_return',
        
        'projected_rental_yield_label',
        'projected_rental_yield',
        'show_projected_rental_yield',
        'projected_rental_yield_tooltip',
        'show_projected_rental_yield_tooltip',
        
        'projected_appreciation_label',
        'projected_appreciation',
        'show_projected_appreciation',
        'projected_appreciation_tooltip',
        'show_projected_appreciation_tooltip',
        
        'rental_yield_label',
        'rental_yield',
        'show_rental_yield',
        'rental_yield_tooltip',
        'show_rental_yield_tooltip',
        
        // Annual Details
        'annual_gross_rents_label',
        'annual_gross_rents',
        'show_annual_gross_rents',
        
        'property_taxes_label',
        'property_taxes',
        'show_property_taxes',
        'property_taxes_tooltip',
        'show_property_taxes_tooltip',
        
        'homeowners_insurance_label',
        'homeowners_insurance',
        'show_homeowners_insurance',
        'homeowners_insurance_tooltip',
        'show_homeowners_insurance_tooltip',
        
        'property_management_label',
        'property_management',
        'show_property_management',
        'property_management_tooltip',
        'show_property_management_tooltip',
        
        'annual_llc_fees_label',
        'annual_llc_fees',
        'show_annual_llc_fees',
        'annual_llc_fees_tooltip',
        'show_annual_llc_fees_tooltip',
        
        'annual_cash_flow_label',
        'annual_cash_flow',
        'show_annual_cash_flow',
        'annual_cash_flow_tooltip',
        'show_annual_cash_flow_tooltip',
        
        'cap_rate_label',
        'cap_rate',
        'show_cap_rate',
        'cap_rate_tooltip',
        'show_cap_rate_tooltip',
        
        'monthly_cash_flow_label',
        'monthly_cash_flow',
        'show_monthly_cash_flow',
        'monthly_cash_flow_tooltip',
        'show_monthly_cash_flow_tooltip',
        
        'projected_annual_cash_flow_label',
        'projected_annual_cash_flow',
        'show_projected_annual_cash_flow',
        'projected_annual_cash_flow_tooltip',
        'show_projected_annual_cash_flow_tooltip',
        
        'current_loan_label',
        'current_loan',
        'show_current_loan',
        'current_loan_tooltip',
        'show_current_loan_tooltip',
            // Custom added items
            'custom_total_investment_items',
            'custom_projected_annual_return_items',
            'custom_annual_gross_rents_items',
    ];

    protected $casts = [
        'show_total_investment' => 'boolean',
        'show_underlying_asset' => 'boolean',
        'show_underlying_asset_tooltip' => 'boolean',
        'show_closing_costs' => 'boolean',
        'show_closing_costs_tooltip' => 'boolean',
        'show_upfront_fees' => 'boolean',
        'show_upfront_fees_tooltip' => 'boolean',
        'show_operating_reserve' => 'boolean',
        'show_operating_reserve_tooltip' => 'boolean',
        'show_projected_annual_return' => 'boolean',
        'show_projected_rental_yield' => 'boolean',
        'show_projected_rental_yield_tooltip' => 'boolean',
        'show_projected_appreciation' => 'boolean',
        'show_projected_appreciation_tooltip' => 'boolean',
        'show_rental_yield' => 'boolean',
        'show_rental_yield_tooltip' => 'boolean',
        'show_annual_gross_rents' => 'boolean',
        'show_property_taxes' => 'boolean',
        'show_property_taxes_tooltip' => 'boolean',
        'show_homeowners_insurance' => 'boolean',
        'show_homeowners_insurance_tooltip' => 'boolean',
        'show_property_management' => 'boolean',
        'show_property_management_tooltip' => 'boolean',
        'show_annual_llc_fees' => 'boolean',
        'show_annual_llc_fees_tooltip' => 'boolean',
        'show_annual_cash_flow' => 'boolean',
        'show_annual_cash_flow_tooltip' => 'boolean',
        'show_cap_rate' => 'boolean',
        'show_cap_rate_tooltip' => 'boolean',
        'show_monthly_cash_flow' => 'boolean',
        'show_monthly_cash_flow_tooltip' => 'boolean',
        'show_projected_annual_cash_flow' => 'boolean',
        'show_projected_annual_cash_flow_tooltip' => 'boolean',
        'show_current_loan' => 'boolean',
        'show_current_loan_tooltip' => 'boolean',
            'custom_total_investment_items' => 'array',
            'custom_projected_annual_return_items' => 'array',
            'custom_annual_gross_rents_items' => 'array',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
