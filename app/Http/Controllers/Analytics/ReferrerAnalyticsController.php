<?php

namespace App\Http\Controllers\Analytics;

use App\Http\Controllers\Controller;
use App\Models\PaymentFunnelEvent;
use App\Models\AnalyticsEvent;
use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReferrerAnalyticsController extends Controller
{
    /**
     * Display referrer analytics dashboard
     */
    public function index(Request $request)
    {
        $websiteId = $request->website_id ?? auth()->user()->website_id ?? null;
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->subDays(30);
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now();

        // Get all websites for filter (WEBSITE-BASED)
        $websites = Website::all();
        
        // Get referrer statistics (WEBSITE-BASED)
        $stats = $this->getReferrerStats($websiteId, $startDate, $endDate);
        
        // Get top referrers (WEBSITE-BASED)
        $topReferrers = $this->getTopReferrers($websiteId, $startDate, $endDate);
        
        // Get referrer performance (WEBSITE-BASED)
        $performance = $this->getReferrerPerformance($websiteId, $startDate, $endDate);
        
        // Get traffic sources breakdown (WEBSITE-BASED)
        $sources = $this->getTrafficSourcesBreakdown($websiteId, $startDate, $endDate);
        
        // Get conversion by referrer type (WEBSITE-BASED)
        $conversionByType = $this->getConversionByType($websiteId, $startDate, $endDate);

        return view('analytics.referrer', compact(
            'websites',
            'websiteId',
            'startDate',
            'endDate',
            'stats',
            'topReferrers',
            'performance',
            'sources',
            'conversionByType'
        ));
    }

    /**
     * Get referrer statistics summary (WEBSITE-BASED)
     */
    protected function getReferrerStats($websiteId, $startDate, $endDate)
    {
        $query = PaymentFunnelEvent::query();
        
        // WEBSITE-BASED FILTER
        if ($websiteId) {
            $query->where('website_id', $websiteId);
        }
        
        $query->whereBetween('created_at', [$startDate, $endDate]);

        // Total sessions with referrer
        $totalWithReferrer = (clone $query)
            ->whereNotNull('referrer_url')
            ->where('referrer_url', '!=', '')
            ->distinct('session_id')
            ->count('session_id');

        // Direct traffic (no referrer)
        $directTraffic = (clone $query)
            ->where(function($q) {
                $q->whereNull('referrer_url')
                  ->orWhere('referrer_url', '');
            })
            ->distinct('session_id')
            ->count('session_id');

        // Total sessions
        $totalSessions = (clone $query)
            ->distinct('session_id')
            ->count('session_id');

        // Referrer-attributed conversions (WEBSITE-BASED)
        $referrerConversions = (clone $query)
            ->where('funnel_step', 'payment_completed')
            ->whereNotNull('referrer_url')
            ->where('referrer_url', '!=', '')
            ->count();

        // Referrer-attributed revenue (WEBSITE-BASED)
        $referrerRevenue = (clone $query)
            ->where('funnel_step', 'payment_completed')
            ->whereNotNull('referrer_url')
            ->where('referrer_url', '!=', '')
            ->sum('amount') ?? 0;

        // Unique referrer domains (WEBSITE-BASED)
        $uniqueReferrers = (clone $query)
            ->whereNotNull('referrer_url')
            ->where('referrer_url', '!=', '')
            ->selectRaw('COUNT(DISTINCT SUBSTRING_INDEX(SUBSTRING_INDEX(referrer_url, "://", -1), "/", 1)) as count')
            ->value('count') ?? 0;

        return [
            'total_with_referrer' => $totalWithReferrer,
            'direct_traffic' => $directTraffic,
            'total_sessions' => $totalSessions,
            'referrer_percentage' => $totalSessions > 0 ? round(($totalWithReferrer / $totalSessions) * 100, 1) : 0,
            'referrer_conversions' => $referrerConversions,
            'referrer_revenue' => $referrerRevenue / 100,
            'unique_referrers' => $uniqueReferrers,
            'avg_revenue_per_conversion' => $referrerConversions > 0 ? ($referrerRevenue / 100) / $referrerConversions : 0
        ];
    }

    /**
     * Get top referrers (WEBSITE-BASED)
     */
    protected function getTopReferrers($websiteId, $startDate, $endDate)
    {
        $query = PaymentFunnelEvent::query();
        
        // WEBSITE-BASED FILTER
        if ($websiteId) {
            $query->where('website_id', $websiteId);
        }
        
        $referrers = $query->whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('referrer_url')
            ->where('referrer_url', '!=', '')
            ->selectRaw('
                referrer_url,
                COUNT(DISTINCT session_id) as sessions,
                COUNT(DISTINCT visitor_id) as visitors
            ')
            ->groupBy('referrer_url')
            ->orderByDesc('sessions')
            ->limit(20)
            ->get();

        // Get conversions per referrer (WEBSITE-BASED)
        $conversions = PaymentFunnelEvent::query()
            ->when($websiteId, fn($q) => $q->where('website_id', $websiteId))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('funnel_step', 'payment_completed')
            ->whereNotNull('referrer_url')
            ->where('referrer_url', '!=', '')
            ->selectRaw('
                referrer_url,
                COUNT(*) as conversions,
                SUM(amount) as revenue
            ')
            ->groupBy('referrer_url')
            ->get()
            ->keyBy('referrer_url');

        // Merge data
        return $referrers->map(function($referrer) use ($conversions) {
            $conv = $conversions->get($referrer->referrer_url);
            $conversionCount = $conv ? $conv->conversions : 0;
            $revenue = $conv ? ($conv->revenue / 100) : 0;
            
            // Extract domain from URL
            $domain = $this->extractDomain($referrer->referrer_url);
            
            return [
                'referrer_url' => $referrer->referrer_url,
                'domain' => $domain,
                'sessions' => (int) $referrer->sessions,
                'visitors' => (int) $referrer->visitors,
                'conversions' => $conversionCount,
                'revenue' => round($revenue, 2),
                'conversion_rate' => $referrer->sessions > 0 ? round(($conversionCount / $referrer->sessions) * 100, 2) : 0,
                'avg_revenue_per_session' => $referrer->sessions > 0 ? round($revenue / $referrer->sessions, 2) : 0
            ];
        });
    }

    /**
     * Get referrer performance by domain (WEBSITE-BASED)
     */
    protected function getReferrerPerformance($websiteId, $startDate, $endDate)
    {
        $query = PaymentFunnelEvent::query();
        
        // WEBSITE-BASED FILTER
        if ($websiteId) {
            $query->where('website_id', $websiteId);
        }
        
        // Get all referrers grouped by domain
        $referrers = $query->whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('referrer_url')
            ->where('referrer_url', '!=', '')
            ->get()
            ->groupBy(function($item) {
                return $this->extractDomain($item->referrer_url);
            })
            ->map(function($group, $domain) {
                return [
                    'domain' => $domain,
                    'sessions' => $group->unique('session_id')->count(),
                    'visitors' => $group->unique('visitor_id')->count()
                ];
            });

        // Get conversions by domain (WEBSITE-BASED)
        $conversionQuery = PaymentFunnelEvent::query()
            ->when($websiteId, fn($q) => $q->where('website_id', $websiteId))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('funnel_step', 'payment_completed')
            ->whereNotNull('referrer_url')
            ->where('referrer_url', '!=', '')
            ->get()
            ->groupBy(function($item) {
                return $this->extractDomain($item->referrer_url);
            })
            ->map(function($group) {
                return [
                    'conversions' => $group->count(),
                    'revenue' => $group->sum('amount') / 100
                ];
            });

        // Merge data
        return $referrers->map(function($referrer) use ($conversionQuery) {
            $conv = $conversionQuery->get($referrer['domain']) ?? ['conversions' => 0, 'revenue' => 0];
            
            return [
                'domain' => $referrer['domain'],
                'sessions' => $referrer['sessions'],
                'visitors' => $referrer['visitors'],
                'conversions' => $conv['conversions'],
                'revenue' => round($conv['revenue'], 2),
                'conversion_rate' => $referrer['sessions'] > 0 ? round(($conv['conversions'] / $referrer['sessions']) * 100, 2) : 0
            ];
        })->sortByDesc('revenue')->take(15)->values();
    }

    /**
     * Get traffic sources breakdown (WEBSITE-BASED)
     */
    protected function getTrafficSourcesBreakdown($websiteId, $startDate, $endDate)
    {
        $query = PaymentFunnelEvent::query();
        
        // WEBSITE-BASED FILTER
        if ($websiteId) {
            $query->where('website_id', $websiteId);
        }
        
        $data = $query->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->map(function($event) {
                return [
                    'session_id' => $event->session_id,
                    'type' => $this->categorizeTrafficSource($event),
                    'is_conversion' => $event->funnel_step === 'payment_completed',
                    'amount' => $event->amount ?? 0
                ];
            })
            ->groupBy('type')
            ->map(function($group, $type) {
                return [
                    'type' => $type,
                    'sessions' => $group->unique('session_id')->count(),
                    'conversions' => $group->where('is_conversion', true)->count(),
                    'revenue' => $group->where('is_conversion', true)->sum('amount') / 100
                ];
            })
            ->values();

        return $data;
    }

    /**
     * Get conversion rates by referrer type (WEBSITE-BASED)
     */
    protected function getConversionByType($websiteId, $startDate, $endDate)
    {
        $sources = $this->getTrafficSourcesBreakdown($websiteId, $startDate, $endDate);
        
        return $sources->map(function($source) {
            return [
                'type' => $source['type'],
                'sessions' => $source['sessions'],
                'conversions' => $source['conversions'],
                'conversion_rate' => $source['sessions'] > 0 ? round(($source['conversions'] / $source['sessions']) * 100, 2) : 0,
                'revenue' => $source['revenue'],
                'avg_order_value' => $source['conversions'] > 0 ? round($source['revenue'] / $source['conversions'], 2) : 0
            ];
        })->sortByDesc('sessions')->values();
    }

    /**
     * Export referrer data as CSV or Excel (WEBSITE-BASED)
     */
    public function export(Request $request)
    {
        $websiteId = $request->website_id ?? auth()->user()->website_id ?? null;
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->subDays(30);
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now();
        $format = $request->format ?? 'csv'; // csv or excel

        // WEBSITE-BASED data
        $referrers = $this->getTopReferrers($websiteId, $startDate, $endDate);
        
        // Get website name for file naming (WEBSITE-BASED)
        $websiteName = $websiteId ? Website::find($websiteId)?->name : 'All_Websites';
        $websiteName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $websiteName);

        if ($format === 'excel') {
            // Excel export (WEBSITE-BASED)
            $excelService = new \App\Services\ExcelExportService();
            $spreadsheet = $excelService->exportReferrers($referrers->toArray(), $websiteName);
            
            $filename = 'referrer_analytics_' . $websiteName . '_' . now()->format('Y-m-d');
            return $excelService->generateAndDownload($spreadsheet, $filename);
        }

        // CSV export (WEBSITE-BASED)
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="referrer_analytics_' . $websiteName . '_' . now()->format('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($referrers) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, [
                'Referrer URL',
                'Domain',
                'Sessions',
                'Visitors',
                'Conversions',
                'Revenue',
                'Conversion Rate (%)',
                'Avg Revenue/Session'
            ]);
            
            // Data
            foreach ($referrers as $referrer) {
                fputcsv($file, [
                    $referrer['referrer_url'],
                    $referrer['domain'],
                    $referrer['sessions'],
                    $referrer['visitors'],
                    $referrer['conversions'],
                    '$' . number_format($referrer['revenue'], 2),
                    $referrer['conversion_rate'],
                    '$' . number_format($referrer['avg_revenue_per_session'], 2)
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Helper: Extract domain from URL
     */
    protected function extractDomain($url)
    {
        if (empty($url)) {
            return 'Direct';
        }
        
        $parsed = parse_url($url);
        $host = $parsed['host'] ?? '';
        
        // Remove www. prefix
        return preg_replace('/^www\./i', '', $host);
    }

    /**
     * Helper: Categorize traffic source (WEBSITE-BASED)
     */
    protected function categorizeTrafficSource($event)
    {
        // Has UTM parameters
        if (!empty($event->utm_source)) {
            return 'Campaign (' . $event->utm_source . ')';
        }
        
        // Has referrer
        if (!empty($event->referrer_url)) {
            $domain = $this->extractDomain($event->referrer_url);
            
            // Social media
            if (preg_match('/(facebook|instagram|twitter|linkedin|youtube|tiktok|pinterest)/i', $domain)) {
                return 'Social Media';
            }
            
            // Search engines
            if (preg_match('/(google|bing|yahoo|duckduckgo|baidu)/i', $domain)) {
                return 'Search Engine';
            }
            
            return 'Referral';
        }
        
        // No referrer or UTM
        return 'Direct';
    }
}
