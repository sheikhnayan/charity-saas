<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Website;
use App\Models\WebsitePaymentSetting;
use App\Models\Setting;

class FixPaymentMethodSettings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:fix-method-settings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix payment method settings - ensure correct payment method is returned';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Analyzing payment method settings...\n');

        $websites = Website::all();
        
        foreach ($websites as $website) {
            $this->line("📋 Website: {$website->name} (Domain: {$website->domain})");
            
            // Check WebsitePaymentSetting
            $websitePaymentSetting = $website->paymentSettings;
            $setting = $website->setting;
            
            if ($websitePaymentSetting) {
                $this->line("   ✅ WebsitePaymentSetting exists");
                $this->line("      - ID: {$websitePaymentSetting->id}");
                $this->line("      - Payment Method: {$websitePaymentSetting->payment_method}");
                $this->line("      - Is Active: " . ($websitePaymentSetting->is_active ? 'YES' : 'NO ❌'));
                $this->line("      - Auth Login: " . ($websitePaymentSetting->authorize_login_id ? 'SET ✅' : 'NOT SET ❌'));
                $this->line("      - Auth Key: " . ($websitePaymentSetting->authorize_transaction_key ? 'SET ✅' : 'NOT SET ❌'));
                
                if (!$websitePaymentSetting->is_active) {
                    $this->warn("   ⚠️  WebsitePaymentSetting is NOT active!");
                    
                    // Ask if we should activate it
                    if ($this->confirm("   Should we activate this setting?")) {
                        $websitePaymentSetting->update(['is_active' => true]);
                        $this->info("   ✅ Activated!");
                    }
                }
            } else {
                $this->warn("   ❌ No WebsitePaymentSetting found");
            }
            
            // Check fallback Setting
            if ($setting) {
                $this->line("   ✅ Fallback Setting exists");
                $this->line("      - Payment Method: {$setting->payment_method}");
                $this->line("      - Auth Login: " . ($setting->authorize_login_id ? 'SET ✅' : 'NOT SET ❌'));
                $this->line("      - Auth Key: " . ($setting->authorize_transaction_key ? 'SET ✅' : 'NOT SET ❌'));
            } else {
                $this->warn("   ❌ No fallback Setting found");
            }
            
            // Show what getPaymentMethod returns
            $method = $website->getPaymentMethod();
            $this->line("   📍 getPaymentMethod() returns: {$method}");
            
            $this->line("");
        }
        
        $this->info("✅ Analysis complete!");
    }
}
