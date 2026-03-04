<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Website;

class UpdateWebsiteTypes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'websites:update-types';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update existing websites to have default type of fundraiser';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating existing websites with default type...');
        
        $websites = Website::whereNull('type')->orWhere('type', '')->get();
        
        if ($websites->count() === 0) {
            $this->info('No websites found that need type updates.');
            return;
        }
        
        $this->info("Found {$websites->count()} websites to update.");
        
        foreach ($websites as $website) {
            $website->type = 'fundraiser';
            $website->save();
            $this->line("Updated website: {$website->name} (ID: {$website->id})");
        }
        
        $this->info('All websites have been updated successfully!');
    }
}
