<?php

namespace App\Services\Analytics;

use App\Models\AnalyticsEvent;
use Illuminate\Http\Request;

class EventTrackingService
{
    protected $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    public function trackPageView(Request $request)
    {
        return $this->analyticsService->recordEvent('page_view', [
            'page' => $request->path(),
            'query' => $request->query()
        ]);
    }

    public function trackConversion($data)
    {
        return $this->analyticsService->recordEvent('conversion', $data);
    }

    public function trackPaymentAttempt($data)
    {
        return $this->analyticsService->recordEvent('payment_attempt', $data);
    }

    public function trackPaymentSuccess($data)
    {
        return $this->analyticsService->recordEvent('payment_success', $data);
    }

    public function trackPaymentFailure($data)
    {
        return $this->analyticsService->recordEvent('payment_failure', $data);
    }

    public function trackDonation($data)
    {
        return $this->analyticsService->recordEvent('donation', $data);
    }

    public function trackAuctionBid($data)
    {
        return $this->analyticsService->recordEvent('auction_bid', $data);
    }

    public function trackUserRegistration($data)
    {
        return $this->analyticsService->recordEvent('user_registration', $data);
    }

    public function trackSearch($data)
    {
        return $this->analyticsService->recordEvent('search', $data);
    }

    public function trackError($data)
    {
        return $this->analyticsService->recordEvent('error', $data);
    }
}