<?php

namespace App\Http\Middleware;

use App\Models\Website;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Str;

class AnalyticsTrackingMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Initial request logging
            file_put_contents(
                storage_path('logs/analytics_debug.log'),
                "\n" . date('Y-m-d H:i:s') . " --- MIDDLEWARE IS RUNNING ---\n" .
                "URL: " . $request->fullUrl() . "\n" .
                "Method: " . $request->method() . "\n" .
                "User Agent: " . $request->userAgent() . "\n",
                FILE_APPEND
            );
            
            // Get the website ID from the request
            $websiteId = $this->getWebsiteId($request);
            
            // Only track if we have a valid website ID
            if ($websiteId) {
                // Generate a unique session ID if one doesn't exist
                if (!$request->session()->has('analytics_session_id')) {
                    $request->session()->put('analytics_session_id', Str::uuid()->toString());
                }
                $sessionId = $request->session()->get('analytics_session_id');
                
                // Track page view
                $this->trackPageView($request, $sessionId, $websiteId);
                
                // Log tracking attempt
                file_put_contents(
                    storage_path('logs/analytics_debug.log'),
                    date('Y-m-d H:i:s') . " - Tracking page view with sessionId: {$sessionId}\n",
                    FILE_APPEND
                );
            } else {
                // Log when website is not found
                file_put_contents(
                    storage_path('logs/analytics_debug.log'),
                    date('Y-m-d H:i:s') . " - No valid website ID found for tracking\n",
                    FILE_APPEND
                );
            }
            
            return $next($request);
        } catch (\Exception $e) {
            // Log the error but don't interrupt the request
            \Log::error('Analytics tracking error: ' . $e->getMessage());
            return $next($request);
        }
    }

    private function getWebsiteId(Request $request): ?int
    {
        $url = $request->fullUrl();
        $domain = parse_url($url, PHP_URL_HOST);
        
        // Debug logging
        file_put_contents(
            storage_path('logs/analytics_debug.log'),
            date('Y-m-d H:i:s') . " - Website Detection - URL: {$url}, Domain: {$domain}\n",
            FILE_APPEND
        );
        
        $website = Website::where('domain', $domain)->first();
        
        if ($website) {
            file_put_contents(
                storage_path('logs/analytics_debug.log'),
                date('Y-m-d H:i:s') . " - Found website: " . json_encode($website->toArray()) . "\n",
                FILE_APPEND
            );
            return $website->id;
        }
        
        // If no website found, log the issue
        file_put_contents(
            storage_path('logs/analytics_debug.log'),
            date('Y-m-d H:i:s') . " - No website found for domain: {$domain}\n",
            FILE_APPEND
        );
        
        return null;

        // Remove port number if present
        $host = explode(':', $host)[0];
        
        // Get website by domain
        $website = Website::where('domain', $host)->first();
        return $website ? $website->id : null;
    }

    protected function trackPageView(Request $request, string $sessionId, int $websiteId): void
    {
        try {
            $event = new \App\Models\AnalyticsEvent();
            $event->event_type = 'page_view';
            $event->website_id = $websiteId;
            $event->session_id = $sessionId;
            
            // Page and URL tracking
            $event->page_url = $request->path();
            $event->url = $request->fullUrl(); // Add the url field that analytics queries need
            
            // User and session info
            $event->user_agent = $request->userAgent();
            $event->ip_address = $request->ip();
            $event->user_id = auth()->check() ? auth()->id() : null;
            $event->method = $request->method();
            
            // Geolocation lookup
            $geolocationService = new \App\Services\GeolocationService();
            $locationData = $geolocationService->getLocationFromIP($request->ip());
            $event->country = $locationData['country'];
            $event->city = $locationData['city'];
            
            // Referrer tracking
            $event->referrer = $request->header('referer');
            $event->referrer_url = $request->header('referer'); // Both fields for compatibility
            
            // Debug basic fields immediately after setting
            file_put_contents(
                storage_path('logs/analytics_debug.log'),
                date('Y-m-d H:i:s') . " - Basic fields set:\n" .
                "page_url: " . ($event->page_url ?? 'NULL') . "\n" .
                "url: " . ($event->url ?? 'NULL') . "\n" .
                "user_agent: " . ($event->user_agent ?? 'NULL') . "\n" .
                "ip_address: " . ($event->ip_address ?? 'NULL') . "\n" .
                "method: " . ($event->method ?? 'NULL') . "\n" .
                "referrer: " . ($event->referrer ?? 'NULL') . "\n",
                FILE_APPEND
            );
            
            // Device and browser detection with debugging
            $deviceType = $this->getDeviceType($request);
            $browser = $this->getBrowserInfo($request);
            $platform = $this->getPlatformInfo($request);
            
            $event->device_type = $deviceType;
            $event->browser = $browser;
            $event->os = $platform;
            $event->platform = $platform;
            
            // Debug device detection
            file_put_contents(
                storage_path('logs/analytics_debug.log'),
                date('Y-m-d H:i:s') . " - Device Detection:\n" .
                "device_type: " . $deviceType . "\n" .
                "browser: " . $browser . "\n" .
                "platform: " . $platform . "\n" .
                "user_agent: " . $request->userAgent() . "\n",
                FILE_APPEND
            );
            
            // UTM parameters with debugging
            $event->utm_source = $request->get('utm_source');
            $event->utm_medium = $request->get('utm_medium');
            $event->utm_campaign = $request->get('utm_campaign');
            $event->utm_term = $request->get('utm_term');
            $event->utm_content = $request->get('utm_content');
            
            // Debug UTM values
            file_put_contents(
                storage_path('logs/analytics_debug.log'),
                date('Y-m-d H:i:s') . " - UTM Values:\n" .
                "utm_source: " . $request->get('utm_source') . "\n" .
                "utm_medium: " . $request->get('utm_medium') . "\n" .
                "utm_campaign: " . $request->get('utm_campaign') . "\n",
                FILE_APPEND
            );
            
            // Session tracking
            if (!$request->session()->has('landing_page')) {
                $request->session()->put('landing_page', $request->fullUrl());
                $event->landing_page = $request->fullUrl();
            } else {
                $event->landing_page = $request->session()->get('landing_page');
            }
            
            // Store additional data in meta_data field (Laravel handles JSON automatically)
            $event->meta_data = [
                'query_params' => $request->query(),
                'is_ajax' => $request->ajax(),
                'is_secure' => $request->secure(),
                'port' => $request->getPort()
            ];
            
            // Log event data before saving with specific field checks
            file_put_contents(
                storage_path('logs/analytics_debug.log'),
                date('Y-m-d H:i:s') . " - Event data before save:\n" .
                "url: " . ($event->url ?? 'NULL') . "\n" .
                "utm_source: " . ($event->utm_source ?? 'NULL') . "\n" .
                "device_type: " . ($event->device_type ?? 'NULL') . "\n" .
                "browser: " . ($event->browser ?? 'NULL') . "\n" .
                "user_agent: " . ($event->user_agent ?? 'NULL') . "\n" .
                "Full event array:\n" . json_encode($event->toArray(), JSON_PRETTY_PRINT) . "\n",
                FILE_APPEND
            );
            
            $event->save();
            
            // Log successful event creation with fresh data from database
            $savedEvent = \App\Models\AnalyticsEvent::find($event->id);
            file_put_contents(
                storage_path('logs/analytics_debug.log'),
                date('Y-m-d H:i:s') . " - Successfully tracked page view (saved data):\n" .
                json_encode($savedEvent->toArray(), JSON_PRETTY_PRINT) . "\n",
                FILE_APPEND
            );
            
            // Update or create session
            $this->updateSession($event);
            
        } catch (\Exception $e) {
            // Log any errors that occur during tracking
            file_put_contents(
                storage_path('logs/analytics_debug.log'),
                date('Y-m-d H:i:s') . " - Error tracking page view: " . $e->getMessage() . "\n" .
                "Stack trace: " . $e->getTraceAsString() . "\n",
                FILE_APPEND
            );
        }

            // return $next($request);
        // } catch (\Exception $e) {
        //     // Log any errors
        //     file_put_contents(
        //         storage_path('logs/analytics_debug.log'),
        //         date('Y-m-d H:i:s') . " - Error in middleware: " . $e->getMessage() . "\n" .
        //         "Stack trace: " . $e->getTraceAsString() . "\n",
        //         FILE_APPEND
        //     );
        //     return $next($request);
        // }
    }

    private function updateSession(\App\Models\AnalyticsEvent $event): void
    {
        try {
            $session = \App\Models\UserSession::firstOrNew(['session_id' => $event->session_id]);
            
            if (!$session->exists) {
                $session->website_id = $event->website_id;
                $session->user_agent = $event->user_agent;
                $session->ip_address = $event->ip_address;
            }
            
            $session->save();
            
            // Log successful session update
            file_put_contents(
                storage_path('logs/analytics_debug.log'),
                date('Y-m-d H:i:s') . " - Successfully updated session:\n" .
                json_encode($session->toArray(), JSON_PRETTY_PRINT) . "\n",
                FILE_APPEND
            );
            
        } catch (\Exception $e) {
            // Log any errors that occur during session update
            file_put_contents(
                storage_path('logs/analytics_debug.log'),
                date('Y-m-d H:i:s') . " - Error updating session: " . $e->getMessage() . "\n" .
                "Stack trace: " . $e->getTraceAsString() . "\n",
                FILE_APPEND
            );
        }
    }

    protected function getDeviceType(Request $request): string
    {
        $userAgent = strtolower($request->header('User-Agent'));
        
        if (str_contains($userAgent, 'mobile')) {
            return 'mobile';
        } elseif (str_contains($userAgent, 'tablet')) {
            return 'tablet';
        }
        
        return 'desktop';
    }

    protected function getBrowserInfo(Request $request): string
    {
        $userAgent = $request->header('User-Agent');
        
        if (str_contains($userAgent, 'Chrome')) {
            return 'Chrome';
        } elseif (str_contains($userAgent, 'Firefox')) {
            return 'Firefox';
        } elseif (str_contains($userAgent, 'Safari')) {
            return 'Safari';
        }
        
        return 'Other';
    }

    protected function getPlatformInfo(Request $request): string
    {
        $userAgent = strtolower($request->header('User-Agent'));
        
        if (str_contains($userAgent, 'windows')) {
            return 'Windows';
        } elseif (str_contains($userAgent, 'macintosh') || str_contains($userAgent, 'mac os')) {
            return 'MacOS';
        } elseif (str_contains($userAgent, 'linux')) {
            return 'Linux';
        } elseif (str_contains($userAgent, 'iphone')) {
            return 'iOS';
        } elseif (str_contains($userAgent, 'android')) {
            return 'Android';
        }
        
        return 'Other';
    }
}
