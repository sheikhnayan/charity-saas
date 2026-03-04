<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Website;

class UpdatePickpocketsWebsiteId extends Command
{
    protected $signature = 'update:pickpockets-website-id';
    protected $description = 'Update pickpockets.com website ID to match VPS (ID: 12)';

    public function handle()
    {
        $this->info('Updating pickpockets.com website ID to match VPS...');
        
        // Find current pickpockets.com website
        $website = Website::where('domain', 'pickpockets.com')->first();
        
        if (!$website) {
            $this->error('pickpockets.com not found in websites table');
            return 1;
        }
        
        $oldId = $website->id;
        $this->info("Current pickpockets.com ID: $oldId");
        
        // Check if ID 12 already exists
        $existingId12 = Website::find(12);
        if ($existingId12) {
            $this->warn("Website ID 12 already exists: {$existingId12->domain}");
            $this->info("Deleting the existing ID 12 website...");
            $existingId12->delete();
        }
        
        // Update the ID to 12 using raw SQL (since Laravel doesn't allow direct ID updates)
        \DB::statement('UPDATE websites SET id = 12 WHERE id = ?', [$oldId]);
        
        // Verify the update
        $updatedWebsite = Website::find(12);
        if ($updatedWebsite && $updatedWebsite->domain === 'pickpockets.com') {
            $this->info("✅ Successfully updated pickpockets.com to ID: 12");
            $this->info("Domain: {$updatedWebsite->domain}");
        } else {
            $this->error("❌ Failed to update website ID");
        }
        
        return 0;
    }
}