<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Website;
use App\Models\TicketCategory;
use App\Models\Ticket;

class TicketCategorySeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create "General" category for each website
        $websites = Website::all();
        
        foreach ($websites as $website) {
            // create or get existing General category for this website
            $generalCategory = TicketCategory::firstOrCreate(
                [
                    'website_id' => $website->id,
                    'name' => 'General'
                ],
                [
                    'slug' => 'general',
                    'description' => 'General ticket category',
                    'is_active' => true,
                    'sort_order' => 0
                ]
            );

            // Assign all existing tickets for this website to the General category if they don't have one
            $updated = Ticket::where('website_id', $website->id)
                  ->whereNull('category_id')
                  ->update(['category_id' => $generalCategory->id]);

            $this->command->info("Ensured General category for website: {$website->name} (ID: {$website->id}). Tickets updated: {$updated}");
        }
        
        $this->command->info('Ticket categories seeded successfully!');
    }
}
