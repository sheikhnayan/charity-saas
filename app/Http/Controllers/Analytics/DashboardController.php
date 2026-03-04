<?php

namespace App\Http\Controllers\Analytics;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\PaymentFunnelEvent;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get websites based on user role
        if (auth()->user()->role === 'admin') {
            $websites = \App\Models\Website::all();
        } else {
            $websites = \App\Models\Website::where('user_id', auth()->id())->get();
        }

        // Get selected website - prioritize websites with Transaction data for breakdown cards
        $selectedWebsiteId = $request->website_id;
        
        if (!$selectedWebsiteId) {
            // First try to find website with Transaction data
            $websiteWithTransactions = \App\Models\Transaction::select('website_id')
                ->where('status', 1)
                ->groupBy('website_id')
                ->orderByRaw('COUNT(*) DESC')
                ->first();
            
            // Fall back to PaymentFunnelEvent data if no transactions
            if (!$websiteWithTransactions) {
                $websiteWithTransactions = PaymentFunnelEvent::select('website_id')
                    ->groupBy('website_id')
                    ->orderByRaw('COUNT(*) DESC')
                    ->first();
            }
                
            $selectedWebsiteId = $websiteWithTransactions ? $websiteWithTransactions->website_id : ($websites->first()->id ?? null);
        }
        
        // Get date range - expand default to 90 days to catch more data
        $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() 
                                        : now()->subDays(90)->startOfDay();
        $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() 
                                    : now()->endOfDay();

        $stats = $this->getAnalyticsStats($selectedWebsiteId, $startDate, $endDate);
        
        // Get selected website for type checking
        $selectedWebsite = $websites->find($selectedWebsiteId);

        return view('analytics.enhanced_dashboard', compact('stats', 'websites', 'selectedWebsiteId', 'selectedWebsite', 'startDate', 'endDate'));
    }

    public function realTime(Request $request)
    {
        $websiteId = $request->website_id;
        $realTimeStats = $this->getRealTimeStats($websiteId);
        return response()->json($realTimeStats);
    }

    protected function getAnalyticsStats($websiteId, $startDate, $endDate)
    {
        $today = now()->startOfDay();
        $lastWeek = now()->subWeek();
        $lastMonth = now()->subMonth();
        
        // Debug: Log what we're looking for
        \Log::info("Analytics Stats Debug", [
            'website_id' => $websiteId,
            'start_date' => $startDate->toDateTimeString(),
            'end_date' => $endDate->toDateTimeString(),
            'payment_events_total' => PaymentFunnelEvent::count(),
            'payment_events_website' => PaymentFunnelEvent::where('website_id', $websiteId)->count(),
            'conversions_website' => PaymentFunnelEvent::where('website_id', $websiteId)->where('funnel_step', 'payment_completed')->count()
        ]);

        // Calculate Returning Customer Rate
        $returningCustomerRate = $this->getReturningCustomerRate($websiteId, $startDate, $endDate);

        // Get daily stats for the past week
        $weeklyStats = collect(range(6, 0))->map(function ($daysAgo) use ($websiteId) {
            $date = now()->subDays($daysAgo)->startOfDay();
            return [
                'date' => $date->format('D'),
                'pageViews' => $this->getPageViews($websiteId, $date, $date->copy()->endOfDay()),
                'uniqueVisitors' => $this->getUniqueVisitors($websiteId, $date, $date->copy()->endOfDay()),
                'conversions' => $this->getConversions($websiteId, $date, $date->copy()->endOfDay()),
                'revenue' => $this->getRevenue($websiteId, $date, $date->copy()->endOfDay()),
            ];
        });

        // Calculate revenue values
        $todayRevenue = $this->getRevenue($websiteId, $startDate, $endDate);
        $monthRevenue = $this->getRevenue($websiteId, $lastMonth, now());
        $todayConversions = $this->getConversions($websiteId, $startDate, $endDate);
        
        return [
            'today' => [
                'pageViews' => $this->getPageViews($websiteId, $startDate, $endDate),
                'uniqueVisitors' => $this->getUniqueVisitors($websiteId, $startDate, $endDate),
                'conversions' => $todayConversions,
                'revenue' => $todayRevenue,
                'revenueFormatted' => '$' . number_format($todayRevenue, 2),
                'sessions' => $this->getUniqueVisitors($websiteId, $startDate, $endDate),
                // Shopify-style metrics
                'grossSales' => $todayRevenue, // Total revenue = gross sales
                'grossSalesFormatted' => '$' . number_format($todayRevenue, 2),
                'returningCustomerRate' => $returningCustomerRate,
                'returningCustomerRateFormatted' => number_format($returningCustomerRate, 2) . '%',
                'ordersFulfilled' => $todayConversions, // Completed payments = fulfilled orders
                'orders' => $todayConversions, // Total orders = conversions
            ],
            'week' => [
                'dates' => $weeklyStats->pluck('date')->toArray(),
                'pageViews' => $weeklyStats->pluck('pageViews')->toArray(),
                'uniqueVisitors' => $weeklyStats->pluck('uniqueVisitors')->toArray(),
                'conversions' => $weeklyStats->pluck('conversions')->toArray(),
                'revenue' => $weeklyStats->pluck('revenue')->toArray(),
            ],
            'month' => [
                'pageViews' => $this->getPageViews($websiteId, $lastMonth, now()),
                'uniqueVisitors' => $this->getUniqueVisitors($websiteId, $lastMonth, now()),
                'conversions' => $this->getConversions($websiteId, $lastMonth, now()),
                'revenue' => $monthRevenue,
                'revenueFormatted' => '$' . number_format($monthRevenue, 2),
            ],
            'topPages' => $this->getTopPages($websiteId, $startDate, $endDate),
            'topReferrers' => $this->getTopReferrers($websiteId, $startDate, $endDate),
            'deviceBreakdown' => $this->getDeviceBreakdown($websiteId, $startDate, $endDate),
            'locationData' => $this->getLocationData($websiteId, $startDate, $endDate),
            'salesByPaymentMethod' => $this->getSalesByPaymentMethod($websiteId, $startDate, $endDate),
            'salesByType' => $this->getSalesByDonationType($websiteId, $startDate, $endDate),
            'detailedTransactions' => $this->getDetailedTransactions($websiteId, $startDate, $endDate),
            'pageViewDetails' => $this->getPageViewDetails($websiteId, $startDate, $endDate),
            'referrerDetails' => $this->getReferrerDetails($websiteId, $startDate, $endDate),
        ];
    }

    protected function getRealTimeStats($websiteId = null)
    {
        $lastFiveMinutes = now()->subMinutes(5);
        $lastWeek = now()->subDays(7); // Extended to 7 days to show recent activity
        
        // Get recent payment activity
        $paymentActivity = $this->getRecentPaymentActivity($lastWeek, $websiteId);
        
        // Get recent auction activity (bids, new auctions)
        $auctionActivity = $this->getRecentAuctionActivity($lastWeek, $websiteId);
        
        // Merge and sort all activities by time
        $allActivities = collect($paymentActivity)->merge($auctionActivity)
            ->sortByDesc('created_at')
            ->values()
            ->take(10);
        
        \Log::info('Real-time activity loaded', [
            'payment_count' => $paymentActivity->count(),
            'auction_count' => $auctionActivity->count(),
            'total_activities' => $allActivities->count()
        ]);
        
        return [
            'activeUsers' => $this->getActiveUsers($lastFiveMinutes, $websiteId),
            'recentPageViews' => $allActivities,
            'recentConversions' => $this->getRecentConversions($lastWeek, $websiteId),
        ];
    }

    protected function getPageViews($websiteId, $startDate, $endDate)
    {
        return \App\Models\AnalyticsEvent::where('event_type', 'page_view')
            ->where('website_id', $websiteId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
    }

    protected function getUniqueVisitors($websiteId, $startDate, $endDate)
    {
        // Try visitor_id first, then fall back to session_id
        $visitorCount = \App\Models\PaymentFunnelEvent::where('website_id', $websiteId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('visitor_id')
            ->distinct('visitor_id')
            ->count('visitor_id');
            
        if ($visitorCount === 0) {
            $visitorCount = \App\Models\PaymentFunnelEvent::where('website_id', $websiteId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->distinct('session_id')
                ->count('session_id');
        }
        
        return $visitorCount;
    }

    protected function getCompletionFunnelStep()
    {
        // Dynamically detect the correct completion funnel step
        $possibleSteps = ['payment_completed', 'payment_complete', 'completed', 'payment_success', 'success'];
        
        foreach ($possibleSteps as $step) {
            $count = \App\Models\PaymentFunnelEvent::where('funnel_step', $step)->count();
            if ($count > 0) {
                \Log::info("Using funnel step: {$step} (found {$count} records)");
                return $step;
            }
        }
        
        // Fallback to payment_completed if nothing found
        \Log::warning("No completion funnel step found, defaulting to 'payment_completed'");
        return 'payment_completed';
    }

    protected function getConversions($websiteId, $startDate, $endDate)
    {
        // Get conversions from PaymentFunnelEvent using dynamic step detection
        $completionStep = $this->getCompletionFunnelStep();
        
        $count = \App\Models\PaymentFunnelEvent::where('funnel_step', $completionStep)
            ->where('website_id', $websiteId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        
        // Fallback to Transaction count if PaymentFunnelEvent has no conversions
        if ($count == 0) {
            $transactionCount = \App\Models\Transaction::where('website_id', $websiteId)
                ->where('status', 1)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();
            $donationCount = \App\Models\Donation::where('website_id', $websiteId)
                ->where('status', 1)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();
            $count = $transactionCount + $donationCount;
        }
            
        return $count;
    }

    protected function getRevenue($websiteId, $startDate, $endDate)
    {
        // Get revenue from PaymentFunnelEvent using dynamic step detection
        $completionStep = $this->getCompletionFunnelStep();
        
        $revenue = \App\Models\PaymentFunnelEvent::where('funnel_step', $completionStep)
            ->where('website_id', $websiteId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount') ?? 0;
        
        // Fallback to Transaction data if PaymentFunnelEvent has no revenue
        if ($revenue == 0) {
            $transactionRevenue = \App\Models\Transaction::where('website_id', $websiteId)
                ->where('status', 1)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('amount') ?? 0;
            $donationRevenue = \App\Models\Donation::where('website_id', $websiteId)
                ->where('status', 1)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('SUM(amount + COALESCE(tip_amount, 0)) as total')
                ->value('total') ?? 0;
            $revenue = $transactionRevenue + $donationRevenue;
        }
            
        return $revenue;
    }

    protected function getTopPages($websiteId, $startDate, $endDate)
    {
        return \App\Models\AnalyticsEvent::where('event_type', 'page_view')
            ->where('website_id', $websiteId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('url')
            ->selectRaw('url, count(*) as views')
            ->orderByDesc('views')
            ->limit(10)
            ->get();
    }

    protected function getTopReferrers($websiteId, $startDate, $endDate)
    {
        return \App\Models\AnalyticsEvent::whereNotNull('referrer_url')
            ->where('website_id', $websiteId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('referrer_url')
            ->selectRaw('referrer_url, count(*) as count')
            ->orderByDesc('count')
            ->limit(10)
            ->get();
    }

    protected function getDeviceBreakdown($websiteId, $startDate, $endDate)
    {
        return \App\Models\AnalyticsEvent::where('website_id', $websiteId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('device_type')
            ->groupBy('device_type')
            ->selectRaw('device_type, count(*) as count')
            ->orderByDesc('count')
            ->get();
    }

    protected function getLocationData($websiteId, $startDate, $endDate)
    {
        // First try to get data with country info
        $withCountry = \App\Models\AnalyticsEvent::whereNotNull('country')
            ->where('website_id', $websiteId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('country')
            ->selectRaw('country, count(*) as count')
            ->orderByDesc('count')
            ->limit(10)
            ->get();
            
        // If no country data, return IP-based data as fallback
        if ($withCountry->isEmpty()) {
            return \App\Models\AnalyticsEvent::whereNotNull('ip_address')
                ->where('website_id', $websiteId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('ip_address')
                ->selectRaw('ip_address as country, count(*) as count')
                ->orderByDesc('count')
                ->limit(10)
                ->get();
        }
        
        return $withCountry;
    }

    /**
     * Get gross sales breakdown by payment gateway/method
     */
    protected function getSalesByPaymentMethod($websiteId, $startDate = null, $endDate = null)
    {
        return \App\Models\Transaction::where('website_id', $websiteId)
            ->where('status', 1)
            ->groupBy('payment_method')
            ->selectRaw('COALESCE(payment_method, "Unknown") as payment_method, count(*) as count, sum(amount) as total')
            ->orderByDesc('total')
            ->get();
    }

    /**
     * Get gross sales breakdown by donation type
     */
    protected function getSalesByDonationType($websiteId, $startDate = null, $endDate = null)
    {
        $query = \App\Models\Donation::where('website_id', $websiteId)
            ->where('status', 1);

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $query->groupBy('type')
            ->selectRaw('type, count(*) as count, SUM(amount + COALESCE(tip_amount, 0)) as total')
            ->orderByDesc('total')
            ->get();
    }

    /**
     * Get all transactions with detailed information for export
     */
    protected function getDetailedTransactions($websiteId, $startDate = null, $endDate = null)
    {
        return \App\Models\Transaction::where('website_id', $websiteId)
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * Get page view data with URLs for export
     */
    protected function getPageViewDetails($websiteId, $startDate, $endDate)
    {
        return \App\Models\AnalyticsEvent::where('event_type', 'page_view')
            ->where('website_id', $websiteId)
            ->whereDate('created_at', '>=', $startDate->toDateString())
            ->whereDate('created_at', '<=', $endDate->toDateString())
            ->groupBy('url')
            ->selectRaw('url, count(*) as views')
            ->orderByDesc('views')
            ->limit(50)
            ->get();
    }

    /**
     * Get referrer data with details for export
     */
    protected function getReferrerDetails($websiteId, $startDate, $endDate)
    {
        return \App\Models\AnalyticsEvent::whereNotNull('referrer_url')
            ->where('website_id', $websiteId)
            ->whereDate('created_at', '>=', $startDate->toDateString())
            ->whereDate('created_at', '<=', $endDate->toDateString())
            ->groupBy('referrer_url')
            ->selectRaw('referrer_url, count(*) as count')
            ->orderByDesc('count')
            ->limit(50)
            ->get();
    }

    protected function getActiveUsers($since, $websiteId = null)
    {
        // Count unique sessions from PaymentFunnelEvent in the time period
        $query = PaymentFunnelEvent::where('created_at', '>=', $since);
        
        if ($websiteId) {
            $query->where('website_id', $websiteId);
        }
        
        return $query->distinct('session_id')->count('session_id');
    }

    protected function getRecentPageViews($since, $websiteId = null)
    {
        $query = \App\Models\AnalyticsEvent::where('event_type', 'page_view')
            ->where('created_at', '>=', $since);
            
        if ($websiteId) {
            $query->where('website_id', $websiteId);
        }
        
        return $query->orderByDesc('created_at')
            ->limit(10)
            ->get();
    }

    protected function getRecentConversions($since, $websiteId = null)
    {
        $query = PaymentFunnelEvent::where('funnel_step', 'payment_completed')
            ->where('created_at', '>=', $since);
            
        if ($websiteId) {
            $query->where('website_id', $websiteId);
        }
        
        return $query->orderByDesc('created_at')
            ->limit(5)
            ->get();
    }

    protected function getRecentPaymentActivity($since, $websiteId = null)
    {
        // Get the actual completion step name
        $completionStep = $this->getCompletionFunnelStep();
        
        $query = PaymentFunnelEvent::whereIn('funnel_step', ['form_view', 'amount_entered', $completionStep])
            ->where('created_at', '>=', $since);
            
        if ($websiteId) {
            $query->where('website_id', $websiteId);
        }
        
        \Log::info('Recent Payment Activity Query', [
            'since' => $since,
            'website_id' => $websiteId,
            'completion_step' => $completionStep,
            'count' => $query->count()
        ]);
        
        return $query->orderByDesc('created_at')
            ->limit(10)
            ->get()
            ->map(function ($event) {
                return [
                    'created_at' => $event->created_at,
                    'event_type' => $event->funnel_step,
                    'form_type' => $event->form_type,
                    'amount' => $event->amount,
                    'session_id' => $event->session_id,
                    'user_id' => $event->user_id,
                    'url' => $event->url ?? 'payment',
                    'page_url' => $event->url ?? 'Payment Form',
                    'country' => $event->country,
                    'state' => $event->state,
                ];
            });
    }

    protected function getRecentAuctionActivity($since, $websiteId = null)
    {
        $query = \App\Models\Auction::where('created_at', '>=', $since);
            
        if ($websiteId) {
            $query->where('website_id', $websiteId);
        }
        
        return $query->orderByDesc('created_at')
            ->limit(5)
            ->get()
            ->map(function ($auction) {
                return [
                    'created_at' => $auction->updated_at ?? $auction->created_at, // Use updated_at for bid activity
                    'event_type' => 'auction_activity',
                    'form_type' => 'auction',
                    'amount' => $auction->current_bid ?? $auction->starting_bid ?? null,
                    'session_id' => 'auction_' . $auction->id,
                    'user_id' => null,
                    'url' => 'auction',
                    'page_url' => 'Auction: ' . ($auction->name ?? 'Item #' . $auction->id),
                    'auction_name' => $auction->name ?? 'Auction Item'
                ];
            });
    }

    /**
     * Calculate returning customer rate
     * Returns percentage of customers who have made multiple purchases
     */
    protected function getReturningCustomerRate($websiteId, $startDate, $endDate)
    {
        $completionStep = $this->getCompletionFunnelStep();
        
        // Get all customers (users) who made purchases in the date range
        $customersInPeriod = PaymentFunnelEvent::where('funnel_step', $completionStep)
            ->where('website_id', $websiteId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('user_id') // Only count registered users
            ->distinct('user_id')
            ->pluck('user_id');
        
        if ($customersInPeriod->isEmpty()) {
            return 0;
        }
        
        // Count how many of these customers have made purchases BEFORE this period
        $returningCustomers = PaymentFunnelEvent::where('funnel_step', $completionStep)
            ->where('website_id', $websiteId)
            ->where('created_at', '<', $startDate) // Purchases before the period
            ->whereIn('user_id', $customersInPeriod)
            ->distinct('user_id')
            ->count();
        
        $totalCustomers = $customersInPeriod->count();
        
        return $totalCustomers > 0 ? ($returningCustomers / $totalCustomers) * 100 : 0;
    }

    /**
     * Chart Data API Endpoints
     */
    public function getTimeBasedConversions(Request $request)
    {
        $websiteId = $request->website_id;
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : now()->subDays(30);
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : now();
        $groupBy = $request->group_by ?? 'day';
        
        $chartService = new \App\Services\AnalyticsChartService();
        return response()->json($chartService->getTimeBasedConversions($websiteId, $startDate, $endDate, $groupBy));
    }

    public function getTimeBasedSessions(Request $request)
    {
        $websiteId = $request->website_id;
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : now()->subDays(30);
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : now();
        $groupBy = $request->group_by ?? 'day';
        
        $chartService = new \App\Services\AnalyticsChartService();
        return response()->json($chartService->getTimeBasedSessions($websiteId, $startDate, $endDate, $groupBy));
    }

    public function getConversionFunnel(Request $request)
    {
        try {
            $websiteId = $request->website_id;
            $startDate = $request->start_date ? Carbon::parse($request->start_date) : now()->subDays(30);
            $endDate = $request->end_date ? Carbon::parse($request->end_date) : now();
            
            $chartService = new \App\Services\AnalyticsChartService();
            $data = $chartService->getConversionFunnelData($websiteId, $startDate, $endDate);
            
            return response()->json($data);
        } catch (\Exception $e) {
            \Log::error('Funnel data error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load funnel data', 'message' => $e->getMessage()], 500);
        }
    }

    public function getDeviceData(Request $request)
    {
        try {
            $websiteId = $request->website_id;
            $startDate = $request->start_date ? Carbon::parse($request->start_date) : now()->subDays(30);
            $endDate = $request->end_date ? Carbon::parse($request->end_date) : now();
            
            $chartService = new \App\Services\AnalyticsChartService();
            $data = $chartService->getDeviceBreakdown($websiteId, $startDate, $endDate);
            
            return response()->json($data);
        } catch (\Exception $e) {
            \Log::error('Device data error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load device data', 'message' => $e->getMessage()], 500);
        }
    }

    public function getLocationChartData(Request $request)
    {
        try {
            $websiteId = $request->website_id;
            $startDate = $request->start_date ? Carbon::parse($request->start_date) : now()->subDays(30);
            $endDate = $request->end_date ? Carbon::parse($request->end_date) : now();
            
            $chartService = new \App\Services\AnalyticsChartService();
            $data = $chartService->getLocationBreakdown($websiteId, $startDate, $endDate);
            
            return response()->json($data);
        } catch (\Exception $e) {
            \Log::error('Location data error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load location data', 'message' => $e->getMessage()], 500);
        }
    }

    public function getProductData(Request $request)
    {
        $websiteId = $request->website_id;
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : now()->subDays(30);
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : now();
        
        $chartService = new \App\Services\AnalyticsChartService();
        return response()->json($chartService->getProductSellThroughRates($websiteId, $startDate, $endDate));
    }

    public function getGeoMapData(Request $request)
    {
        try {
            $websiteId = $request->website_id;
            $startDate = $request->start_date ? Carbon::parse($request->start_date) : now()->subDays(30);
            $endDate = $request->end_date ? Carbon::parse($request->end_date) : now();
            
            $chartService = new \App\Services\AnalyticsChartService();
            $data = $chartService->getGeoMapData($websiteId, $startDate, $endDate);
            
            return response()->json($data);
        } catch (\Exception $e) {
            \Log::error('GeoMap data error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load geomap data', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Export analytics dashboard data as CSV or Excel
     */
    public function export(Request $request)
    {
        $websiteId = $request->website_id;
        $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() 
                                        : now()->subDays(90)->startOfDay();
        $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() 
                                    : now()->endOfDay();
        $format = $request->format ?? 'csv'; // csv or excel

        // Get analytics data
        $stats = $this->getAnalyticsStats($websiteId, $startDate, $endDate);
        
        // Get website name for file naming
        $websiteName = $websiteId ? \App\Models\Website::find($websiteId)?->name : 'All_Websites';
        $websiteName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $websiteName);

        if ($format === 'excel') {
            // Excel export - keep existing implementation
            $excelService = new \App\Services\ExcelExportService();
            $spreadsheet = $excelService->exportAnalyticsDashboard($stats, $websiteName, $startDate, $endDate);
            
            $filename = 'analytics_dashboard_' . $websiteName . '_' . now()->format('Y-m-d');
            return $excelService->generateAndDownload($spreadsheet, $filename);
        }

        // CSV export - use plain text format
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="Analytics_' . $websiteName . '_' . now()->format('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($stats, $startDate, $endDate, $websiteName) {
            $output = '';
            
            // Helper function to add a line
            $line = function($data = []) {
                if (empty($data)) return "\n";
                return implode(',', array_map(function($v) {
                    $v = (string)$v;
                    if (strpos($v, ',') !== false || strpos($v, '"') !== false || strpos($v, "\n") !== false) {
                        return '"' . str_replace('"', '""', $v) . '"';
                    }
                    return $v;
                }, $data)) . "\n";
            };
            
            echo $line(['=== ANALYTICS DASHBOARD EXPORT ===']);
            echo $line(['Website', $websiteName]);
            echo $line(['Export Date', now()->format('F d, Y h:i A')]);
            echo $line(['Date Range', $startDate->format('M d, Y') . ' to ' . $endDate->format('M d, Y')]);
            echo $line();
            echo $line();
            
            // KEY METRICS
            echo $line(['=== KEY PERFORMANCE METRICS ===']);
            echo $line();
            echo $line(['Metric', 'Value']);
            echo $line(['Gross Sales', '$' . number_format($stats['today']['grossSales'] ?? 0, 2)]);
            echo $line(['Total Orders', number_format($stats['today']['orders'] ?? 0)]);
            echo $line(['Orders Fulfilled', number_format($stats['today']['ordersFulfilled'] ?? 0)]);
            echo $line(['Total Conversions', number_format($stats['today']['conversions'] ?? 0)]);
            echo $line(['Unique Visitors', number_format($stats['today']['uniqueVisitors'] ?? 0)]);
            echo $line(['Page Views', number_format($stats['today']['pageViews'] ?? 0)]);
            echo $line(['Returning Customer Rate', number_format($stats['today']['returningCustomerRate'] ?? 0, 2) . '%']);
            echo $line();
            echo $line();
            
            // SALES BY PAYMENT METHOD
            echo $line(['=== SALES BY PAYMENT METHOD ===']);
            echo $line();
            if (!empty($stats['salesByPaymentMethod'])) {
                echo $line(['Payment Method', 'Transactions', 'Total Sales', 'Percentage']);
                $grandTotal = 0;
                $totalTxns = 0;
                foreach ($stats['salesByPaymentMethod'] as $method) {
                    $grandTotal += $method->total;
                    $totalTxns += $method->count;
                }
                foreach ($stats['salesByPaymentMethod'] as $method) {
                    $pct = $grandTotal > 0 ? ($method->total / $grandTotal * 100) : 0;
                    echo $line([
                        ucfirst($method->payment_method ?? 'Unknown'),
                        $method->count,
                        '$' . number_format($method->total, 2),
                        number_format($pct, 1) . '%'
                    ]);
                }
                echo $line(['TOTAL', $totalTxns, '$' . number_format($grandTotal, 2), '100.0%']);
            } else {
                echo $line(['No payment method data available']);
            }
            echo $line();
            echo $line();
            
            // SALES BY TYPE
            echo $line(['=== SALES BY DONATION TYPE ===']);
            echo $line();
            if (!empty($stats['salesByType'])) {
                echo $line(['Type', 'Transactions', 'Total Sales', 'Avg Transaction', 'Percentage']);
                $grandTotal = 0;
                $totalTxns = 0;
                foreach ($stats['salesByType'] as $type) {
                    $grandTotal += $type->total;
                    $totalTxns += $type->count;
                }
                foreach ($stats['salesByType'] as $type) {
                    $avg = $type->count > 0 ? ($type->total / $type->count) : 0;
                    $pct = $grandTotal > 0 ? ($type->total / $grandTotal * 100) : 0;
                    echo $line([
                        ucfirst($type->type ?? 'Unknown'),
                        $type->count,
                        '$' . number_format($type->total, 2),
                        '$' . number_format($avg, 2),
                        number_format($pct, 1) . '%'
                    ]);
                }
                $avgOverall = $totalTxns > 0 ? ($grandTotal / $totalTxns) : 0;
                echo $line(['TOTAL', $totalTxns, '$' . number_format($grandTotal, 2), '$' . number_format($avgOverall, 2), '100.0%']);
            } else {
                echo $line(['No donation type data available']);
            }
            echo $line();
            echo $line();
            
            // ALL TRANSACTIONS
            echo $line(['=== ALL TRANSACTIONS (DETAILED) ===']);
            echo $line();
            if (!empty($stats['detailedTransactions'])) {
                echo $line(['Date', 'Time', 'ID', 'Type', 'Payment Method', 'Donor Name', 'Email', 'City', 'State', 'Amount', 'Status']);
                $totalAmount = 0;
                foreach ($stats['detailedTransactions'] as $txn) {
                    $totalAmount += $txn->amount;
                    $status = $txn->status == 1 ? 'Completed' : ($txn->status == 0 ? 'Pending' : 'Unknown');
                    echo $line([
                        $txn->created_at->format('Y-m-d'),
                        $txn->created_at->format('H:i:s'),
                        $txn->id,
                        ucfirst($txn->type ?? 'Unknown'),
                        ucfirst($txn->payment_method ?? 'Not Specified'),
                        $txn->name ?? 'Anonymous',
                        $txn->email ?? 'Not Provided',
                        $txn->city ?? '',
                        $txn->state ?? '',
                        '$' . number_format($txn->amount, 2),
                        $status
                    ]);
                }
                echo $line(['', '', '', '', '', '', '', '', 'TOTAL:', '$' . number_format($totalAmount, 2), '']);
            } else {
                echo $line(['No transaction data available']);
            }
            echo $line();
            echo $line();
            
            // TOP PAGES
            echo $line(['=== TOP 50 PAGES VIEWED ===']);
            echo $line();
            if (!empty($stats['pageViewDetails'])) {
                echo $line(['Rank', 'Page URL', 'Views']);
                $rank = 1;
                foreach ($stats['pageViewDetails'] as $page) {
                    echo $line([$rank++, $page->url ?? 'Unknown', $page->views]);
                }
            } else {
                echo $line(['No page view data available']);
            }
            echo $line();
            echo $line();
            
            // TOP REFERRERS
            echo $line(['=== TOP 50 REFERRER SOURCES ===']);
            echo $line();
            if (!empty($stats['referrerDetails'])) {
                echo $line(['Rank', 'Referrer URL', 'Visitors']);
                $rank = 1;
                foreach ($stats['referrerDetails'] as $ref) {
                    echo $line([$rank++, $ref->referrer_url ?? 'Direct Traffic', $ref->count]);
                }
            } else {
                echo $line(['No referrer data available']);
            }
            echo $line();
            echo $line();
            
            // FOOTER
            echo $line(['=== END OF REPORT ===']);
            echo $line(['Generated by Analytics Dashboard']);
            echo $line(['Export Time', now()->format('Y-m-d H:i:s')]);
        };

        return response()->stream($callback, 200, $headers);
    }
}