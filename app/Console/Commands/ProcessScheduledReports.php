<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ProcessScheduledReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process and send scheduled reports';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Processing scheduled reports...');
        
        $reportService = app(\App\Services\ReportSchedulerService::class);
        
        try {
            $count = $reportService->processDueReports();
            
            $this->info("Successfully processed {$count} report(s)");
            
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Error processing reports: ' . $e->getMessage());
            
            return Command::FAILURE;
        }
    }
}
