<?php

namespace App\Services;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;

class UniqueVisitorService
{
    protected $cookieName = '_charity_visitor_id';
    protected $cookieLifetime = 30 * 24 * 60; // 30 days in minutes
    
    /**
     * Get or create unique visitor ID using Shopify's approach
     * Primary: Browser cookie (30-day expiry)
     * Fallback: Generate new ID for each request if cookies blocked
     */
    public function getUniqueVisitorId(Request $request)
    {
        // Try to get existing visitor ID from cookie
        $visitorId = $request->cookie($this->cookieName);
        
        if (!$visitorId) {
            // Generate new unique visitor ID
            $visitorId = $this->generateVisitorId();
            
            // Set cookie for 30 days (Shopify's default)
            Cookie::queue(
                $this->cookieName,
                $visitorId,
                $this->cookieLifetime,
                '/', // path
                null, // domain
                false, // secure (set to true in production with HTTPS)
                true, // httpOnly
                false, // raw
                'Lax' // sameSite
            );
        }
        
        return $visitorId;
    }
    
    /**
     * Generate unique visitor ID
     * Format: timestamp + random string (like Shopify)
     */
    protected function generateVisitorId()
    {
        return time() . '.' . Str::random(16);
    }
    
    /**
     * Track visitor with complete analytics data
     */
    public function trackVisitor(Request $request, $websiteId = null)
    {
        $visitorId = $this->getUniqueVisitorId($request);
        $sessionId = $request->session()->getId();
        
        // Detect website if not provided
        if (!$websiteId) {
            $websiteId = $this->detectWebsiteId($request);
        }
        
        // Get user agent details
        $userAgent = $request->userAgent();
        $deviceInfo = $this->parseUserAgent($userAgent);
        
        // Prepare visitor data
        $visitorData = [
            'visitor_id' => $visitorId,
            'session_id' => $sessionId,
            'website_id' => $websiteId,
            'ip_address' => $request->ip(),
            'user_agent' => $userAgent,
            'device_type' => $deviceInfo['device_type'],
            'browser' => $deviceInfo['browser'],
            'operating_system' => $deviceInfo['os'],
            'referrer' => $request->header('referer'),
            'landing_page' => $request->fullUrl(),
            'country' => $this->getCountryFromIP($request->ip()),
            'visited_at' => Carbon::now(),
        ];
        
        // Store or update visitor record
        $this->storeVisitorData($visitorData);
        
        return $visitorId;
    }
    
    /**
     * Detect website ID from request (like your existing system)
     */
    protected function detectWebsiteId(Request $request)
    {
        $host = $request->getHost();
        $website = \App\Models\Website::where('domain', $host)->first();
        return $website ? $website->id : null;
    }
    
    /**
     * Parse user agent for device information
     */
    protected function parseUserAgent($userAgent)
    {
        // Use existing Jenssegers/Agent if available, or simple detection
        if (class_exists('\Jenssegers\Agent\Agent')) {
            $agent = new \Jenssegers\Agent\Agent();
            $agent->setUserAgent($userAgent);
            
            return [
                'device_type' => $agent->isMobile() ? 'mobile' : ($agent->isTablet() ? 'tablet' : 'desktop'),
                'browser' => $agent->browser(),
                'os' => $agent->platform()
            ];
        }
        
        // Fallback simple detection
        $isMobile = preg_match('/Mobile|Android|iPhone|iPad/', $userAgent);
        return [
            'device_type' => $isMobile ? 'mobile' : 'desktop',
            'browser' => $this->detectBrowser($userAgent),
            'os' => $this->detectOS($userAgent)
        ];
    }
    
    /**
     * Simple browser detection
     */
    protected function detectBrowser($userAgent)
    {
        if (strpos($userAgent, 'Chrome') !== false) return 'Chrome';
        if (strpos($userAgent, 'Firefox') !== false) return 'Firefox';
        if (strpos($userAgent, 'Safari') !== false) return 'Safari';
        if (strpos($userAgent, 'Edge') !== false) return 'Edge';
        return 'Unknown';
    }
    
    /**
     * Simple OS detection
     */
    protected function detectOS($userAgent)
    {
        if (strpos($userAgent, 'Windows') !== false) return 'Windows';
        if (strpos($userAgent, 'Mac') !== false) return 'macOS';
        if (strpos($userAgent, 'Linux') !== false) return 'Linux';
        if (strpos($userAgent, 'Android') !== false) return 'Android';
        if (strpos($userAgent, 'iOS') !== false) return 'iOS';
        return 'Unknown';
    }
    
    /**
     * Get country from IP using free GeoIP service
     */
    protected function getCountryFromIP($ip)
    {
        // Skip local/private IPs
        if ($ip === '127.0.0.1' || $ip === '::1' || strpos($ip, '192.168.') === 0 || strpos($ip, '10.') === 0) {
            return 'US'; // Default for local development
        }
        
        try {
            // Use free ip-api.com service (100 requests per minute limit)
            $response = file_get_contents("http://ip-api.com/json/{$ip}?fields=status,country,countryCode");
            $data = json_decode($response, true);
            
            if ($data && $data['status'] === 'success') {
                return $data['countryCode'];
            }
        } catch (\Exception $e) {
            \Log::warning("GeoIP lookup failed for IP {$ip}: " . $e->getMessage());
        }
        
        // Fallback: try to detect from Accept-Language header or other methods
        return $this->getCountryFromFallback();
    }
    
    /**
     * Fallback method to detect country
     */
    protected function getCountryFromFallback()
    {
        // Try to get from Accept-Language header
        $acceptLanguage = request()->header('Accept-Language', '');
        
        if (preg_match('/([a-z]{2})-([A-Z]{2})/', $acceptLanguage, $matches)) {
            return strtoupper($matches[2]);
        }
        
        // Default fallback
        return 'US';
    }
    
    /**
     * Store visitor data in database
     */
    protected function storeVisitorData($data)
    {
        // Update or create visitor record
        \App\Models\UniqueVisitor::updateOrCreate(
            [
                'visitor_id' => $data['visitor_id'],
                'website_id' => $data['website_id']
            ],
            $data
        );
        
        // Also track page view
        \App\Models\PageView::create([
            'visitor_id' => $data['visitor_id'],
            'session_id' => $data['session_id'],
            'website_id' => $data['website_id'],
            'url' => $data['landing_page'],
            'referrer' => $data['referrer'],
            'viewed_at' => $data['visited_at']
        ]);
    }
    
    /**
     * Check if visitor is returning (has existing cookie)
     */
    public function isReturningVisitor(Request $request)
    {
        return $request->hasCookie($this->cookieName);
    }
    
    /**
     * Get visitor analytics for a website
     */
    public function getVisitorStats($websiteId, $startDate, $endDate)
    {
        return [
            'unique_visitors' => \App\Models\UniqueVisitor::where('website_id', $websiteId)
                ->whereBetween('visited_at', [$startDate, $endDate])
                ->count(),
                
            'total_sessions' => \App\Models\PageView::where('website_id', $websiteId)
                ->whereBetween('viewed_at', [$startDate, $endDate])
                ->distinct('session_id')
                ->count(),
                
            'total_page_views' => \App\Models\PageView::where('website_id', $websiteId)
                ->whereBetween('viewed_at', [$startDate, $endDate])
                ->count(),
                
            'returning_visitors' => \App\Models\UniqueVisitor::where('website_id', $websiteId)
                ->whereBetween('visited_at', [$startDate, $endDate])
                ->where('visited_at', '>', \DB::raw('created_at'))
                ->count()
        ];
    }
}
