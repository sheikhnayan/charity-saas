<?php

namespace App\Services;

use App\Models\ScheduledReport;
use App\Models\ReportExecution;
use App\Models\Donation;
use App\Models\Transaction;
use App\Models\Cohort;
use App\Models\ABTest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ReportSchedulerService
{
    /**
     * Generate and send due reports
     */
    public function processDueReports()
    {
        $dueReports = ScheduledReport::where('is_active', true)
            ->where('next_run_at', '<=', Carbon::now())
            ->get();

        foreach ($dueReports as $report) {
            $this->generateReport($report);
        }

        return $dueReports->count();
    }

    /**
     * Generate a specific report
     */
    public function generateReport(ScheduledReport $report)
    {
        $execution = ReportExecution::create([
            'scheduled_report_id' => $report->id,
            'status' => 'processing',
            'started_at' => Carbon::now()
        ]);

        try {
            // Generate report based on type
            $data = $this->generateReportData($report);
            
            // Export to format
            $filePath = $this->exportReport($report, $data);
            $fileSize = Storage::size($filePath);

            // Update execution
            $execution->update([
                'status' => 'completed',
                'completed_at' => Carbon::now(),
                'file_path' => $filePath,
                'file_size' => $fileSize,
                'execution_data' => [
                    'row_count' => count($data),
                    'generated_at' => Carbon::now()->toDateTimeString()
                ]
            ]);

            // Send email
            $this->sendReportEmail($report, $execution);

            // Update next run time
            $report->update([
                'last_run_at' => Carbon::now(),
                'next_run_at' => $this->calculateNextRunTime($report->frequency)
            ]);

            return $execution;

        } catch (\Exception $e) {
            $execution->update([
                'status' => 'failed',
                'completed_at' => Carbon::now(),
                'error_message' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Generate report data based on type
     */
    private function generateReportData(ScheduledReport $report)
    {
        $config = $report->configuration;
        $dateRange = $this->getDateRange($config['date_range'] ?? 'last_30_days');

        switch ($report->report_type) {
            case 'analytics':
                return $this->generateAnalyticsReport($report->website_id, $dateRange, $config);
            
            case 'donations':
                return $this->generateDonationsReport($report->website_id, $dateRange, $config);
            
            case 'conversions':
                return $this->generateConversionsReport($report->website_id, $dateRange, $config);
            
            case 'cohort':
                return $this->generateCohortReport($report->website_id, $config);
            
            case 'fraud':
                return $this->generateFraudReport($report->website_id, $dateRange, $config);
            
            case 'ab_test':
                return $this->generateABTestReport($report->website_id, $config);
            
            default:
                throw new \Exception("Unknown report type: {$report->report_type}");
        }
    }

    /**
     * Generate analytics report
     */
    private function generateAnalyticsReport($websiteId, $dateRange, $config)
    {
        return \DB::table('analytics_events')
            ->where('website_id', $websiteId)
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->select([
                'event_type',
                'page_url',
                'utm_source',
                'utm_medium',
                'utm_campaign',
                'device_type',
                'browser',
                'country',
                'created_at'
            ])
            ->get()
            ->toArray();
    }

    /**
     * Generate donations report
     */
    private function generateDonationsReport($websiteId, $dateRange, $config)
    {
        return Donation::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->with('user')
            ->select([
                'id',
                'user_id',
                'amount',
                'payment_method',
                'status',
                'created_at'
            ])
            ->get()
            ->map(function($donation) {
                return [
                    'Donation ID' => $donation->id,
                    'Donor Name' => $donation->user->name ?? 'Anonymous',
                    'Donor Email' => $donation->user->email ?? 'N/A',
                    'Amount' => $donation->amount,
                    'Payment Method' => $donation->payment_method,
                    'Status' => $donation->status,
                    'Date' => $donation->created_at->format('Y-m-d H:i:s')
                ];
            })
            ->toArray();
    }

    /**
     * Generate conversions report
     */
    private function generateConversionsReport($websiteId, $dateRange, $config)
    {
        $conversionEvents = \DB::table('analytics_events')
            ->where('website_id', $websiteId)
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->whereIn('event_type', ['donation_completed', 'signup_completed'])
            ->select([
                'event_type',
                'conversion_value',
                'utm_source',
                'utm_campaign',
                'created_at'
            ])
            ->get();

        return $conversionEvents->toArray();
    }

    /**
     * Generate cohort report
     */
    private function generateCohortReport($websiteId, $config)
    {
        $cohortId = $config['cohort_id'] ?? null;
        
        $query = Cohort::where('website_id', $websiteId)
            ->with(['members.user', 'retention']);

        if ($cohortId) {
            $query->where('id', $cohortId);
        }

        return $query->get()->toArray();
    }

    /**
     * Generate fraud report
     */
    private function generateFraudReport($websiteId, $dateRange, $config)
    {
        return \DB::table('fraud_detections')
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->get()
            ->toArray();
    }

    /**
     * Generate A/B test report
     */
    private function generateABTestReport($websiteId, $config)
    {
        $testId = $config['test_id'] ?? null;
        
        $query = ABTest::where('website_id', $websiteId)
            ->with(['testVariants', 'results']);

        if ($testId) {
            $query->where('id', $testId);
        }

        return $query->get()->toArray();
    }

    /**
     * Export report to file
     */
    private function exportReport(ScheduledReport $report, $data)
    {
        $filename = "reports/{$report->report_type}_{$report->id}_" . Carbon::now()->format('Y-m-d_His');

        switch ($report->format) {
            case 'csv':
                return $this->exportCSV($data, $filename . '.csv');
            
            case 'json':
                return $this->exportJSON($data, $filename . '.json');
            
            case 'pdf':
                return $this->exportPDF($data, $filename . '.pdf', $report);
            
            default:
                return $this->exportCSV($data, $filename . '.csv');
        }
    }

    /**
     * Export to CSV
     */
    private function exportCSV($data, $filename)
    {
        if (empty($data)) {
            $csv = "No data available\n";
        } else {
            $output = fopen('php://temp', 'r+');
            
            // Headers
            fputcsv($output, array_keys((array)$data[0]));
            
            // Data
            foreach ($data as $row) {
                fputcsv($output, (array)$row);
            }
            
            rewind($output);
            $csv = stream_get_contents($output);
            fclose($output);
        }

        Storage::put($filename, $csv);
        return $filename;
    }

    /**
     * Export to JSON
     */
    private function exportJSON($data, $filename)
    {
        $json = json_encode($data, JSON_PRETTY_PRINT);
        Storage::put($filename, $json);
        return $filename;
    }

    /**
     * Export to PDF (placeholder - requires PDF library)
     */
    private function exportPDF($data, $filename, $report)
    {
        // Placeholder - would use a PDF library like DomPDF or TCPDF
        $html = "<h1>{$report->name}</h1>";
        $html .= "<p>Generated: " . Carbon::now()->format('Y-m-d H:i:s') . "</p>";
        $html .= "<p>Data rows: " . count($data) . "</p>";
        
        Storage::put($filename, $html);
        return $filename;
    }

    /**
     * Send report via email
     */
    private function sendReportEmail(ScheduledReport $report, ReportExecution $execution)
    {
        if (empty($report->recipients)) {
            return;
        }

        try {
            // Apply website-specific email settings if available
            if ($report->website_id) {
                \App\Services\WebsiteMailService::applyForWebsite($report->website);
            }

            // Filter out admin@admin from recipients
            $recipients = is_array($report->recipients) 
                ? array_filter($report->recipients, fn($email) => $email !== 'admin@admin')
                : (($report->recipients !== 'admin@admin') ? $report->recipients : null);

            // Only send if there are valid recipients remaining
            if (!empty($recipients)) {
                Mail::send('emails.scheduled_report', [
                    'report' => $report,
                    'execution' => $execution
                ], function($message) use ($recipients, $report, $execution) {
                    $message->to($recipients)
                        ->subject("Scheduled Report: {$report->name}")
                        ->attach(Storage::path($execution->file_path));
                });

                $execution->update(['email_sent' => true]);
            }
        } catch (\Exception $e) {
            // Log email error but don't fail the report generation
            \Log::error("Failed to send report email: " . $e->getMessage());
        }
    }

    /**
     * Calculate next run time based on frequency
     */
    private function calculateNextRunTime($frequency)
    {
        $now = Carbon::now();

        switch ($frequency) {
            case 'daily':
                return $now->addDay()->startOfDay()->addHours(8); // 8 AM next day
            
            case 'weekly':
                return $now->addWeek()->startOfWeek()->addHours(8); // Monday 8 AM
            
            case 'monthly':
                return $now->addMonth()->startOfMonth()->addHours(8); // 1st of month 8 AM
            
            case 'quarterly':
                return $now->addMonths(3)->startOfMonth()->addHours(8);
            
            default:
                return $now->addDay()->startOfDay()->addHours(8);
        }
    }

    /**
     * Get date range from string
     */
    private function getDateRange($range)
    {
        $now = Carbon::now();

        switch ($range) {
            case 'today':
                return ['start' => $now->startOfDay(), 'end' => $now->endOfDay()];
            
            case 'yesterday':
                return ['start' => $now->subDay()->startOfDay(), 'end' => $now->endOfDay()];
            
            case 'last_7_days':
                return ['start' => $now->subDays(7)->startOfDay(), 'end' => Carbon::now()->endOfDay()];
            
            case 'last_30_days':
                return ['start' => $now->subDays(30)->startOfDay(), 'end' => Carbon::now()->endOfDay()];
            
            case 'this_month':
                return ['start' => $now->startOfMonth(), 'end' => Carbon::now()->endOfMonth()];
            
            case 'last_month':
                $lastMonth = $now->subMonth();
                return ['start' => $lastMonth->startOfMonth(), 'end' => $lastMonth->endOfMonth()];
            
            default:
                return ['start' => $now->subDays(30)->startOfDay(), 'end' => Carbon::now()->endOfDay()];
        }
    }
}
