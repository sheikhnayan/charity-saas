<?php

namespace App\Http\Controllers;

use App\Models\ScheduledReport;
use App\Models\ReportExecution;
use App\Services\ReportSchedulerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportSchedulerService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * List all scheduled reports
     */
    public function index()
    {
        $websiteId = Auth::user()->website_id ?? 1;
        
        $reports = ScheduledReport::where('website_id', $websiteId)
            ->with('executions')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json($reports);
    }

    /**
     * Create scheduled report
     */
    public function create(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'report_type' => 'required|in:analytics,donations,conversions,cohort,fraud,ab_test',
            'configuration' => 'required|array',
            'frequency' => 'required|in:daily,weekly,monthly,quarterly',
            'format' => 'required|in:pdf,csv,excel,json',
            'recipients' => 'required|array',
            'recipients.*' => 'email'
        ]);

        $websiteId = Auth::user()->website_id ?? 1;
        
        $report = ScheduledReport::create([
            'website_id' => $websiteId,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'report_type' => $validated['report_type'],
            'configuration' => $validated['configuration'],
            'frequency' => $validated['frequency'],
            'format' => $validated['format'],
            'recipients' => $validated['recipients'],
            'is_active' => true,
            'next_run_at' => $this->calculateNextRunTime($validated['frequency'])
        ]);

        return response()->json([
            'message' => 'Scheduled report created successfully',
            'report' => $report
        ], 201);
    }

    /**
     * Get report details
     */
    public function show($id)
    {
        $report = ScheduledReport::with('executions')->findOrFail($id);
        return response()->json($report);
    }

    /**
     * Update report
     */
    public function update(Request $request, $id)
    {
        $report = ScheduledReport::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'configuration' => 'sometimes|array',
            'frequency' => 'sometimes|in:daily,weekly,monthly,quarterly',
            'format' => 'sometimes|in:pdf,csv,excel,json',
            'recipients' => 'sometimes|array',
            'recipients.*' => 'email',
            'is_active' => 'sometimes|boolean'
        ]);

        $report->update($validated);

        return response()->json([
            'message' => 'Report updated successfully',
            'report' => $report
        ]);
    }

    /**
     * Delete report
     */
    public function destroy($id)
    {
        $report = ScheduledReport::findOrFail($id);
        $report->delete();

        return response()->json([
            'message' => 'Report deleted successfully'
        ]);
    }

    /**
     * Generate report now (manual trigger)
     */
    public function generate($id)
    {
        $report = ScheduledReport::findOrFail($id);
        
        try {
            $execution = $this->reportService->generateReport($report);

            return response()->json([
                'message' => 'Report generated successfully',
                'execution' => $execution
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to generate report: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download report file
     */
    public function download($executionId)
    {
        $execution = ReportExecution::findOrFail($executionId);

        if (!$execution->file_path || !Storage::exists($execution->file_path)) {
            return response()->json([
                'error' => 'Report file not found'
            ], 404);
        }

        return Storage::download($execution->file_path);
    }

    /**
     * Get execution history
     */
    public function executions($id)
    {
        $report = ScheduledReport::findOrFail($id);
        
        $executions = $report->executions()
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return response()->json($executions);
    }

    private function calculateNextRunTime($frequency)
    {
        $now = \Carbon\Carbon::now();

        switch ($frequency) {
            case 'daily':
                return $now->addDay()->startOfDay()->addHours(8);
            case 'weekly':
                return $now->addWeek()->startOfWeek()->addHours(8);
            case 'monthly':
                return $now->addMonth()->startOfMonth()->addHours(8);
            case 'quarterly':
                return $now->addMonths(3)->startOfMonth()->addHours(8);
            default:
                return $now->addDay()->startOfDay()->addHours(8);
        }
    }
}
