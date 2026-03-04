<?php

namespace App\Services;

use App\Models\AnalyticsEvent;
use App\Models\PaymentFunnelEvent;
use App\Models\Ticket;
use App\Models\Investment;
use App\Models\Donation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsChartService
{
    /**
     * Get time-based conversion data for charts using REAL data
     */
    public function getTimeBasedConversions($websiteId, $startDate, $endDate, $groupBy = 'day')
    {
        $selectFormat = $this->getSelectFormat($groupBy);
        
        // Get conversions from both AnalyticsEvent and PaymentFunnelEvent
        $analyticsConversions = AnalyticsEvent::where('event_type', 'conversion')
            ->where('website_id', $websiteId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw("$selectFormat as period, COUNT(*) as conversions, SUM(conversion_value) as revenue")
            ->groupBy('period')
            ->get();
            
        $paymentConversions = PaymentFunnelEvent::where('funnel_step', 'payment_completed')
            ->where('website_id', $websiteId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw("$selectFormat as period, COUNT(*) as conversions, SUM(amount) as revenue")
            ->groupBy('period')
            ->get();
            
        // Merge the results
        $combined = collect();
        $allPeriods = $analyticsConversions->pluck('period')->merge($paymentConversions->pluck('period'))->unique();
        
        foreach ($allPeriods as $period) {
            $analytics = $analyticsConversions->where('period', $period)->first();
            $payment = $paymentConversions->where('period', $period)->first();
            
            $combined->push([
                'period' => $this->formatPeriodLabel($period, $groupBy),
                'conversions' => ($analytics->conversions ?? 0) + ($payment->conversions ?? 0),
                'revenue' => (($analytics->revenue ?? 0) + (($payment->revenue ?? 0) / 100))
            ]);
        }
        
        return $combined->sortBy('period')->values();
    }

    /**
     * Get time-based sessions data using REAL analytics data
     */
    public function getTimeBasedSessions($websiteId, $startDate, $endDate, $groupBy = 'day')
    {
        $selectFormat = $this->getSelectFormat($groupBy);
        
        return AnalyticsEvent::where('event_type', 'page_view')
            ->where('website_id', $websiteId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw("$selectFormat as period, COUNT(DISTINCT session_id) as sessions, COUNT(*) as page_views, COUNT(DISTINCT session_id) as unique_visitors")
            ->groupBy('period')
            ->orderBy('period')
            ->get()
            ->map(function ($item) use ($groupBy) {
                return [
                    'period' => $this->formatPeriodLabel($item->period, $groupBy),
                    'sessions' => (int) $item->sessions,
                    'page_views' => (int) $item->page_views,
                    'unique_visitors' => (int) $item->unique_visitors
                ];
            });
    }

    /**
     * Get conversion funnel breakdown using PaymentFunnelEvent data
     */
    public function getConversionFunnelData($websiteId, $startDate, $endDate)
    {
        // Get unique sessions (representing total website visits) from PaymentFunnelEvent
        $totalSessions = PaymentFunnelEvent::where('website_id', $websiteId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->distinct('session_id')
            ->count('session_id');

        // Get PaymentFunnelEvent steps
        $formViews = PaymentFunnelEvent::where('website_id', $websiteId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('funnel_step', 'form_view')
            ->distinct('session_id')
            ->count();
            
        $amountEntered = PaymentFunnelEvent::where('website_id', $websiteId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('funnel_step', 'amount_entered')
            ->distinct('session_id')
            ->count();
            
        $personalInfoStarted = PaymentFunnelEvent::where('website_id', $websiteId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('funnel_step', 'personal_info_started')
            ->distinct('session_id')
            ->count();
            
        $personalInfoCompleted = PaymentFunnelEvent::where('website_id', $websiteId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('funnel_step', 'personal_info_completed')
            ->distinct('session_id')
            ->count();
            
        $paymentInitiated = PaymentFunnelEvent::where('website_id', $websiteId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('funnel_step', 'payment_initiated')
            ->distinct('session_id')
            ->count();
            
        $paymentCompleted = PaymentFunnelEvent::where('website_id', $websiteId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('funnel_step', 'payment_completed')
            ->distinct('session_id')
            ->count();

        $funnelSteps = [
            'Sessions' => $totalSessions,
            'Form Views' => $formViews,
            'Amount Entered' => $amountEntered,
            'Personal Info Started' => $personalInfoStarted,
            'Personal Info Completed' => $personalInfoCompleted,
            'Payment Page' => $paymentInitiated,
            'Completed Conversions' => $paymentCompleted
        ];

        // Calculate conversion rates
        $funnelData = [];
        $previousStep = $totalSessions;
        
        foreach ($funnelSteps as $stepName => $count) {
            $conversionRate = $totalSessions > 0 ? ($count / $totalSessions) * 100 : 0;
            $dropoffRate = $previousStep > 0 ? (($previousStep - $count) / $previousStep) * 100 : 0;
            
            $funnelData[] = [
                'step' => $stepName,
                'count' => $count,
                'conversion_rate' => round($conversionRate, 2),
                'dropoff_rate' => round($dropoffRate, 2)
            ];
            
            $previousStep = $count;
        }

        return $funnelData;
    }

    /**
     * Get device type breakdown using PaymentFunnelEvent data
     */
    public function getDeviceBreakdown($websiteId, $startDate, $endDate)
    {
        // Get device data from PaymentFunnelEvent
        $deviceData = PaymentFunnelEvent::where('website_id', $websiteId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('device_type')
            ->selectRaw('device_type, COUNT(DISTINCT session_id) as visitors')
            ->groupBy('device_type')
            ->get();
            
        // Get conversions by device from PaymentFunnelEvent
        $deviceConversions = PaymentFunnelEvent::where('funnel_step', 'payment_completed')
            ->where('website_id', $websiteId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('device_type')
            ->selectRaw('device_type, COUNT(*) as conversions, SUM(amount) as revenue')
            ->groupBy('device_type')
            ->get()
            ->keyBy('device_type');

        return $deviceData->map(function ($item) use ($deviceConversions) {
            $conversions = $deviceConversions->get($item->device_type);
            $conversionRate = $item->visitors > 0 ? (($conversions->conversions ?? 0) / $item->visitors) * 100 : 0;
            
            return [
                'device_type' => $item->device_type ?: 'Unknown',
                'visitors' => (int) $item->visitors,
                'conversions' => (int) ($conversions->conversions ?? 0),
                'revenue' => (float) ($conversions->revenue ?? 0),
                'conversion_rate' => round($conversionRate, 2)
            ];
        });
    }

    /**
     * Get location breakdown using PaymentFunnelEvent data
     */
    public function getLocationBreakdown($websiteId, $startDate, $endDate)
    {
        // Get completion step
        $completionStep = $this->getCompletionFunnelStep();
        
        // Get location data from PaymentFunnelEvent grouped by country/state
        $locationData = PaymentFunnelEvent::where('website_id', $websiteId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('country_code')
            ->selectRaw('
                country_code,
                country,
                state,
                COUNT(DISTINCT COALESCE(visitor_id, session_id)) as visitors,
                COUNT(DISTINCT session_id) as sessions
            ')
            ->groupBy('country_code', 'country', 'state')
            ->orderByDesc('visitors')
            ->limit(20)
            ->get();

        // Get conversions by location from PaymentFunnelEvent
        $locationConversions = PaymentFunnelEvent::where('funnel_step', $completionStep)
            ->where('website_id', $websiteId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('country_code')
            ->selectRaw('
                country_code,
                state,
                COUNT(*) as conversions,
                SUM(amount) as revenue
            ')
            ->groupBy('country_code', 'state')
            ->get()
            ->mapWithKeys(function($item) {
                $key = $item->country_code . ($item->state ? '_' . $item->state : '');
                return [$key => $item];
            });

        return $locationData->map(function ($item) use ($locationConversions) {
            $key = $item->country_code . ($item->state ? '_' . $item->state : '');
            $conversions = $locationConversions->get($key);
            $conversionRate = $item->sessions > 0 ? (($conversions->conversions ?? 0) / $item->sessions) * 100 : 0;
            
            $location = $item->state 
                ? ($item->state . ', ' . ($item->country ?: $item->country_code))
                : ($item->country ?: $item->country_code);
            
            return [
                'country' => $item->country_code,
                'country_name' => $location,
                'visitors' => (int) $item->visitors,
                'sessions' => (int) $item->sessions,
                'conversions' => (int) ($conversions->conversions ?? 0),
                'revenue' => (float) ($conversions->revenue ?? 0),
                'conversion_rate' => round($conversionRate, 2)
            ];
        });
    }
    
    protected function getCompletionFunnelStep()
    {
        $possibleSteps = ['payment_completed', 'payment_complete', 'completed', 'payment_success', 'success'];
        
        foreach ($possibleSteps as $step) {
            $count = PaymentFunnelEvent::where('funnel_step', $step)->count();
            if ($count > 0) {
                return $step;
            }
        }
        
        return 'payment_completed'; // fallback
    }

    /**
     * Get product sell-through rates using REAL data
     */
    public function getProductSellThroughRates($websiteId, $startDate, $endDate)
    {
        $results = collect();
        
        // Get REAL ticket data
        $tickets = Ticket::where('website_id', $websiteId)->get();
        foreach ($tickets as $ticket) {
            // Count actual completed payments for this ticket
            $sold = PaymentFunnelEvent::where('website_id', $websiteId)
                ->where('form_type', 'ticket')
                ->where('funnel_step', 'payment_completed')
                ->where('form_data->ticket_id', $ticket->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();
                
            $revenue = PaymentFunnelEvent::where('website_id', $websiteId)
                ->where('form_type', 'ticket')
                ->where('funnel_step', 'payment_completed')
                ->where('form_data->ticket_id', $ticket->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('amount');
                
            $sellThroughRate = $ticket->quantity > 0 ? ($sold / $ticket->quantity) * 100 : 0;
            
            $results->push([
                'type' => 'ticket',
                'id' => $ticket->id,
                'name' => $ticket->name,
                'price' => (float) $ticket->price,
                'available' => (int) $ticket->quantity,
                'sold' => (int) $sold,
                'remaining' => (int) ($ticket->quantity - $sold),
                'sell_through_rate' => round($sellThroughRate, 2),
                'revenue' => (float) ($revenue ?? 0) / 100
            ]);
        }

        // Get REAL donation data
        $donationViews = PaymentFunnelEvent::where('website_id', $websiteId)
            ->where('form_type', 'donation')
            ->where('funnel_step', 'form_view')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
            
        $donationCompleted = PaymentFunnelEvent::where('website_id', $websiteId)
            ->where('form_type', 'donation')
            ->where('funnel_step', 'payment_completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
            
        $donationRevenue = PaymentFunnelEvent::where('website_id', $websiteId)
            ->where('form_type', 'donation')
            ->where('funnel_step', 'payment_completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');
            
        if ($donationViews > 0) {
            $donationRate = ($donationCompleted / $donationViews) * 100;
            $results->push([
                'type' => 'donation',
                'id' => 0,
                'name' => 'Donations',
                'price' => $donationCompleted > 0 ? (float) ($donationRevenue / $donationCompleted) / 100 : 0,
                'available' => $donationViews,
                'sold' => $donationCompleted,
                'remaining' => $donationViews - $donationCompleted,
                'sell_through_rate' => round($donationRate, 2),
                'revenue' => (float) ($donationRevenue ?? 0) / 100
            ]);
        }
        
        // Get REAL investment data  
        $investmentViews = PaymentFunnelEvent::where('website_id', $websiteId)
            ->where('form_type', 'investment')
            ->where('funnel_step', 'form_view')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
            
        $investmentCompleted = PaymentFunnelEvent::where('website_id', $websiteId)
            ->where('form_type', 'investment')
            ->where('funnel_step', 'payment_completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
            
        $investmentRevenue = PaymentFunnelEvent::where('website_id', $websiteId)
            ->where('form_type', 'investment')
            ->where('funnel_step', 'payment_completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');
            
        if ($investmentViews > 0) {
            $investmentRate = ($investmentCompleted / $investmentViews) * 100;
            $results->push([
                'type' => 'investment',
                'id' => 0,
                'name' => 'Investments',
                'price' => $investmentCompleted > 0 ? (float) ($investmentRevenue / $investmentCompleted) / 100 : 0,
                'available' => $investmentViews,
                'sold' => $investmentCompleted,
                'remaining' => $investmentViews - $investmentCompleted,
                'sell_through_rate' => round($investmentRate, 2),
                'revenue' => (float) ($investmentRevenue ?? 0) / 100
            ]);
        }

        return $results;
    }

    /**
     * Get geographic data for mapping using REAL data
     */
    public function getGeoMapData($websiteId, $startDate, $endDate)
    {
        // Get completion step
        $completionStep = $this->getCompletionFunnelStep();
        
        // Get real location data from PaymentFunnelEvent grouped by country
        $locationData = PaymentFunnelEvent::where('website_id', $websiteId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('country_code')
            ->selectRaw('
                country_code,
                country,
                COUNT(DISTINCT COALESCE(visitor_id, session_id)) as visitors,
                COUNT(DISTINCT session_id) as sessions
            ')
            ->groupBy('country_code', 'country')
            ->get();

        // Get conversions by country
        $locationConversions = PaymentFunnelEvent::where('funnel_step', $completionStep)
            ->where('website_id', $websiteId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('country_code')
            ->selectRaw('
                country_code,
                COUNT(*) as conversions,
                SUM(amount) as revenue
            ')
            ->groupBy('country_code')
            ->get()
            ->keyBy('country_code');

        // Country coordinates mapping
        $coordinates = [
            'US' => ['lat' => 39.8283, 'lng' => -98.5795, 'name' => 'United States'],
            'CA' => ['lat' => 56.1304, 'lng' => -106.3468, 'name' => 'Canada'],
            'GB' => ['lat' => 55.3781, 'lng' => -3.436, 'name' => 'United Kingdom'],
            'AU' => ['lat' => -25.2744, 'lng' => 133.7751, 'name' => 'Australia'],
            'DE' => ['lat' => 51.1657, 'lng' => 10.4515, 'name' => 'Germany'],
            'FR' => ['lat' => 46.2276, 'lng' => 2.2137, 'name' => 'France'],
            'IN' => ['lat' => 20.5937, 'lng' => 78.9629, 'name' => 'India'],
            'MX' => ['lat' => 23.6345, 'lng' => -102.5528, 'name' => 'Mexico'],
            'BR' => ['lat' => -14.2350, 'lng' => -51.9253, 'name' => 'Brazil'],
            'JP' => ['lat' => 36.2048, 'lng' => 138.2529, 'name' => 'Japan'],
            'IT' => ['lat' => 41.8719, 'lng' => 12.5674, 'name' => 'Italy'],
            'ES' => ['lat' => 40.4637, 'lng' => -3.7492, 'name' => 'Spain'],
            'NL' => ['lat' => 52.1326, 'lng' => 5.2913, 'name' => 'Netherlands'],
            'SE' => ['lat' => 60.1282, 'lng' => 18.6435, 'name' => 'Sweden'],
            'CH' => ['lat' => 46.8182, 'lng' => 8.2275, 'name' => 'Switzerland'],
        ];

        $result = $locationData->map(function ($item) use ($locationConversions, $coordinates) {
            $conversions = $locationConversions->get($item->country_code);
            $conversionRate = $item->sessions > 0 ? (($conversions->conversions ?? 0) / $item->sessions) * 100 : 0;
            
            // Get coordinates or use default
            $coords = $coordinates[$item->country_code] ?? ['lat' => 0, 'lng' => 0, 'name' => $item->country ?: $item->country_code];
            
            return [
                'country_code' => $item->country_code,
                'country_name' => $item->country ?: $coords['name'],
                'lat' => $coords['lat'],
                'lng' => $coords['lng'],
                'visitors' => (int) $item->visitors,
                'sessions' => (int) $item->sessions,
                'conversions' => (int) ($conversions->conversions ?? 0),
                'revenue' => (float) ($conversions->revenue ?? 0),
                'conversion_rate' => round($conversionRate, 2)
            ];
        });
        
        // Only return countries with visitors
        return $result->filter(function ($item) {
            return $item['visitors'] > 0;
        })->values();
    }

    /**
     * Helper methods
     */
    protected function getDateFormat($groupBy)
    {
        switch ($groupBy) {
            case 'hour':
                return '%Y-%m-%d %H:00:00';
            case 'day':
                return '%Y-%m-%d';
            case 'week':
                return '%Y-%u';
            case 'month':
                return '%Y-%m';
            case 'year':
                return '%Y';
            default:
                return '%Y-%m-%d';
        }
    }

    protected function getSelectFormat($groupBy)
    {
        switch ($groupBy) {
            case 'hour':
                return "DATE_FORMAT(created_at, '%Y-%m-%d %H:00:00')";
            case 'day':
                return "DATE_FORMAT(created_at, '%Y-%m-%d')";
            case 'week':
                return "DATE_FORMAT(created_at, '%Y-%u')";
            case 'month':
                return "DATE_FORMAT(created_at, '%Y-%m')";
            case 'year':
                return "DATE_FORMAT(created_at, '%Y')";
            default:
                return "DATE_FORMAT(created_at, '%Y-%m-%d')";
        }
    }

    protected function formatPeriodLabel($period, $groupBy)
    {
        switch ($groupBy) {
            case 'hour':
                return Carbon::parse($period)->format('M j, g A');
            case 'day':
                return Carbon::parse($period)->format('M j');
            case 'week':
                $parts = explode('-', $period);
                return 'Week ' . $parts[1] . ', ' . $parts[0];
            case 'month':
                return Carbon::parse($period . '-01')->format('M Y');
            case 'year':
                return $period;
            default:
                return Carbon::parse($period)->format('M j');
        }
    }

    protected function getCountryName($countryCode)
    {
        $countries = [
            'US' => 'United States',
            'CA' => 'Canada',
            'GB' => 'United Kingdom',
            'AU' => 'Australia',
            'DE' => 'Germany',
            'FR' => 'France',
            'IT' => 'Italy',
            'ES' => 'Spain',
            'JP' => 'Japan',
            'CN' => 'China',
            'IN' => 'India',
            'BR' => 'Brazil',
            'MX' => 'Mexico',
            'RU' => 'Russia',
            'NL' => 'Netherlands',
            'SE' => 'Sweden',
            'NO' => 'Norway',
            'DK' => 'Denmark',
            'FI' => 'Finland',
            'CH' => 'Switzerland'
        ];

        return $countries[$countryCode] ?? $countryCode;
    }

    protected function getCountryCoordinates($countryCode)
    {
        $coordinates = [
            'US' => ['lat' => 39.8283, 'lng' => -98.5795],
            'CA' => ['lat' => 56.1304, 'lng' => -106.3468],
            'GB' => ['lat' => 55.3781, 'lng' => -3.4360],
            'AU' => ['lat' => -25.2744, 'lng' => 133.7751],
            'DE' => ['lat' => 51.1657, 'lng' => 10.4515],
            'FR' => ['lat' => 46.2276, 'lng' => 2.2137],
            'IT' => ['lat' => 41.8719, 'lng' => 12.5674],
            'ES' => ['lat' => 40.4637, 'lng' => -3.7492],
            'JP' => ['lat' => 36.2048, 'lng' => 138.2529],
            'CN' => ['lat' => 35.8617, 'lng' => 104.1954],
            'IN' => ['lat' => 20.5937, 'lng' => 78.9629],
            'BR' => ['lat' => -14.2350, 'lng' => -51.9253],
            'MX' => ['lat' => 23.6345, 'lng' => -102.5528],
            'RU' => ['lat' => 61.5240, 'lng' => 105.3188],
            'NL' => ['lat' => 52.1326, 'lng' => 5.2913],
            'SE' => ['lat' => 60.1282, 'lng' => 18.6435],
            'NO' => ['lat' => 60.4720, 'lng' => 8.4689],
            'DK' => ['lat' => 56.2639, 'lng' => 9.5018],
            'FI' => ['lat' => 61.9241, 'lng' => 25.7482],
            'CH' => ['lat' => 46.8182, 'lng' => 8.2275]
        ];

        return $coordinates[$countryCode] ?? ['lat' => 0, 'lng' => 0];
    }
}
