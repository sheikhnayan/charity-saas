<?php

namespace App\Http\Controllers\Analytics;

use App\Http\Controllers\Controller;
use App\Models\PaymentFunnelEvent;
use App\Models\AnalyticsEvent;
use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UTMAnalyticsController extends Controller
{
    /**
     * Display UTM attribution dashboard
     */
    public function index(Request $request)
    {
        $websiteId = $request->website_id ?? auth()->user()->website_id ?? null;
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->subDays(30);
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now();

        // Get all websites for filter
        $websites = Website::all();
        
        // Get UTM statistics
        $stats = $this->getUTMStats($websiteId, $startDate, $endDate);
        
        // Get campaign performance
        $campaigns = $this->getCampaignPerformance($websiteId, $startDate, $endDate);
        
        // Get source/medium breakdown
        $sources = $this->getSourceMediumBreakdown($websiteId, $startDate, $endDate);
        
        // Get conversion attribution
        $attribution = $this->getConversionAttribution($websiteId, $startDate, $endDate);
        
        // Get trending campaigns
        $trending = $this->getTrendingCampaigns($websiteId, $startDate, $endDate);

        return view('analytics.utm', compact(
            'websites',
            'websiteId',
            'startDate',
            'endDate',
            'stats',
            'campaigns',
            'sources',
            'attribution',
            'trending'
        ));
    }

    /**
     * Get UTM statistics summary
     */
    protected function getUTMStats($websiteId, $startDate, $endDate)
    {
        // Query AnalyticsEvent table instead of PaymentFunnelEvent
        $query = AnalyticsEvent::query();
        
        if ($websiteId) {
            $query->where('website_id', $websiteId);
        }
        
        $query->whereBetween('created_at', [$startDate, $endDate]);

        // Total sessions with UTM
        $totalWithUTM = (clone $query)
            ->whereNotNull('utm_source')
            ->distinct('session_id')
            ->count('session_id');

        // Total sessions
        $totalSessions = (clone $query)
            ->distinct('session_id')
            ->count('session_id');

        // UTM-attributed conversions (from both analytics and payment funnel)
        $utmConversions = PaymentFunnelEvent::query()
            ->when($websiteId, fn($q) => $q->where('website_id', $websiteId))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('funnel_step', 'payment_completed')
            ->whereNotNull('utm_source')
            ->count();

        // UTM-attributed revenue (from payment funnel)
        $utmRevenue = PaymentFunnelEvent::query()
            ->when($websiteId, fn($q) => $q->where('website_id', $websiteId))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('funnel_step', 'payment_completed')
            ->whereNotNull('utm_source')
            ->sum('amount') ?? 0;

        // Unique campaigns
        $uniqueCampaigns = (clone $query)
            ->whereNotNull('utm_campaign')
            ->distinct('utm_campaign')
            ->count('utm_campaign');

        // Unique sources
        $uniqueSources = (clone $query)
            ->whereNotNull('utm_source')
            ->distinct('utm_source')
            ->count('utm_source');

        return [
            'total_with_utm' => $totalWithUTM,
            'total_sessions' => $totalSessions,
            'utm_percentage' => $totalSessions > 0 ? round(($totalWithUTM / $totalSessions) * 100, 1) : 0,
            'utm_conversions' => $utmConversions,
            'utm_revenue' => $utmRevenue / 100, // Convert cents to dollars
            'unique_campaigns' => $uniqueCampaigns,
            'unique_sources' => $uniqueSources,
            'avg_revenue_per_conversion' => $utmConversions > 0 ? ($utmRevenue / 100) / $utmConversions : 0
        ];
    }

    /**
     * Get campaign performance data
     */
    protected function getCampaignPerformance($websiteId, $startDate, $endDate)
    {
        // Query AnalyticsEvent for UTM sessions and visitors
        $query = AnalyticsEvent::query();
        
        if ($websiteId) {
            $query->where('website_id', $websiteId);
        }
        
        // Get sessions by campaign
        $sessions = $query->whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('utm_campaign')
            ->selectRaw('
                utm_campaign,
                utm_source,
                utm_medium,
                COUNT(DISTINCT session_id) as sessions,
                COUNT(DISTINCT ip_address) as visitors
            ')
            ->groupBy('utm_campaign', 'utm_source', 'utm_medium')
            ->get()
            ->keyBy('utm_campaign');

        // Get conversions by campaign
        $conversions = PaymentFunnelEvent::query()
            ->when($websiteId, fn($q) => $q->where('website_id', $websiteId))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('funnel_step', 'payment_completed')
            ->whereNotNull('utm_campaign')
            ->selectRaw('
                utm_campaign,
                COUNT(*) as conversions,
                SUM(amount) as revenue
            ')
            ->groupBy('utm_campaign')
            ->get()
            ->keyBy('utm_campaign');

        // Merge data
        $campaigns = $sessions->map(function($session) use ($conversions) {
            $conversion = $conversions->get($session->utm_campaign);
            $conversionCount = $conversion ? $conversion->conversions : 0;
            $revenue = $conversion ? ($conversion->revenue / 100) : 0;
            $conversionRate = $session->sessions > 0 ? ($conversionCount / $session->sessions) * 100 : 0;
            $costPerConversion = $conversionCount > 0 ? $revenue / $conversionCount : 0;

            return [
                'campaign' => $session->utm_campaign,
                'source' => $session->utm_source,
                'medium' => $session->utm_medium,
                'sessions' => (int) $session->sessions,
                'visitors' => (int) $session->visitors,
                'conversions' => $conversionCount,
                'revenue' => round($revenue, 2),
                'conversion_rate' => round($conversionRate, 2),
                'avg_revenue_per_session' => $session->sessions > 0 ? round($revenue / $session->sessions, 2) : 0,
                'cost_per_conversion' => round($costPerConversion, 2)
            ];
        })->sortByDesc('revenue')->values();

        return $campaigns;
    }

    /**
     * Get source and medium breakdown
     */
    protected function getSourceMediumBreakdown($websiteId, $startDate, $endDate)
    {
        $query = AnalyticsEvent::query();
        
        if ($websiteId) {
            $query->where('website_id', $websiteId);
        }
        
        // Get by source
        $sources = $query->whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('utm_source')
            ->selectRaw('
                utm_source as source,
                COUNT(DISTINCT session_id) as sessions,
                COUNT(DISTINCT ip_address) as visitors
            ')
            ->groupBy('utm_source')
            ->orderByDesc('sessions')
            ->limit(10)
            ->get();

        // Get conversions by source
        $sourceConversions = PaymentFunnelEvent::query()
            ->when($websiteId, fn($q) => $q->where('website_id', $websiteId))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('funnel_step', 'payment_completed')
            ->whereNotNull('utm_source')
            ->selectRaw('
                utm_source as source,
                COUNT(*) as conversions,
                SUM(amount) as revenue
            ')
            ->groupBy('utm_source')
            ->get()
            ->keyBy('source');

        // Merge source data
        $sourceData = $sources->map(function($source) use ($sourceConversions) {
            $conv = $sourceConversions->get($source->source);
            $conversions = $conv ? $conv->conversions : 0;
            $revenue = $conv ? ($conv->revenue / 100) : 0;
            
            return [
                'source' => $source->source,
                'sessions' => (int) $source->sessions,
                'visitors' => (int) $source->visitors,
                'conversions' => $conversions,
                'revenue' => round($revenue, 2),
                'conversion_rate' => $source->sessions > 0 ? round(($conversions / $source->sessions) * 100, 2) : 0
            ];
        });

        // Get by medium
        $mediums = PaymentFunnelEvent::query()
            ->when($websiteId, fn($q) => $q->where('website_id', $websiteId))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('utm_medium')
            ->selectRaw('
                utm_medium as medium,
                COUNT(DISTINCT session_id) as sessions
            ')
            ->groupBy('utm_medium')
            ->orderByDesc('sessions')
            ->get();

        return [
            'sources' => $sourceData,
            'mediums' => $mediums
        ];
    }

    /**
     * Get conversion attribution data
     */
    protected function getConversionAttribution($websiteId, $startDate, $endDate)
    {
        $conversions = PaymentFunnelEvent::query()
            ->when($websiteId, fn($q) => $q->where('website_id', $websiteId))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('funnel_step', 'payment_completed')
            ->whereNotNull('utm_source')
            ->selectRaw('
                utm_source,
                utm_medium,
                utm_campaign,
                COUNT(*) as conversions,
                SUM(amount) as revenue
            ')
            ->groupBy('utm_source', 'utm_medium', 'utm_campaign')
            ->orderByDesc('revenue')
            ->limit(20)
            ->get();

        return $conversions->map(function($conv) {
            return [
                'source' => $conv->utm_source,
                'medium' => $conv->utm_medium,
                'campaign' => $conv->utm_campaign,
                'conversions' => (int) $conv->conversions,
                'revenue' => round(($conv->revenue / 100), 2)
            ];
        });
    }

    /**
     * Get trending campaigns (comparing periods)
     */
    protected function getTrendingCampaigns($websiteId, $startDate, $endDate)
    {
        $days = $startDate->diffInDays($endDate);
        $previousStart = $startDate->copy()->subDays($days);
        $previousEnd = $startDate->copy()->subDay();

        // Current period
        $current = PaymentFunnelEvent::query()
            ->when($websiteId, fn($q) => $q->where('website_id', $websiteId))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('funnel_step', 'payment_completed')
            ->whereNotNull('utm_campaign')
            ->selectRaw('utm_campaign, COUNT(*) as conversions')
            ->groupBy('utm_campaign')
            ->get()
            ->keyBy('utm_campaign');

        // Previous period
        $previous = PaymentFunnelEvent::query()
            ->when($websiteId, fn($q) => $q->where('website_id', $websiteId))
            ->whereBetween('created_at', [$previousStart, $previousEnd])
            ->where('funnel_step', 'payment_completed')
            ->whereNotNull('utm_campaign')
            ->selectRaw('utm_campaign, COUNT(*) as conversions')
            ->groupBy('utm_campaign')
            ->get()
            ->keyBy('utm_campaign');

        // Calculate trends
        $trending = $current->map(function($curr) use ($previous) {
            $prev = $previous->get($curr->utm_campaign);
            $prevConversions = $prev ? $prev->conversions : 0;
            $growth = $prevConversions > 0 ? (($curr->conversions - $prevConversions) / $prevConversions) * 100 : 100;

            return [
                'campaign' => $curr->utm_campaign,
                'current_conversions' => (int) $curr->conversions,
                'previous_conversions' => $prevConversions,
                'growth_percentage' => round($growth, 1),
                'trending' => $growth > 0 ? 'up' : 'down'
            ];
        })->sortByDesc('growth_percentage')->take(5);

        return $trending->values();
    }

    /**
     * Export UTM data as CSV or Excel (WEBSITE-BASED)
     */
    public function export(Request $request)
    {
        $websiteId = $request->website_id ?? auth()->user()->website_id ?? null;
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->subDays(30);
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now();
        $format = $request->format ?? 'csv'; // csv or excel

        // Get WEBSITE-BASED data
        $campaigns = $this->getCampaignPerformance($websiteId, $startDate, $endDate);
        
        // Get website name for file naming (WEBSITE-BASED)
        $websiteName = $websiteId ? Website::find($websiteId)?->name : 'All_Websites';
        $websiteName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $websiteName);

        if ($format === 'excel') {
            // Excel export (WEBSITE-BASED)
            $excelService = new \App\Services\ExcelExportService();
            $spreadsheet = $excelService->exportUTMCampaigns($campaigns->toArray(), $websiteName);
            
            $filename = 'utm_analytics_' . $websiteName . '_' . now()->format('Y-m-d');
            return $excelService->generateAndDownload($spreadsheet, $filename);
        }

        // CSV export (WEBSITE-BASED)
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="utm_analytics_' . $websiteName . '_' . now()->format('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($campaigns) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, [
                'Campaign',
                'Source',
                'Medium',
                'Sessions',
                'Visitors',
                'Conversions',
                'Revenue',
                'Conversion Rate (%)',
                'Avg Revenue/Session',
                'Cost/Conversion'
            ]);
            
            // Data
            foreach ($campaigns as $campaign) {
                fputcsv($file, [
                    $campaign['campaign'],
                    $campaign['source'],
                    $campaign['medium'],
                    $campaign['sessions'],
                    $campaign['visitors'],
                    $campaign['conversions'],
                    '$' . number_format($campaign['revenue'], 2),
                    $campaign['conversion_rate'],
                    '$' . number_format($campaign['avg_revenue_per_session'], 2),
                    '$' . number_format($campaign['cost_per_conversion'], 2)
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
