<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GeolocationService
{
    /**
     * Get full location data from IP address
     * 
     * @param string $ip
     * @return array ['country_code' => 'US', 'country' => 'United States', 'state' => 'California', 'city' => 'Los Angeles']
     */
    public function getLocationFromIP($ip)
    {
        // Skip local/private IPs
        if ($this->isLocalIP($ip)) {
            return $this->getDefaultLocation();
        }
        
        // Check cache first (cache for 24 hours)
        $cacheKey = 'geoip_' . md5($ip);
        
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
        
        try {
            // Use free ip-api.com service (100 requests per minute limit)
            $response = @file_get_contents("http://ip-api.com/json/{$ip}?fields=status,country,countryCode,regionName,city", false, stream_context_create([
                'http' => [
                    'timeout' => 3,
                    'ignore_errors' => true
                ]
            ]));
            
            if ($response) {
                $data = json_decode($response, true);
                
                if ($data && $data['status'] === 'success') {
                    $locationData = [
                        'country_code' => $data['countryCode'] ?? 'US',
                        'country' => $data['country'] ?? 'United States',
                        'state' => $data['regionName'] ?? null,
                        'city' => $data['city'] ?? null
                    ];
                    
                    // Cache the result
                    Cache::put($cacheKey, $locationData, now()->addHours(24));
                    
                    return $locationData;
                }
            }
        } catch (\Exception $e) {
            Log::warning("GeoIP lookup failed for IP {$ip}: " . $e->getMessage());
        }
        
        // Fallback: try to detect from Accept-Language header
        $fallbackLocation = $this->getLocationFromFallback();
        Cache::put($cacheKey, $fallbackLocation, now()->addHours(1)); // Cache fallback for shorter time
        
        return $fallbackLocation;
    }
    
    /**
     * Check if IP is local/private
     */
    protected function isLocalIP($ip)
    {
        if ($ip === '127.0.0.1' || $ip === '::1') {
            return true;
        }
        
        if (strpos($ip, '192.168.') === 0 || strpos($ip, '10.') === 0 || strpos($ip, '172.') === 0) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Get default location for local IPs
     */
    protected function getDefaultLocation()
    {
        return [
            'country_code' => 'US',
            'country' => 'United States',
            'state' => null,
            'city' => null
        ];
    }
    
    /**
     * Fallback method to detect country from browser headers
     */
    protected function getLocationFromFallback()
    {
        // Try to get from Accept-Language header
        $acceptLanguage = request()->header('Accept-Language', '');
        
        if (preg_match('/([a-z]{2})-([A-Z]{2})/', $acceptLanguage, $matches)) {
            $countryCode = strtoupper($matches[2]);
            
            return [
                'country_code' => $countryCode,
                'country' => $this->getCountryName($countryCode),
                'state' => null,
                'city' => null
            ];
        }
        
        // Default fallback
        return $this->getDefaultLocation();
    }
    
    /**
     * Get country name from country code
     */
    protected function getCountryName($countryCode)
    {
        $countries = [
            'US' => 'United States',
            'CA' => 'Canada',
            'GB' => 'United Kingdom',
            'AU' => 'Australia',
            'DE' => 'Germany',
            'FR' => 'France',
            'IN' => 'India',
            'MX' => 'Mexico',
            'BR' => 'Brazil',
            'JP' => 'Japan',
            'IT' => 'Italy',
            'ES' => 'Spain',
            'NL' => 'Netherlands',
            'SE' => 'Sweden',
            'CH' => 'Switzerland',
            'CN' => 'China',
            'RU' => 'Russia',
            'KR' => 'South Korea',
            'AR' => 'Argentina',
            'PL' => 'Poland',
        ];
        
        return $countries[$countryCode] ?? $countryCode;
    }
    
    /**
     * Get just the country code (for backward compatibility)
     */
    public function getCountryCode($ip)
    {
        $location = $this->getLocationFromIP($ip);
        return $location['country_code'];
    }
}
