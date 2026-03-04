<?php

namespace App\Http\Controllers;

use App\Models\FraudRule;
use App\Models\FraudDetection;
use App\Services\FraudDetectionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class FraudDetectionController extends Controller
{
    protected $fraudService;

    public function __construct(FraudDetectionService $fraudService)
    {
        $this->fraudService = $fraudService;
    }

    /**
     * Display fraud detection dashboard
     */
    public function index(Request $request)
    {
        // Get website filter (from request or user's default website)
        $websiteId = $request->website_id ?? auth()->user()->website_id ?? null;
        
        $stats = $this->fraudService->getStatistics(null, null, $websiteId);
        
        // Filter detections by website through transaction/donation relationships
        $recentDetections = FraudDetection::with(['fraudRule', 'transaction.website', 'donation.website', 'user'])
            ->when($websiteId, function($query) use ($websiteId) {
                $query->where(function($q) use ($websiteId) {
                    $q->whereHas('transaction', function($tq) use ($websiteId) {
                        $tq->where('website_id', $websiteId);
                    })
                    ->orWhereHas('donation', function($dq) use ($websiteId) {
                        $dq->where('website_id', $websiteId);
                    });
                });
            })
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        $activeRules = FraudRule::active()->orderBy('priority', 'desc')->get();
        
        // Get all websites for filter dropdown
        $websites = \App\Models\Website::all();
        $selectedWebsiteId = $websiteId;

        // Chart Data: Daily fraud trend (last 7 days)
        $dailyTrend = FraudDetection::selectRaw('DATE(created_at) as date, COUNT(*) as count, AVG(risk_score) as avg_risk, SUM(CASE WHEN action_taken = "blocked" THEN 1 ELSE 0 END) as blocked')
            ->when($websiteId, function($query) use ($websiteId) {
                $query->where(function($q) use ($websiteId) {
                    $q->whereHas('transaction', function($tq) use ($websiteId) {
                        $tq->where('website_id', $websiteId);
                    })
                    ->orWhereHas('donation', function($dq) use ($websiteId) {
                        $dq->where('website_id', $websiteId);
                    });
                });
            })
            ->whereBetween('created_at', [Carbon::now()->subDays(6)->startOfDay(), Carbon::now()->endOfDay()])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Chart Data: Risk distribution
        $riskDistribution = FraudDetection::selectRaw('
                CASE 
                    WHEN risk_score < 30 THEN "Low"
                    WHEN risk_score < 60 THEN "Medium"
                    WHEN risk_score < 80 THEN "High"
                    ELSE "Critical"
                END as risk_level,
                COUNT(*) as count
            ')
            ->when($websiteId, function($query) use ($websiteId) {
                $query->where(function($q) use ($websiteId) {
                    $q->whereHas('transaction', function($tq) use ($websiteId) {
                        $tq->where('website_id', $websiteId);
                    })
                    ->orWhereHas('donation', function($dq) use ($websiteId) {
                        $dq->where('website_id', $websiteId);
                    });
                });
            })
            ->whereBetween('created_at', [Carbon::now()->subDays(30), Carbon::now()])
            ->groupByRaw('risk_level')
            ->get()
            ->keyBy('risk_level');

        // Format chart data
        $chartData = [
            'trend' => [
                'labels' => $dailyTrend->pluck('date')->map(fn($d) => Carbon::parse($d)->format('D'))->toArray(),
                'risk_scores' => $dailyTrend->pluck('avg_risk')->map(fn($v) => round($v ?? 0, 1))->toArray(),
                'blocked_counts' => $dailyTrend->pluck('blocked')->toArray(),
            ],
            'distribution' => [
                'low' => $riskDistribution->get('Low')->count ?? 0,
                'medium' => $riskDistribution->get('Medium')->count ?? 0,
                'high' => $riskDistribution->get('High')->count ?? 0,
                'critical' => $riskDistribution->get('Critical')->count ?? 0,
            ]
        ];

        return view('fraud.index', compact('stats', 'recentDetections', 'activeRules', 'websites', 'selectedWebsiteId', 'chartData'));
    }

    /**
     * Get fraud statistics API
     */
    public function getStatistics(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->subDays(30);
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now();
        $websiteId = $request->website_id ?? auth()->user()->website_id ?? null;

        $stats = $this->fraudService->getStatistics($startDate, $endDate, $websiteId);

        // Get daily breakdown filtered by website
        $dailyStats = FraudDetection::selectRaw('DATE(created_at) as date, COUNT(*) as count, AVG(risk_score) as avg_risk')
            ->when($websiteId, function($query) use ($websiteId) {
                $query->where(function($q) use ($websiteId) {
                    $q->whereHas('transaction', function($tq) use ($websiteId) {
                        $tq->where('website_id', $websiteId);
                    })
                    ->orWhereHas('donation', function($dq) use ($websiteId) {
                        $dq->where('website_id', $websiteId);
                    });
                });
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top triggered rules filtered by website
        $topRules = FraudDetection::selectRaw('fraud_rule_id, COUNT(*) as triggers')
            ->with('fraudRule')
            ->when($websiteId, function($query) use ($websiteId) {
                $query->where(function($q) use ($websiteId) {
                    $q->whereHas('transaction', function($tq) use ($websiteId) {
                        $tq->where('website_id', $websiteId);
                    })
                    ->orWhereHas('donation', function($dq) use ($websiteId) {
                        $dq->where('website_id', $websiteId);
                    });
                });
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('fraud_rule_id')
            ->orderBy('triggers', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'summary' => $stats,
            'daily_stats' => $dailyStats,
            'top_rules' => $topRules,
        ]);
    }

    /**
     * Get pending detections
     */
    public function pending(Request $request)
    {
        $websiteId = $request->website_id ?? auth()->user()->website_id ?? null;
        
        $detections = FraudDetection::with(['fraudRule', 'transaction.website', 'donation.website', 'user'])
            ->pendingReview()
            ->when($websiteId, function($query) use ($websiteId) {
                $query->where(function($q) use ($websiteId) {
                    $q->whereHas('transaction', function($tq) use ($websiteId) {
                        $tq->where('website_id', $websiteId);
                    })
                    ->orWhereHas('donation', function($dq) use ($websiteId) {
                        $dq->where('website_id', $websiteId);
                    });
                });
            })
            ->orderBy('risk_score', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return response()->json($detections);
    }

    /**
     * Review a detection
     */
    public function review(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:cleared,blocked',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $detection = FraudDetection::findOrFail($id);
        $detection->update([
            'status' => $request->status,
            'notes' => $request->notes,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return response()->json([
            'message' => 'Detection reviewed successfully',
            'detection' => $detection->load('fraudRule', 'reviewer'),
        ]);
    }

    /**
     * List all fraud rules
     */
    public function rules()
    {
        $rules = FraudRule::orderBy('priority', 'desc')->get();
        return response()->json($rules);
    }

    /**
     * Create a new fraud rule
     */
    public function createRule(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'rule_type' => 'required|in:velocity,geolocation,amount_threshold,card_testing,pattern_matching',
            'parameters' => 'required|array',
            'action' => 'required|in:flag,block,review',
            'risk_score' => 'required|integer|min:1|max:100',
            'priority' => 'nullable|integer',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $rule = FraudRule::create($request->all());

        return response()->json([
            'message' => 'Rule created successfully',
            'rule' => $rule,
        ], 201);
    }

    /**
     * Update a fraud rule
     */
    public function updateRule(Request $request, $id)
    {
        $rule = FraudRule::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'rule_type' => 'sometimes|in:velocity,geolocation,amount_threshold,card_testing,pattern_matching',
            'parameters' => 'sometimes|array',
            'action' => 'sometimes|in:flag,block,review',
            'risk_score' => 'sometimes|integer|min:1|max:100',
            'priority' => 'nullable|integer',
            'is_active' => 'sometimes|boolean',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $rule->update($request->all());

        return response()->json([
            'message' => 'Rule updated successfully',
            'rule' => $rule,
        ]);
    }

    /**
     * Delete a fraud rule
     */
    public function deleteRule($id)
    {
        $rule = FraudRule::findOrFail($id);
        $rule->delete();

        return response()->json(['message' => 'Rule deleted successfully']);
    }

    /**
     * Toggle rule active status
     */
    public function toggleRule($id)
    {
        $rule = FraudRule::findOrFail($id);
        $rule->is_active = !$rule->is_active;
        $rule->save();

        return response()->json([
            'message' => 'Rule status updated',
            'rule' => $rule,
        ]);
    }

    /**
     * Get recent fraud detections for dashboard
     */
    public function getRecentDetections(Request $request)
    {
        $limit = $request->limit ?? 50;
        $websiteId = $request->website_id ?? auth()->user()->website_id ?? null;
        
        $detections = FraudDetection::with(['user', 'transaction.website', 'donation.website'])
            ->when($websiteId, function($query) use ($websiteId) {
                $query->where(function($q) use ($websiteId) {
                    $q->whereHas('transaction', function($tq) use ($websiteId) {
                        $tq->where('website_id', $websiteId);
                    })
                    ->orWhereHas('donation', function($dq) use ($websiteId) {
                        $dq->where('website_id', $websiteId);
                    });
                });
            })
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function($detection) {
                return [
                    'id' => $detection->id,
                    'transaction_id' => $detection->transaction_id ?? $detection->donation_id ?? 'N/A',
                    'user_id' => $detection->user_id,
                    'amount' => $detection->amount ?? 0,
                    'risk_score' => $detection->risk_score,
                    'risk_level' => $detection->risk_level,
                    'action_taken' => $detection->action_taken,
                    'detection_reason' => $detection->detection_reason,
                    'created_at' => $detection->created_at->toIso8601String(),
                ];
            });

        return response()->json($detections);
    }
}
