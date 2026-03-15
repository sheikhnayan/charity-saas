<?php

namespace App\Console\Commands;

use App\Models\Website;
use App\Services\HeaderFooterBuilderService;
use Illuminate\Console\Command;

class BackfillHeaderFooterBuilderStates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'builders:backfill-header-footer
                            {--website_id= : Backfill only one website by ID}
                            {--dry-run : Preview how many websites would be processed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backfill header/footer builder states for existing websites';

    /**
     * Execute the console command.
     */
    public function handle(HeaderFooterBuilderService $builderService)
    {
        $websiteId = $this->option('website_id');
        $dryRun = (bool) $this->option('dry-run');

        $query = Website::query();

        if (!empty($websiteId)) {
            $query->where('id', $websiteId);
        }

        $websites = $query->get();

        if ($websites->isEmpty()) {
            $this->warn('No websites found to process.');
            return self::SUCCESS;
        }

        $this->info('Found ' . $websites->count() . ' website(s) to process.');

        if ($dryRun) {
            $this->line('Dry run enabled. No records were modified.');
            return self::SUCCESS;
        }

        $bar = $this->output->createProgressBar($websites->count());
        $bar->start();

        foreach ($websites as $website) {
            $builderService->seedDefaultsForWebsite($website);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info('Backfill complete. Header/Footer builder states are now seeded.');

        return self::SUCCESS;
    }
}
