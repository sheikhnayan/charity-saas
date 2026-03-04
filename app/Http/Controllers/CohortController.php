<?php

namespace App\Http\Controllers;

use App\Models\Cohort;
use App\Services\CohortService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CohortController extends Controller
{
    protected $cohortService;

    public function __construct(CohortService $cohortService)
    {
        $this->cohortService = $cohortService;
    }

    /**
     * Display cohort analysis dashboard
     */
    public function index(Request $request)
    {
        $websiteId = $request->website_id ?? Auth::user()->website_id ?? null;
        
        $cohorts = Cohort::when($websiteId, function($query) use ($websiteId) {
                return $query->where('website_id', $websiteId);
            })
            ->with('members')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        $stats = $this->cohortService->getCohortStats($websiteId);
        
        // Get all websites for filter dropdown
        $websites = \App\Models\Website::all();
        $selectedWebsiteId = $websiteId;

        // Chart Data: Cohort growth over time (last 6 months)
        $cohortGrowth = Cohort::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->when($websiteId, function($query) use ($websiteId) {
                return $query->where('website_id', $websiteId);
            })
            ->whereBetween('created_at', [\Carbon\Carbon::now()->subMonths(5)->startOfMonth(), \Carbon\Carbon::now()])
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Chart Data: LTV by cohort type
        $ltvByCohortType = Cohort::select('type', \DB::raw('AVG(cm.lifetime_value) as avg_ltv'))
            ->join('cohort_members as cm', 'cohorts.id', '=', 'cm.cohort_id')
            ->when($websiteId, function($query) use ($websiteId) {
                return $query->where('cohorts.website_id', $websiteId);
            })
            ->groupBy('type')
            ->get()
            ->keyBy('type');

        // Chart Data: LTV trends over 6 months for different cohort types
        $ltvTrends = [
            'labels' => [],
            'high_value' => [],
            'repeat' => [],
            'first_time' => []
        ];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = \Carbon\Carbon::now()->subMonths($i);
            $ltvTrends['labels'][] = $month->format('M Y');
            
            // Get average LTV for each type in this month
            foreach (['high_value', 'repeat', 'first_time'] as $type) {
                $avgLtv = \DB::table('cohort_members as cm')
                    ->join('cohorts as c', 'cm.cohort_id', '=', 'c.id')
                    ->where('c.type', $type)
                    ->when($websiteId, function($query) use ($websiteId) {
                        return $query->where('c.website_id', $websiteId);
                    })
                    ->whereMonth('cm.joined_at', '<=', $month->month)
                    ->whereYear('cm.joined_at', '<=', $month->year)
                    ->avg('cm.lifetime_value') ?? 0;
                
                $ltvTrends[$type][] = round($avgLtv, 2);
            }
        }

        // Chart Data: Retention curve (30 days)
        $retentionData = [];
        for ($day = 0; $day <= 30; $day += 5) {
            $avgRetention = 0;
            $cohortCount = 0;
            foreach ($cohorts->take(5) as $cohort) { // Top 5 cohorts
                $retention = $this->cohortService->calculateRetentionRate($cohort->id, $day);
                if ($retention > 0) {
                    $avgRetention += $retention;
                    $cohortCount++;
                }
            }
            $retentionData[] = $cohortCount > 0 ? round($avgRetention / $cohortCount, 1) : 0;
        }

        $chartData = [
            'growth' => [
                'labels' => $cohortGrowth->pluck('month')->map(fn($m) => \Carbon\Carbon::parse($m)->format('M Y'))->toArray(),
                'counts' => $cohortGrowth->pluck('count')->toArray(),
            ],
            'ltv_by_type' => [
                'first_time' => round($ltvByCohortType->get('first_time')->avg_ltv ?? 0, 2),
                'repeat' => round($ltvByCohortType->get('repeat')->avg_ltv ?? 0, 2),
                'high_value' => round($ltvByCohortType->get('high_value')->avg_ltv ?? 0, 2),
                'lapsed' => round($ltvByCohortType->get('lapsed')->avg_ltv ?? 0, 2),
            ],
            'retention_curve' => $retentionData,
            'ltv_trends' => $ltvTrends
        ];

        return view('cohorts.index', compact('cohorts', 'stats', 'websites', 'selectedWebsiteId', 'chartData'));
    }

    /**
     * Create a new cohort
     */
    public function create(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:first_time,repeat,high_value,lapsed,by_date,custom',
            'description' => 'nullable|string',
            'definition' => 'required|array',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date'
        ]);

        $websiteId = Auth::user()->website_id ?? 1;
        
        $cohort = $this->cohortService->createCohort($websiteId, $validated);

        return response()->json([
            'message' => 'Cohort created successfully',
            'cohort' => $cohort
        ], 201);
    }

    /**
     * Get cohort details with retention data
     */
    public function show($id)
    {
        $cohort = Cohort::with(['members.user', 'retention'])
            ->findOrFail($id);

        return response()->json($cohort);
    }

    /**
     * Update cohort
     */
    public function update(Request $request, $id)
    {
        $cohort = Cohort::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'sometimes|boolean'
        ]);

        $cohort->update($validated);

        return response()->json([
            'message' => 'Cohort updated successfully',
            'cohort' => $cohort
        ]);
    }

    /**
     * Delete cohort
     */
    public function destroy($id)
    {
        $cohort = Cohort::findOrFail($id);
        $cohort->delete();

        return response()->json([
            'message' => 'Cohort deleted successfully'
        ]);
    }

    /**
     * Refresh cohort members
     */
    public function refresh($id)
    {
        $cohort = Cohort::findOrFail($id);
        $this->cohortService->populateCohort($cohort);

        return response()->json([
            'message' => 'Cohort refreshed successfully',
            'cohort' => $cohort->fresh()
        ]);
    }

    /**
     * Calculate retention metrics
     */
    public function calculateRetention($id, Request $request)
    {
        $cohort = Cohort::findOrFail($id);
        
        $periods = $request->input('periods', [0, 1, 7, 14, 30, 60, 90]);
        
        $this->cohortService->calculateRetention($cohort, $periods);

        return response()->json([
            'message' => 'Retention calculated successfully',
            'retention' => $cohort->retention
        ]);
    }

    /**
     * Compare multiple cohorts
     */
    public function compare(Request $request)
    {
        $validated = $request->validate([
            'cohort_ids' => 'required|array|min:2',
            'cohort_ids.*' => 'exists:cohorts,id'
        ]);

        $websiteId = Auth::user()->website_id ?? 1;
        
        $comparison = $this->cohortService->compareCohorts(
            $validated['cohort_ids'],
            $websiteId
        );

        return response()->json([
            'comparison' => $comparison
        ]);
    }

    /**
     * Get retention chart data
     */
    public function retentionChart($id)
    {
        $cohort = Cohort::with('retention')->findOrFail($id);

        $chartData = $cohort->retention->map(function($retention) {
            return [
                'period' => $retention->period,
                'retention_rate' => $retention->retention_rate,
                'retained_users' => $retention->retained_users,
                'revenue' => $retention->revenue
            ];
        });

        return response()->json($chartData);
    }

    /**
     * Get cohort members
     */
    public function members($id)
    {
        $cohort = Cohort::findOrFail($id);
        
        $members = $cohort->members()
            ->with('user')
            ->paginate(50);

        return response()->json($members);
    }

    /**
     * Export cohort data
     */
    public function export($id, Request $request)
    {
        $cohort = Cohort::with(['members.user'])->findOrFail($id);
        
        $format = $request->input('format', 'csv');
        
        // Prepare data
        $data = $cohort->members->map(function($member) {
            return [
                'User ID' => $member->user_id,
                'Name' => $member->user->name,
                'Email' => $member->user->email,
                'Joined Cohort' => $member->joined_at->format('Y-m-d'),
                'Lifetime Value' => $member->lifetime_value,
                'Transaction Count' => $member->transaction_count,
                'Last Activity' => $member->last_activity_at ? $member->last_activity_at->format('Y-m-d') : 'N/A'
            ];
        });

        if ($format === 'csv') {
            return $this->exportCSV($data, "cohort_{$cohort->id}_members.csv");
        }

        return response()->json($data);
    }

    private function exportCSV($data, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            if ($data->isNotEmpty()) {
                fputcsv($file, array_keys($data->first()));
            }
            
            // Add data
            foreach ($data as $row) {
                fputcsv($file, $row);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get retention heatmap data for dashboard
     */
    public function getRetentionHeatmap(Request $request)
    {
        $websiteId = $request->website_id ?? Auth::user()->website_id ?? null;
        
        $cohorts = Cohort::where('website_id', $websiteId)
            ->where('is_active', true)
            ->with('members')
            ->limit(10)
            ->get()
            ->map(function($cohort) {
                // Calculate retention rates for different time periods
                $totalMembers = $cohort->members->count();
                
                if ($totalMembers === 0) {
                    return null;
                }
                
                return [
                    'name' => $cohort->name,
                    'size' => $totalMembers,
                    'day_1' => $this->cohortService->calculateRetentionRate($cohort->id, 1),
                    'day_7' => $this->cohortService->calculateRetentionRate($cohort->id, 7),
                    'day_14' => $this->cohortService->calculateRetentionRate($cohort->id, 14),
                    'day_30' => $this->cohortService->calculateRetentionRate($cohort->id, 30),
                    'day_60' => $this->cohortService->calculateRetentionRate($cohort->id, 60),
                    'day_90' => $this->cohortService->calculateRetentionRate($cohort->id, 90),
                ];
            })
            ->filter();

        return response()->json($cohorts->values());
    }
}
