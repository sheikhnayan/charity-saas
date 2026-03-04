<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Investment;
use App\Models\Website;

class TestInvestmentSave extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:investment-save';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test investment data saving functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing investment data saving...');

        // Get a website to test with
        $website = Website::first();
        if (!$website) {
            $this->error('No website found in database');
            return;
        }

        // Test data
        $testData = [
            'website_id' => $website->id,
            'investor_name' => 'Test User',
            'investor_email' => 'test@example.com',
            'investor_phone' => '1234567890',
            'investment_amount' => 1000.00,
            'investor_type' => 'individual',
            'share_quantity' => 100,
            'investor_data' => [
                'address' => '123 Test St',
                'city' => 'Test City',
                'state' => 'Test State',
                'zip' => '12345',
                'country' => 'United States',
                'accredited_investor' => 'yes'
            ]
        ];

        try {
            $investment = Investment::create($testData);
            $this->info('✅ Investment created successfully!');
            $this->info('ID: ' . $investment->id);
            $this->info('Investor Type: ' . $investment->investor_type);
            $this->info('Investor Data: ' . json_encode($investment->investor_data, JSON_PRETTY_PRINT));
            
            // Clean up
            $investment->delete();
            $this->info('Test investment deleted');
            
        } catch (\Exception $e) {
            $this->error('❌ Error creating investment: ' . $e->getMessage());
            $this->error($e->getTraceAsString());
        }
    }
}
