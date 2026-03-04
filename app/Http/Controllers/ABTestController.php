<?php

namespace App\Http\Controllers;

use App\Models\ABTest;
use App\Services\ABTestingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ABTestController extends Controller
{
    protected $abTestService;

    public function __construct(ABTestingService $abTestService)
    {
        $this->abTestService = $abTestService;
    }

    /**
     * Display A/B testing dashboard
     */
    public function index(Request $request)
    {
        $websiteId = $request->website_id ?? Auth::user()->website_id ?? null;
        
        $tests = ABTest::when($websiteId, function($query) use ($websiteId) {
                return $query->where('website_id', $websiteId);
            })
            ->with(['testVariants', 'results', 'winningVariant'])
            ->withCount(['assignments as participants_count', 'testVariants as variants_count'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // Add winner variant name and latest confidence to each test
        $tests->each(function($test) {
            if ($test->winningVariant) {
                $test->winner_variant = $test->winningVariant->name;
            }
            // Get latest result for confidence level
            $latestResult = $test->results->sortByDesc('calculated_at')->first();
            if ($latestResult) {
                $test->confidence_level = $latestResult->confidence_level;
            }
        });
        
        $stats = $this->abTestService->getTestStats($websiteId);
        
        // Get all websites for filter dropdown
        $websites = \App\Models\Website::all();
        $selectedWebsiteId = $websiteId;

        // Chart Data: Conversion trends over last 7 days
        $conversionTrend = \DB::table('ab_test_conversions as conv')
            ->join('ab_tests as test', 'conv.test_id', '=', 'test.id')
            ->selectRaw('DATE(conv.created_at) as date, COUNT(*) as conversions')
            ->when($websiteId, function($query) use ($websiteId) {
                return $query->where('test.website_id', $websiteId);
            })
            ->whereBetween('conv.created_at', [\Carbon\Carbon::now()->subDays(6)->startOfDay(), \Carbon\Carbon::now()->endOfDay()])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Chart Data: Variant performance comparison
        $variantPerformance = \DB::table('ab_test_variants as var')
            ->join('ab_tests as test', 'var.test_id', '=', 'test.id')
            ->leftJoin('ab_test_conversions as conv', 'var.id', '=', 'conv.variant_id')
            ->leftJoin('ab_test_results as res', function($join) {
                $join->on('var.id', '=', 'res.variant_id')
                     ->on('var.test_id', '=', 'res.test_id');
            })
            ->selectRaw('var.name, COUNT(DISTINCT conv.id) as conversions, COALESCE(res.impressions, 0) as views')
            ->when($websiteId, function($query) use ($websiteId) {
                return $query->where('test.website_id', $websiteId);
            })
            ->where('test.status', 'running')
            ->groupBy('var.id', 'var.name', 'res.impressions')
            ->get();

        $chartData = [
            'trend' => [
                'labels' => $conversionTrend->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('D'))->toArray(),
                'conversions' => $conversionTrend->pluck('conversions')->toArray(),
            ],
            'variants' => [
                'labels' => $variantPerformance->pluck('name')->toArray(),
                'conversions' => $variantPerformance->pluck('conversions')->toArray(),
                'views' => $variantPerformance->pluck('views')->toArray(),
            ]
        ];

        return view('abtests.index', compact('tests', 'stats', 'websites', 'selectedWebsiteId', 'chartData'));
    }

    /**
     * Create a new A/B test
     */
    public function create(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'test_type' => 'required|string',
            'variants' => 'required|array|min:2',
            'variants.*.name' => 'required|string',
            'variants.*.configuration' => 'nullable|array',
            'variants.*.is_control' => 'nullable|boolean',
            'variants.*.traffic_percentage' => 'required|integer|min:0|max:100',
            'traffic_split' => 'required|array',
            'goal_metric' => 'required|string',
            'goal_value' => 'nullable|numeric',
            'min_sample_size' => 'nullable|integer|min:10',
            'confidence_level' => 'nullable|numeric|min:80|max:99.99'
        ]);

        // Ensure each variant has a configuration array (default to empty if not provided)
        if (isset($validated['variants'])) {
            foreach ($validated['variants'] as $key => $variant) {
                if (!isset($variant['configuration']) || !is_array($variant['configuration'])) {
                    $validated['variants'][$key]['configuration'] = [];
                }
            }
        }

        $websiteId = Auth::user()->website_id ?? 1;
        
        $test = $this->abTestService->createTest($websiteId, $validated);

        return response()->json([
            'message' => 'A/B test created successfully',
            'test' => $test
        ], 201);
    }

    /**
     * Get test details
     */
    public function show($id)
    {
        $test = ABTest::with(['testVariants', 'results', 'assignments', 'conversions'])
            ->findOrFail($id);

        return response()->json($test);
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $test = ABTest::with(['testVariants', 'website'])->findOrFail($id);
        $websites = \App\Models\Website::all();
        
        return view('abtests.edit', compact('test', 'websites'));
    }

    /**
     * Update test
     */
    public function update(Request $request, $id)
    {
        $test = ABTest::findOrFail($id);

        if ($test->status === 'running') {
            return response()->json([
                'error' => 'Cannot update a running test'
            ], 400);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'goal_metric' => 'sometimes|string',
            'goal_value' => 'nullable|numeric'
        ]);

        $test->update($validated);

        return response()->json([
            'message' => 'Test updated successfully',
            'test' => $test
        ]);
    }

    /**
     * Start test
     */
    public function start($id)
    {
        $test = $this->abTestService->startTest($id);

        return response()->json([
            'message' => 'Test started successfully',
            'test' => $test
        ]);
    }

    /**
     * Pause test
     */
    public function pause($id)
    {
        $test = ABTest::findOrFail($id);
        $test->update(['status' => 'paused']);

        return response()->json([
            'message' => 'Test paused successfully',
            'test' => $test
        ]);
    }

    /**
     * End test
     */
    public function end($id)
    {
        $test = $this->abTestService->endTest($id);

        return response()->json([
            'message' => 'Test ended successfully',
            'test' => $test
        ]);
    }

    /**
     * Delete test
     */
    public function destroy($id)
    {
        $test = ABTest::findOrFail($id);

        if ($test->status === 'running') {
            return response()->json([
                'error' => 'Cannot delete a running test. Please pause or end it first.'
            ], 400);
        }

        $test->delete();

        return response()->json([
            'message' => 'Test deleted successfully'
        ]);
    }

    /**
     * Assign variant to user (API endpoint for frontend)
     */
    public function assignVariant(Request $request, $id)
    {
        $validated = $request->validate([
            'user_identifier' => 'required|string',
            'identifier_type' => 'nullable|in:user,session,cookie',
            'user_id' => 'nullable|exists:users,id',
            'session_id' => 'nullable|string'
        ]);

        $variant = $this->abTestService->assignVariant(
            $id,
            $validated['user_identifier'],
            $validated['identifier_type'] ?? 'cookie',
            $validated['user_id'] ?? null,
            $validated['session_id'] ?? null
        );

        if (!$variant) {
            return response()->json([
                'error' => 'Test is not running'
            ], 400);
        }

        return response()->json([
            'variant' => $variant
        ]);
    }

    /**
     * Track conversion
     */
    public function trackConversion(Request $request, $id)
    {
        $validated = $request->validate([
            'user_identifier' => 'required|string',
            'conversion_type' => 'required|string',
            'conversion_value' => 'nullable|numeric',
            'metadata' => 'nullable|array'
        ]);

        $success = $this->abTestService->trackConversion(
            $id,
            $validated['user_identifier'],
            $validated['conversion_type'],
            $validated['conversion_value'] ?? null,
            $validated['metadata'] ?? null
        );

        return response()->json([
            'success' => $success,
            'message' => $success ? 'Conversion tracked' : 'User not assigned to test'
        ]);
    }

    /**
     * Calculate results
     */
    public function calculateResults($id)
    {
        $results = $this->abTestService->calculateResults($id);

        return response()->json([
            'results' => $results
        ]);
    }

    /**
     * Get test results
     */
    public function results($id)
    {
        $test = ABTest::with(['testVariants', 'results' => function($q) {
            $q->orderBy('calculated_at', 'desc');
        }, 'winningVariant', 'website'])->findOrFail($id);

        // Group results by variant and get latest result for each
        $resultsByVariant = $test->results->groupBy('variant_id')->map(function($variantResults) {
            return $variantResults->first(); // Get latest result
        });

        // Get conversion trend over time for chart
        $conversionTrend = \DB::table('ab_test_conversions')
            ->where('test_id', $id)
            ->selectRaw('DATE(converted_at) as date, COUNT(*) as conversions')
            ->whereBetween('converted_at', [\Carbon\Carbon::now()->subDays(30), \Carbon\Carbon::now()])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Get variant comparison data
        $variantStats = [];
        foreach ($test->testVariants as $variant) {
            $latestResult = $resultsByVariant->get($variant->id);
            $variantStats[] = [
                'variant' => $variant,
                'result' => $latestResult,
                'assignments_count' => $variant->assignments()->count(),
                'conversions_count' => $variant->conversions()->count()
            ];
        }

        return view('abtests.results', compact('test', 'variantStats', 'conversionTrend'));
    }

    /**
     * Determine winner
     */
    public function determineWinner($id)
    {
        $winner = $this->abTestService->determineWinner($id);

        if (!$winner) {
            return response()->json([
                'message' => 'Insufficient data to determine winner. Minimum sample size not met.'
            ], 400);
        }

        return response()->json([
            'message' => 'Winner determined',
            'winner' => $winner
        ]);
    }

    /**
     * Get conversion chart data
     */
    public function conversionChart($id)
    {
        $test = ABTest::with(['results' => function($q) {
            $q->orderBy('calculated_at', 'asc');
        }])->findOrFail($id);

        $chartData = $test->results->groupBy('variant_id')->map(function($results, $variantId) {
            $variant = \App\Models\ABTestVariant::find($variantId);
            return [
                'variant_name' => $variant->name,
                'data' => $results->map(function($result) {
                    return [
                        'timestamp' => $result->calculated_at->format('Y-m-d H:i'),
                        'conversion_rate' => $result->conversion_rate,
                        'impressions' => $result->impressions,
                        'conversions' => $result->conversions
                    ];
                })
            ];
        });

        return response()->json($chartData->values());
    }

    /**
     * Export test data
     */
    public function export($id)
    {
        $test = ABTest::with(['testVariants', 'assignments.variant', 'conversions.variant'])->findOrFail($id);
        
        $data = $test->assignments->map(function($assignment) {
            return [
                'User Identifier' => $assignment->user_identifier,
                'Variant' => $assignment->variant->name,
                'Assigned At' => $assignment->assigned_at->format('Y-m-d H:i:s'),
                'Converted' => $assignment->conversions->isNotEmpty() ? 'Yes' : 'No',
                'Conversion Value' => $assignment->conversions->sum('conversion_value')
            ];
        });

        return $this->exportCSV($data, "ab_test_{$test->id}_data.csv");
    }

    private function exportCSV($data, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            if ($data->isNotEmpty()) {
                fputcsv($file, array_keys($data->first()));
            }
            
            foreach ($data as $row) {
                fputcsv($file, $row);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
