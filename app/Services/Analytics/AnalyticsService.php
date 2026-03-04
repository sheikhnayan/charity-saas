<?php

namespace App\Services\Analytics;

use App\Models\AnalyticsEvent;
use App\Models\UserSession;
use Illuminate\Support\Facades\Cache;
use Jenssegers\Agent\Agent;

class AnalyticsService
{
    protected $agent;
    protected $sessionDuration = 1800; // 30 minutes in seconds

    public function __construct()
    {
        $this->agent = new Agent();
    }

    public function startSession()
    {
        $session = new UserSession();
        $session->session_id = session()->getId();
        $session->user_id = auth()->id();
        $session->user_agent = request()->userAgent();
        $session->ip_address = request()->ip();
        
        // UTM Parameters
        $session->utm_source = request()->get('utm_source');
        $session->utm_medium = request()->get('utm_medium');
        $session->utm_campaign = request()->get('utm_campaign');
        
        // Device & Browser Info
        $session->device_info = [
            'device' => $this->agent->device(),
            'platform' => $this->agent->platform(),
            'platform_version' => $this->agent->version($this->agent->platform())
        ];
        
        $session->browser_info = [
            'browser' => $this->agent->browser(),
            'browser_version' => $this->agent->version($this->agent->browser())
        ];
        
        $session->start_time = now();
        $session->last_activity = now();
        $session->save();
        
        return $session;
    }

    public function trackResponse(Request $request, Response $response)
    {
        if (!session()->has('analytics_session_id')) {
            return;
        }

        $sessionId = session('analytics_session_id');
        $session = UserSession::where('session_id', $sessionId)->first();
        
        if ($session) {
            $session->last_activity = now();
            $session->save();

            // Record response metrics
            $this->recordEvent('response_metrics', [
                'status_code' => $response->getStatusCode(),
                'response_time' => defined('LARAVEL_START') ? (microtime(true) - LARAVEL_START) * 1000 : null,
                'content_type' => $response->headers->get('Content-Type'),
                'content_length' => $response->headers->get('Content-Length')
            ]);
        }
    }

    public function recordEvent($eventType, $data = [])
    {
        $event = new AnalyticsEvent();
        $event->event_type = $eventType;
        $event->user_id = auth()->id();
        $event->session_id = session()->getId();
        $event->url = request()->fullUrl();
        
        // UTM Parameters
        $event->utm_source = request()->get('utm_source');
        $event->utm_medium = request()->get('utm_medium');
        $event->utm_campaign = request()->get('utm_campaign');

        // Device & Browser Info
        $event->device_info = [
            'device' => $this->agent->device(),
            'platform' => $this->agent->platform(),
            'platform_version' => $this->agent->version($this->agent->platform())
        ];

        $event->browser_info = [
            'browser' => $this->agent->browser(),
            'browser_version' => $this->agent->version($this->agent->browser())
        ];

        // Location Data (from IP)
        $event->location_data = [
            'ip' => request()->ip(),
            // Add more location data as needed
        ];

        $event->referrer_url = request()->headers->get('referer');
        $event->data = $data;
        
        $event->save();
        
        return $event;
        $event->conversion_data = $data;

        $event->save();

        return $event;
    }

    public function startSession()
    {
        $session = new UserSession();
        $session->session_id = session()->getId();
        $session->user_id = auth()->id();
        $session->start_time = now();
        $session->device_type = $this->agent->device();
        $session->browser = $this->agent->browser();
        $session->ip_address = request()->ip();
        $session->initial_referrer = request()->headers->get('referer');
        $session->landing_page = request()->fullUrl();
        $session->utm_parameters = [
            'source' => request()->get('utm_source'),
            'medium' => request()->get('utm_medium'),
            'campaign' => request()->get('utm_campaign')
        ];
        
        $session->save();

        return $session;
    }

    public function endSession($sessionId)
    {
        $session = UserSession::where('session_id', $sessionId)->first();
        if ($session) {
            $session->end_time = now();
            $session->save();
        }
    }

    public function getPageViews($startDate = null, $endDate = null)
    {
        $query = AnalyticsEvent::where('event_type', 'page_view');

        if ($startDate) {
            $query->where('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('created_at', '<=', $endDate);
        }

        return $query->count();
    }

    public function getUniqueVisitors($startDate = null, $endDate = null)
    {
        $query = UserSession::query();

        if ($startDate) {
            $query->where('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('created_at', '<=', $endDate);
        }

        return $query->distinct('ip_address')->count('ip_address');
    }

    public function getConversionRate($startDate = null, $endDate = null)
    {
        $totalVisitors = $this->getUniqueVisitors($startDate, $endDate);
        
        if ($totalVisitors === 0) {
            return 0;
        }

        $conversions = AnalyticsEvent::where('event_type', 'conversion')
            ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate))
            ->when($endDate, fn($q) => $q->where('created_at', '<=', $endDate))
            ->count();

        return ($conversions / $totalVisitors) * 100;
    }
}