<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Website;

class AddPickpocketsWebsite extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:pickpockets-website';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add pickpockets.com website to the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Check if website already exists
        $existing = Website::where('domain', 'pickpockets.com')->first();
        
        if ($existing) {
            $this->info("Website pickpockets.com already exists with ID: {$existing->id}");
            return 0;
        }
        
        // Create new website
        $website = new Website();
        $website->domain = 'pickpockets.com';
        $website->name = 'Pickpockets Charity';
        $website->user_id = 1; // Adjust this to the correct user ID
        $website->save();
        
        $this->info("✅ Website pickpockets.com created successfully with ID: {$website->id}");
        
        // Show all websites
        $this->info('All websites:');
        $websites = Website::all();
        foreach($websites as $site) {
            $this->line("  - ID: {$site->id}, Domain: {$site->domain}, Name: {$site->name}");
        }
        
        return 0;
    }
}
